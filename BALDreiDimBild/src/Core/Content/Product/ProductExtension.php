<?php
declare(strict_types=1);

namespace BAL\DreiDimBild\Core\Content\Product;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use BAL\DreiDimBild\Core\Content\Three\Aggregate\ThreeProduct\ThreeProductDefinition;
use BAL\DreiDimBild\Core\Content\Three\ThreeDefinition;

class ProductExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'threes',
                ThreeDefinition::class,
                ThreeProductDefinition::class,
                'product_id',
                'three_id'
            ))->addFlags(new Inherited())
        );
    }

    public function getDefinitionClass(): string
    {
        return ProductDefinition::class;
    }
}
