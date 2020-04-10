<?php
namespace App\Antti\FeedsAppItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Antti\FeedsAppItemBundle\Repository\CategoryRepository")
 * @ORM\Table(name="feeds_app_item_category", uniqueConstraints={@ORM\UniqueConstraint(name="domain_idx", columns={"category_domain"})}, indexes={@ORM\Index(name="name_idx", columns={"name"})})
 */
class Category
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\Length(max=255)
     * @Assert\Length(min=2)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="category_domain", type="string", nullable=false)
     * @Assert\Length(max=255)
     * @Assert\Length(min=2)
     * @Assert\Url
     */
    private $domain;
    
    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="category")
     */
    private $items;
    
    public function __construct() 
    {
        $this->items = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setDomain(string $domain): self
    {
        $this->domain = $domain;
        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }
    
    public function getItems() :Collection
    {
        return $this->items;
    }

    public function __toString(): string 
    {
        return 'id: ' . $this->getId() . ', '
            . 'name: ' . $this->getName() . ', '
            . 'domain: ' . $this->getDomain();
    }
}