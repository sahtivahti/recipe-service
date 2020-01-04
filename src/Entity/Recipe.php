<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private string $name = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private string $author = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(groups={"Create"})
     */
    private string $userId = '';

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $style = '';

    /**
     * @ORM\Column(type="float", precision=10, scale=2)
     *
     * @Assert\GreaterThanOrEqual(0.00)
     */
    private float $batchSize = 0.00;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hop", mappedBy="recipe", orphanRemoval=true)
     */
    private ArrayCollection $hops;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->hops = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStyle(): string
    {
        return $this->style;
    }

    public function setStyle(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getBatchSize(): float
    {
        return $this->batchSize;
    }

    public function setBatchSize(float $batchSize): self
    {
        $this->batchSize = $batchSize;

        return $this;
    }

    /**
     * @return Collection|Hop[]
     */
    public function getHops(): Collection
    {
        return $this->hops;
    }

    public function addHop(Hop $hop): self
    {
        if (!$this->hops->contains($hop)) {
            $this->hops[] = $hop;
            $hop->setRecipe($this);
        }

        return $this;
    }

    public function removeHop(Hop $hop): self
    {
        if ($this->hops->contains($hop)) {
            $this->hops->removeElement($hop);
            // set the owning side to null (unless already changed)
            if ($hop->getRecipe() === $this) {
                $hop->setRecipe(null);
            }
        }

        return $this;
    }
}
