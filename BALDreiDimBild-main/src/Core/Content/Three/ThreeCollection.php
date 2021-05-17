<?php declare(strict_types=1);

namespace BAL\DreiDimBild\Core\Content\Three;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(ThreeEntity $entity)
 * @method void              set(string $key, ThreeEntity $entity)
 * @method ThreeEntity[]    getIterator()
 * @method ThreeEntity[]    getElements()
 * @method ThreeEntity|null get(string $key)
 * @method ThreeEntity|null first()
 * @method ThreeEntity|null last()
 */
class ThreeCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return ThreeEntity::class;
    }
}
