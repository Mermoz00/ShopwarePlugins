<?php declare(strict_types=1);

namespace BAL\Contact\Core\Content\Contact\Aggregate\ContactProduct;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use BAL\Contact\Core\Content\Contact\ContactDefinition;

class ContactProductDefinition extends MappingEntityDefinition
{
    public function getEntityName(): string
    {
        return 'bal_contact_product';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('contact_id', 'contactId', ContactDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('product_id', 'productId', ProductDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new ReferenceVersionField(ProductDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('contact', 'contact_id', ContactDefinition::class),
            new ManyToOneAssociationField('product', 'product_id', ProductDefinition::class),
            new CreatedAtField(),
        ]);
    }
}
