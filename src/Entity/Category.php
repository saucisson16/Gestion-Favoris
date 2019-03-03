<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @UniqueEntity(fields="name", message="Cette catégorie existe déjà.")
 */
class Category
{
    /**
     * @ORM\OneToMany(targetEntity="Link", mappedBy="category")
     * @Assert\Valid()
     */
    private $links;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Veuillez saisir un nom de catégorie valide.")*
     * @Assert\NotNull()
     * @Assert\Length(
     *     min="2",
     *     max="50",
     *     minMessage="Une catégorie doit contenir au moins {{ limit }} caractères.",
     *     maxMessage="Une catégorie ne peut contenir plus de {{ limit }} caractères."
     * )
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="photoPath", type="string", length=255, nullable=true)
     * @Assert\Image(
     *     maxSize="4M",
     *     allowPortrait = false,
     *     minWidth="800",
     *     minHeight="600",
     *     maxSizeMessage="Votre image doit ne peut pas faire plus de 4Mo.",
     *     minHeightMessage="Votre image doit faire minimun 800x600px. (Hauteur de {{ height }}px actuellement)",
     *     minWidthMessage="Votre image doit faire minimun 800x600px. (Largeur de {{ width }}px actuellement)",
     *     allowPortraitMessage="Votre image doit être au format paysage. (Format portrait actuellement)"
     * )
     */
    private $photoPath;

    /**
     * @var integer $position
     *
     * @Gedmo\SortablePosition()
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->links = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add link
     *
     * @param Link $link
     *
     * @return Category
     */
    public function addLink(Link $link)
    {
        $this->links[] = $link;

        return $this;
    }

    /**
     * Remove link
     *
     * @param Link $link
     */
    public function removeLink(Link $link)
    {
        $this->links->removeElement($link);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set photoPath
     *
     * @param string $photoPath
     *
     * @return Category
     */
    public function setPhotoPath($photoPath)
    {
        $this->photoPath = $photoPath;

        return $this;
    }

    /**
     * Get photoPath
     *
     * @return string
     */
    public function getPhotoPath()
    {
        return $this->photoPath;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}
