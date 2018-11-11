<?php

declare(strict_types=1);
/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MajidMvulle\Bundle\StorageBundle\Metadata;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

class AmazonMetadataBuilder
{
    const PRIVATE_ACCESS = 'private';
    const PUBLIC_READ = 'public-read';
    const PUBLIC_READ_WRITE = 'public-read-write';
    const AUTHENTICATED_READ = 'authenticated-read';
    const BUCKET_OWNER_READ = 'bucket-owner-read';
    const BUCKET_OWNER_FULL_CONTROL = 'bucket-owner-full-control';

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var string[]
     */
    protected $acl = [
        'private' => self::PRIVATE_ACCESS,
        'public' => self::PUBLIC_READ,
        'open' => self::PUBLIC_READ_WRITE,
        'auth_read' => self::AUTHENTICATED_READ,
        'owner_read' => self::BUCKET_OWNER_READ,
        'owner_full_control' => self::BUCKET_OWNER_FULL_CONTROL,
    ];

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    public function get($dummyVariable, $filename): array
    {
        return array_replace_recursive(
            $this->getDefaultMetadata(),
            $this->getContentType($filename)
        );
    }

    protected function getDefaultMetadata(): array
    {
        //merge acl
        $output = [];
        if (isset($this->settings['acl']) && !empty($this->settings['acl'])) {
            $output['ACL'] = $this->acl[$this->settings['acl']];
        }

        //merge meta
        if (isset($this->settings['meta']) && !empty($this->settings['meta'])) {
            $output['meta'] = $this->settings['meta'];
        }

        //merge cache control header
        if (isset($this->settings['cache_control']) && !empty($this->settings['cache_control'])) {
            $output['CacheControl'] = $this->settings['cache_control'];
        }

        //merge encryption
        if (isset($this->settings['encryption']) && !empty($this->settings['encryption'])) {
            if ('aes256' === $this->settings['encryption']) {
                $output['encryption'] = 'AES256';
            }
        }

        return $output;
    }

    protected function getContentType($filename): array
    {
        return ['contentType' => MimeTypeGuesser::getInstance()->guess($filename)];
    }
}
