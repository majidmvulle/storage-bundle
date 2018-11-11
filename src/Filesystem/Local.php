<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\Filesystem;

use Gaufrette\Adapter\Local as BaseLocal;

/**
 * Class Local.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class Local extends BaseLocal
{
    public function getDirectory(): string
    {
        return $this->directory;
    }
}
