<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\Entity;

use Ramsey\Uuid\UuidInterface;
use Sonata\UserBundle\Entity\BaseUser;

/**
 * Interface HasMediaInterface.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
interface HasMediaInterface
{
    public function getId(): UuidInterface;

    public function getGallery(): ?Gallery;

    public function setGallery(?Gallery $gallery): self;

    public function getUser(): BaseUser;

    public function getFolder(): string;
}
