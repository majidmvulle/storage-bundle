<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle;

use Doctrine\ORM\EntityManagerInterface;
use Gaufrette\Adapter;
use MajidMvulle\Bundle\StorageBundle\Entity\Gallery;
use MajidMvulle\Bundle\StorageBundle\Entity\GalleryMedia;
use MajidMvulle\Bundle\StorageBundle\Entity\HasMediaInterface;
use MajidMvulle\Bundle\StorageBundle\Entity\Media;
use MajidMvulle\Bundle\StorageBundle\Filesystem\Local;
use Sonata\UserBundle\Entity\BaseUser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class MediaManager.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 */
class MediaManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function saveFile(UploadedFile $file, BaseUser $user): Media
    {
        $dateString = (new \DateTime())->format('Ymd');
        $userId = (int) $user->getId();
        $realPath = $file->getRealPath();
        $m = microtime(true);

        $name = sha1(base_convert((int) (floor($m).($m - floor($m)) * 1000000), 10, 36).$file->getClientOriginalName());
        $sizes = getimagesize($realPath);
        $path = sha1(base_convert((int) (floor($userId).($userId - floor($userId)) * 1000000), 10, 36)).'/'.$dateString;

        $media = new Media();
        $media->setName($name);
        $media->setOriginalName($file->getClientOriginalName());
        $media->setWidth($sizes[0]);
        $media->setHeight($sizes[1]);
        $media->setContentType($file->getMimeType());
        $media->setContentSize($file->getSize());
        $media->setChecksum(sha1_file($realPath));
        $media->setFilesystem($this->getFilesystemName());
        $media->setPath($path);
        $media->setEnabled(true);

        $filesystem = $this->container->get($this->getFilesystemName());

        if ($filesystem instanceof Local) {
            $media->setTransportable(false);
            $media->setLocal(true);
        } else {
            $media->setTransportable(true);
            $media->setLocal(false);
        }

        if ($filesystem->write(sprintf('%s/%s', $path, $name), file_get_contents($realPath))) {
            $this->entityManager->persist($media);
            $this->entityManager->flush();
        }

        return $media;
    }

    public function save(HasMediaInterface $relatedEntity, array $photos, bool $andFlush = true): void
    {
        $gallery = $relatedEntity->getGallery();

        if (!$gallery) {
            $gallery = new Gallery();
            $gallery->setRelatedEntity(get_class($relatedEntity));
            $gallery->setRelatedEntityId($relatedEntity->getId()->toString());
            $gallery->setEnabled(true);

            $this->entityManager->persist($gallery);

            if ($andFlush) {
                $this->entityManager->flush();
            }

            $relatedEntity->setGallery($gallery);
        }

        $galleryMedia = $gallery->getGalleryMedia();
        $galleryMediaNextIndex = $galleryMedia->count();

        /** @var UploadedFile $photo */
        foreach ($photos as $photo) {
            $media = $this->saveFile($photo, $relatedEntity->getUser());

            $galleryMedia = new GalleryMedia();
            $galleryMedia->setMedia($media);
            $galleryMedia->setGallery($gallery);
            $galleryMedia->setPosition($galleryMediaNextIndex);
            $galleryMedia->setEnabled(true);

            $this->entityManager->persist($galleryMedia);

            ++$galleryMediaNextIndex;
        }

        if ($andFlush) {
            $this->entityManager->flush();
        }

        $this->entityManager->persist($media);

        if ($andFlush) {
            $this->entityManager->flush();
        }
    }

    public function delete(Media $media, bool $force = false): void
    {
        if ($force) {
            $key = sprintf('%s/%s', $media->getPath(), $media->getName());

            if ($this->getFilesystem()->delete($key)) {
                $this->entityManager->remove($media);
            } else {
                throw new FileException('Unable to delete file');
            }
        } else {
            $media->setDeleted(true);
        }

        $this->entityManager->flush();
    }

    public function getFilePath(Media $media): string
    {
        return sprintf('%s/%s', $this->getBasePath($media), $media->getPathAndName());
    }

    public function getFile(Media $media): mixed
    {
        return file_get_contents($this->getFilePath($media));
    }

    public function getFilesystem(): Adapter
    {
        return $this->container->get($this->getFilesystemName());
    }

    public function getFilesystemName(): ?string
    {
        if ($this->container->has('majidmvulle.storage.filesystem.local')) {
            return 'majidmvulle.storage.filesystem.local';
        } elseif ($this->container->has('majidmvulle.storage.filesystem.s3')) {
            return 'majidmvulle.storage.filesystem.s3';
        }
    }

    private function getBasePath(Media $media): ?string
    {
        $filesystemName = $media->getFilesystem();

        if ('majidmvulle.storage.filesystem.local' === $filesystemName) {
            return $this->container->getParameter('majidmvulle.storage.filesystem.local.directory');
        } elseif ('majidmvulle.storage.filesystem.s3' === $filesystemName) {
            return $this->container->getParameter('majidmvulle.storage.filesystem.s3.bucket');
        }
    }
}
