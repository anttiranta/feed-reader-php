<?php
namespace App\Antti\FeedsAppItemBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Antti\FeedsAppItemBundle\Util\Item\CollectionUtils;

/**
 * @ORM\Entity(repositoryClass="App\Antti\FeedsAppItemBundle\Repository\ItemRepository")
 * @ORM\Table(name="feeds_app_item", indexes={@ORM\Index(name="title_idx", columns={"title"})})
 * @ORM\HasLifecycleCallbacks
 */
class Item
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Length(max=255)
     * @Assert\Length(min=2)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(max=1000)
     * @Assert\Url
     */
    private $link;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="published_date", type="datetime")
     */
    private $pubDate;
    
    /** 
     * @var string
     * @ORM\Column(name="published_timezone", type="string", nullable=true) 
     */
    private $pubTimezone;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="items", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Length(max=1000)
     * @Assert\Url
     */
    private $comments;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPubDate(): ?\DateTime
    {
        if ($this->pubDate !== null && $this->pubTimezone !== null) {
            if ($this->pubDate->getTimeZone()->getName() !== $this->pubTimezone) {
                $this->assignTimezoneToDateTime(
                    $this->pubDate, 
                    $this->pubTimezone
                );
            }
        }
        return $this->pubDate;
    }

    public function setPubDate(\DateTime $pubDate): self
    {
        $this->pubDate = $pubDate;
        
        if($this->pubDate->getTimeZone()) {
            $this->pubTimezone = $this->pubDate->getTimeZone()->getName();
        }
        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(string $comments): self
    {
        $this->comments = $comments;
        return $this;
    }
    
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $date): self
    {
        $this->createdAt = $date;
        return $this;
    }
    
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $date): self
    {
        $this->updatedAt = $date;
        return $this;
    }
    
    /**
    * @ORM\PrePersist
    */
    public function prePersist() 
    {
        $this->prepareForSave();
    }
    
    /**
    * @ORM\PreUpdate
    */
    public function preUpdate() 
    {
        if ($this->getId() > 0) {
            $this->setUpdatedAt(new \DateTime());
        }
        $this->prepareForSave();
    }
    
    private function prepareForSave()
    {
        if ($this->getCategory() != null) {
            if (CollectionUtils::inCollection($this, $this->getCategory()->getItems())) {
                $this->getCategory()->getItems()->set(
                    CollectionUtils::collectionSearch($this, $this->getCategory()->getItems()),
                    $this
                );
            } else {
                $this->getCategory()->getItems()->add($this);
            }
        }
    }
    
    private function assignTimezoneToDateTime(\DateTime &$date, string $tzName) 
    {
        $date = new \DateTime(
            $date->format('Y-m-d\TH:i:s'), 
            new \DateTimeZone($tzName)
        );
    }
}
