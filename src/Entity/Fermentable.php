<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FermentableRepository")
 */
class Fermentable
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
     * @Groups({"Listing", "Details"})
     */
    private string $name = '';

    /**
     * @ORM\Column(type="float")
     *
     * @Groups({"Listing", "Details"})
     *
     * @Assert\NotNull()
     * @Assert\GreaterThan(0)
     */
    private float $quantity = 0.0;

    /**
     * @ORM\Column(type="float")
     *
     * @Groups({"Listing", "Details"})
     *
     * @Assert\NotNull()
     * @Assert\GreaterThan(0)
     */
    private float $color = 0.0;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe", inversedBy="fermentables")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Recipe $recipe = null;

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

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getColor(): float
    {
        return $this->color;
    }

    public function setColor(float $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }
}
