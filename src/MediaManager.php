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

    public function saveFile(UploadedFile $file, HasMediaInterface $relatedEntity): Media
    {
        $folder = trim($relatedEntity->getFolder());

        $dateString = (new \DateTime())->format('Ymd');
        $userId = (int) $relatedEntity->getUser()->getId();
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
        $media->setBasePath(sprintf('%s%s', $this->container->getParameter('majidmvulle.storage.base_path'), $folder));
        $media->setPath($path);
        $media->setEnabled(true);
        $media->setGalleryMedia(null); //reset to not have ArrayCollection issue
        $filesystem = $this->container->get($this->getFilesystemName());

        if ($filesystem instanceof Local) {
            $media->setTransportable(false);
            $media->setLocal(true);
        } else {
            $media->setTransportable(true);
            $media->setLocal(false);
        }
        $key = sprintf('%s/%s', $path, $name);
        if ($folder) {
            $key = sprintf('%s/%s', $folder, $key);
        }
        if ($filesystem->write($key, file_get_contents($realPath))) {
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
            $media = $this->saveFile($photo, $relatedEntity);
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

    public function deleteGallery(Gallery $gallery, bool $force = false): void
    {
        $galleryMedia = $gallery->getGalleryMedia();

        foreach ($galleryMedia as $gMedia) {
            $this->deleteMedia($gMedia->getMedia(), $force);
        }

        if ($force) {
            $this->entityManager->remove($gallery);
        } else {
            $gallery->setDeleted(true);
        }

        $this->entityManager->flush();
    }

    public function deleteMedia(Media $media, bool $force = false): void
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

    public function getFile(Media $media): mixed
    {
        return file_get_contents($media->getFullPath());
    }

    public function getFilesystem(): Adapter
    {
        return $this->container->get($this->getFilesystemName());
    }

    public function getFilesystemName(): string
    {
        if ($this->container->has('majidmvulle.storage.filesystem.local')) {
            return 'majidmvulle.storage.filesystem.local';
        } elseif ($this->container->has('majidmvulle.storage.filesystem.s3')) {
            return 'majidmvulle.storage.filesystem.s3';
        }
    }

    public function reorder(Media $media, int $position): void
    {
        $indexOfMedia = false;
        $galleryMedia = $media->getGallery()->getGalleryMedia();

        if ($position < 0 || $position >= $galleryMedia->count()) {
            throw new \InvalidArgumentException(sprintf('Position %d is invalid', $position));
        }

        for ($i = 0; $i < $galleryMedia->count(); ++$i) {
            if ($galleryMedia[$i]->getMedia()->getId() === $media->getId()) {
                $indexOfMedia = $i;
            }
        }

        if (false === $indexOfMedia) {
            throw new \InvalidArgumentException('Unable to reorder media');
        }

        if ($indexOfMedia === $position) {
            return;
        }

        $galleryMediaArray = $galleryMedia->toArray();

        $actualMedia = $galleryMediaArray[$indexOfMedia];

        unset($galleryMediaArray[$indexOfMedia]);

        $galleryMediaArray = array_values($galleryMediaArray);
        $aCopyOfGalleryMediaArray = $galleryMediaArray; //make a copy, just to be safe

        for ($i = $position; $i < count($aCopyOfGalleryMediaArray); ++$i) {
            $galleryMediaArray[$i + 1] = $aCopyOfGalleryMediaArray[$i];
        }

        $galleryMediaArray[$position] = $actualMedia;

        for ($i = 0; $i < count($galleryMediaArray); ++$i) {
            $galleryMediaArray[$i]->setPosition($i);
        }

        $this->entityManager->flush();
    }
}
