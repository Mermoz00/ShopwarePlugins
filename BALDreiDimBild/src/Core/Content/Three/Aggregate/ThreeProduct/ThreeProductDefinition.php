<?php declare(strict_types=1);

namespace BAL\DreiDimBild\Core\Content\Three\Aggregate\ThreeProduct;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use BAL\DreiDimBild\Core\Content\Three\ThreeDefinition;

class ThreeProductDefinition extends MappingEntityDefinition
{
    public function getEntityName(): string
    {
        return 'bal_three_product';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('three_id', 'threeId', ThreeDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('three', 'three_id', ThreeDefinition::class),
            new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class),
            new CreatedAtField(),
        ]);
    }
}
