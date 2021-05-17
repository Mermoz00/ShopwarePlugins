<?php declare(strict_types=1);

namespace BAL\DreiDimBild\Storefront\Page\Product\Subscriber;

use Shopware\Core\Framework\Struct\Struct;

class ViewerBackgroundOptions extends Struct
{
    public $image = null;
    public $color = null;
}
