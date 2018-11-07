<?php

declare(strict_types=1);

namespace MajidMvulle\Bundle\StorageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Gallery.
 *
 * @author Majid Mvulle <majid@majidmvulle.com>
 *
 * @ORM\Entity(repositoryClass="MajidMvulle\Bundle\StorageBundle\Bundle\Repository\GalleryRepository")
 * @ORM\Table(name="majidmvulle_gallery")
 */
class Gallery
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="related_entity", type="string", length=255)
     */
    protected $relatedEntity;

    /**
     * @var string
     *
     * @ORM\Column(name="related_entity_id", type="string", length=100)
     */
    protected $relatedEntityId;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean", options={"default": true})
     */
    protected $enabled;

    /**
     * @var GalleryMedia
     *
     * @ORM\OneToMany(targetEntity="MajidMvulle\Bundle\StorageBundle\Bundle\Entity\GalleryMedia", mappedBy="gallery")
     */
    protected $galleryMedia;
    /**
     * @var \DateTimeInterface
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTimeInterface
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;
}
