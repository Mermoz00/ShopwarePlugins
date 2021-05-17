<?php declare(strict_types=1);

namespace BAL\DreiDimBild\Core\Content\Three;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use BAL\DreiDimBild\Core\Content\Three\Aggregate\ThreeProduct\ThreeProductDefinition;

class ThreeDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'bal_three';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
    public function getEntityClass(): string
    {
        return ThreeEntity::class;
    }


    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new StringField('path', 'path'))->addFlags(new Required()),
            (new StringField('name', 'name'))->addFlags(new Required()),
            new ManyToManyAssociationField('products', ProductDefinition::class, ThreeProductDefinition::class, 'three_id', 'product_id'),
        ]);
    }
}
