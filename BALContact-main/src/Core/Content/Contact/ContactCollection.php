<?php declare(strict_types=1);

namespace BAL\Contact\Core\Content\Contact;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(ContactEntity $entity)
 * @method void              set(string $key, ContactEntity $entity)
 * @method ContactEntity[]    getIterator()
 * @method ContactEntity[]    getElements()
 * @method ContactEntity|null get(string $key)
 * @method ContactEntity|null first()
 * @method ContactEntity|null last()
 */
class ContactCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ContactEntity::class;
    }
}
