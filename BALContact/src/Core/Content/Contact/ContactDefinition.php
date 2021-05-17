<?php declare(strict_types=1);

namespace BAL\Contact\Core\Content\Contact;

use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use BAL\Contact\Core\Content\Contact\Aggregate\ContactProduct\ContactProductDefinition;

class ContactDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'bal_contact';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }
    public function getEntityClass(): string
    {
        return ContactEntity::class;
    }


    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new StringField('firstname', 'firstname'))->addFlags(new Required()),
            (new StringField('lastname', 'lastname'))->addFlags(new Required()),
            (new StringField('phonenumber', 'phonenumber'))->addFlags(new Required()),
            (new StringField('email', 'email'))->addFlags(new Required()),
            new ManyToManyAssociationField('products', ProductDefinition::class, ContactProductDefinition::class, 'contact_id', 'product_id'),
        ]);
    }
}
