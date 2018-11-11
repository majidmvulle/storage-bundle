<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class MajidMvulleStorageBundle.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class MajidMvulleStorageBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        $this->extension = $this->createContainerExtension();

        return parent::getContainerExtension();
    }
}
