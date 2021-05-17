<?php
declare(strict_types=1);

namespace BAL\Contact\Core\Content\Product;

use BAL\Contact\Core\Content\Contact\Aggregate\ContactProduct\ContactProductDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Inherited;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use BAL\Contact\Core\Content\Contact\ContactDefinition;

class ProductExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new ManyToManyAssociationField(
                'contacts',
                ContactDefinition::class,
                ContactProductDefinition::class,
                'product_id',
                'contact_id'
            ))->addFlags(new Inherited())
        );
    }

    public function getDefinitionClass(): string
    {
        return ProductDefinition::class;
    }
}
