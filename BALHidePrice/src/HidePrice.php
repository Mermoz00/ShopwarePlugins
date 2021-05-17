<?php declare(strict_types=1);

namespace BAL\HidePrice;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\InstallContext;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;
use Shopware\Core\System\CustomField\CustomFieldTypes;

class HidePrice extends Plugin
{
    public function install(InstallContext $installContext): void
    {
        parent::install($installContext);
        $customFieldSetRepository = $this->container->get('custom_field_set.repository');
        $check=$customFieldSetRepository->search( (new Criteria())->addFilter(new EqualsFilter('name', 'custom_hide_price')),$installContext->getContext());
        if($check->getTotal()==0) {
            $customFieldSetRepository->create([
                [
                    'name' => 'custom_hide_price',
                    'config' => [
                        'label' => [
                            'de-DE' => 'Den Preis verstecken',
                            'en-GB' => 'Hide the price'
                        ]
                    ],
                    'relations' => [[
                        'entityName' => 'product'
                    ]],
                    'customFields' => [
                        [
                            'name' => 'custom_hide_price',
                            'type' => CustomFieldTypes::BOOL
                        ]
                    ]
                ]
            ], $installContext->getContext());
        }
    }
    public function uninstall(UninstallContext $uninstallContext): void
    {
        parent::uninstall($uninstallContext);

        if ($uninstallContext->keepUserData()) {
            return;
        }
        $customFieldSetRepository = $this->container->get('custom_field_set.repository');
        $temp=$customFieldSetRepository->search(
            (new Criteria())->addFilter(new EqualsFilter('name', 'custom_hide_price'))
            ,Context::createDefaultContext());
        if($temp->getTotal()==1){
            $temp1=$temp->getEntities()->getElements();
            $id=array_shift($temp1)->getId();

            $customFieldSetRepository->delete([
                ['id'=>$id]
            ],$uninstallContext->getContext());
        }

    }
}
