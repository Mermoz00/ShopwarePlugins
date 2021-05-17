<?php
declare(strict_types=1);

namespace BAL\ProductRequestForm\Storefront\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Validation\DataBag\RequestDataBag;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Framework\Captcha\Annotation\Captcha;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Shopware\Storefront\Controller\StorefrontController;

/**
 * @RouteScope(scopes={"storefront"})
 */
class ProductRequestFormController extends StorefrontController
{
    //private $requestStack;
    private $mailer;
    private $systemConfigService;

    public function __construct(
        Swift_Mailer $mailer,
       /* RequestStack $requestStack,*/
        SystemConfigService $systemConfigService
    )
    {
        $this->mailer = $mailer;
    /*    $this->requestStack = $requestStack;*/
        $this->systemConfigService = $systemConfigService;
    }

    /**
     * @Route("/mail/askprice", name="frontend.mail.askprice", methods={"POST"}, defaults={"XmlHttpRequest"=true})
     * @Captcha
     */
    public function askprice(RequestDataBag $data, SalesChannelContext $context): JsonResponse
    {

        $response = [];

        $receivers = [];

        $requestData = $data->all();

        $configReceiver = $this->systemConfigService->get('ProductRequestForm.config.email', $context->getSalesChannel()->getId());

        if ($configReceiver !== null && $configReceiver !== '') {
            $receivers[] = $configReceiver;
        }

        if (empty($receivers)) {
            $receivers[] = $this->systemConfigService->get('core.basicInformation.email', $context->getSalesChannel()->getId());
        }

        // TODO: Validiere Formulardaten E-Mail

        // Send Mail
        foreach ($receivers as $receiver) {
            $message = new Swift_Message();
            $message->setSubject('Neue Produktanfrage')
                ->setFrom($requestData["email"])
                ->setTo($receiver)
                ->setBody($this->buildMailBody($requestData));
            dump($receiver);
            if ($this->mailer->send($message) > 0) {
                $response[] = [
                    'type' => 'success',
                    'alert' => 'Vielen Dank für Ihre Anfrage.',
                ];
            } else {
                $response[] = [
                    'type' => 'danger',
                    'alert' => $this->renderView('@Storefront/storefront/utilities/alert.html.twig', [
                        'type' => 'danger',
                        'list' => ['Das Anfrageformular konnte nicht übermittelt werden.'],
                    ]),
                ];
            }
        }

        return new JsonResponse($response);
    }


    /**
     * @param array $requestData
     * @return string
     */
    private function buildMailBody(array $requestData): string
    {
        $message = "Anfrage zum Produkt: " . $requestData["productNumber"] . "\n\n";
        $message .= "Vorname:   " . $requestData["firstName"] . "\n";
        $message .= "Nachname:  " . $requestData["lastName"] . "\n";
        $message .= "Absender:  " . $requestData["email"] . "\n\n";
        $message .= "Nachricht: " . $requestData["comment"];

        return $message;
    }
}
