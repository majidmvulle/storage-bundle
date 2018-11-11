<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\Filesystem;

use Gaufrette\Adapter\AwsS3 as BaseAwsS3;

/**
 * Class AwsS3.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class AwsS3 extends BaseAwsS3 implements RemoteFilesystemInterface
{
}
