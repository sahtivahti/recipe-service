<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
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
     *
     * @Groups({"Listing", "Details"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     *
     * @Groups({"Listing", "Details"})
     */
    private string $name = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Email()
     *
     * @Groups({"Details"})
     */
    private string $author = '';

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(groups={"Create"})
     *
     * @Groups({"Details"})
     */
    private string $userId = '';

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"Listing", "Details"})
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Groups({"Listing", "Details"})
     */
    private DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"Listing", "Details"})
     */
    private string $style = '';

    /**
     * @ORM\Column(type="float", precision=10, scale=2)
     *
     * @Assert\GreaterThanOrEqual(0.00)
     *
     * @Groups({"Details"})
     */
    private float $batchSize = 0.00;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Hop", mappedBy="recipe", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"time" = "DESC"})
     *
     * @Groups({"Details"})
     */
    private Collection $hops;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Fermentable", mappedBy="recipe", orphanRemoval=true, cascade={"persist"})
     *
     * @Groups({"Details"})
     */
    private Collection $fermentables;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->hops = new ArrayCollection();
        $this->fermentables = new ArrayCollection();
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

    /**
     * @return Collection|Fermentable[]
     */
    public function getFermentables(): Collection
    {
        return $this->fermentables;
    }

    public function addFermentable(Fermentable $fermentable): self
    {
        if (!$this->fermentables->contains($fermentable)) {
            $this->fermentables[] = $fermentable;
            $fermentable->setRecipe($this);
        }

        return $this;
    }

    public function removeFermentable(Fermentable $fermentable): self
    {
        if ($this->fermentables->contains($fermentable)) {
            $this->fermentables->removeElement($fermentable);
            // set the owning side to null (unless already changed)
            if ($fermentable->getRecipe() === $this) {
                $fermentable->setRecipe(null);
            }
        }

        return $this;
    }
}
