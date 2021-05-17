<?php declare(strict_types=1);

namespace BAL\DreiDimBild\Storefront\Page\Product\Subscriber;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

use Shopware\Storefront\Page\Product\ProductLoaderCriteriaEvent;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class ProductPageCriteriaSubscriber implements EventSubscriberInterface
{
    private $categoryRepo;
    private $mediaRepo;

    public function __construct(
        EntityRepositoryInterface $categoryRepo,
        EntityRepositoryInterface $mediaRepo
    )
    {
        $this->categoryRepo = $categoryRepo;
        $this->mediaRepo = $mediaRepo;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductLoaderCriteriaEvent::class => 'onProductCriteriaLoaded',
            ProductPageLoadedEvent::class => 'onProductsLoaded'
        ];
    }

    public function onProductCriteriaLoaded(ProductLoaderCriteriaEvent $event): void
    {
        $event->getCriteria()->addAssociation('threes.products.cover');
    }

    public function onProductsLoaded(ProductPageLoadedEvent $event): void
    {
        $categoryIds = $event->getPage()->getProduct()->getCategoryTree();
        $categories = $this->categoryRepo->search(
            new Criteria($categoryIds), Context::createDefaultContext()
        );

        $id = null;
        $image = null;
        $color = null;
        $check=false;
        if (isset($event->getPage()->getProduct()->getCustomFields()["custom_bg_color"])) {
            $color = $event->getPage()->getProduct()->getCustomFields()["custom_bg_color"];
            $check=true;
        }
        if (isset($event->getPage()->getProduct()->getCustomFields()["custom_bg_image"])) {
            $id = $event->getPage()->getProduct()->getCustomFields()["custom_bg_image"];
            if (!is_null($id)) {
                $image = $this->checkIfBgImageExist($id);
                if (!is_null($image)) {
                    $check=true;
                }
            }
        }
        if($check==false){
            for ($i = count($categoryIds) - 1; $i >= 0; $i--) {
                if (isset($categories->getEntities()->getElements()[$categoryIds[$i]]->getCustomFields()["custom_bg_color"])) {
                    $color = $categories->getEntities()->getElements()[$categoryIds[$i]]->getCustomFields()["custom_bg_color"];
                }
                if (isset($categories->getEntities()->getElements()[$categoryIds[$i]]->getCustomFields()["custom_bg_image"])) {
                    $id = $categories->getEntities()->getElements()[$categoryIds[$i]]->getCustomFields()["custom_bg_image"];
                }
                if (!is_null($id)) {
                    $image = $this->checkIfBgImageExist($id);
                }

            }
        }
        if (!is_null($id) || !is_null($color)) {
            $entities = [
                "image" => $image ? $image : null,
                "color" => $color ? $color : "#FFFFFF0"
            ];
            $struct = new ViewerBackgroundOptions();
            $struct->color = $entities["color"];
            $struct->image = $entities["image"];
            $event->getPage()->addExtension('data', $struct);
        }
    }

    /**
     * @param $id
     * @return string|null
     */
    public function checkIfBgImageExist($id): ?string
    {

        $media = $this->mediaRepo->search(
            new Criteria([
                $id
            ]),
            Context::createDefaultContext()
        );
        if ($media->getTotal() >= 1) {
            $array = $media->getEntities()->getElements();
            $temp = array_shift($array)->getUrl();
            if (is_null($temp)) {
                return null;
            } else {
                return "/media" . explode('media', $temp, 2)[1];
            }
        } else {
            return null;
        }

    }

}
