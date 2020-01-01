<?php
declare(strict_types = 1);

namespace App\Model;

use Doctrine\ORM\QueryBuilder;

class RecipeSearchFilters
{
    private ?string $name = null;

    private ?string $author = null;

    private ?string $userId = null;

    private ?int $page = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string|null $author
     *
     * @return self
     */
    public function setAuthor(?string $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param string|null $userId
     *
     * @return self
     */
    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPage(): ?int
    {
        return $this->page;
    }

    /**
     * @param int|null $page
     *
     * @return self
     */
    public function setPage(?int $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function applyTo(QueryBuilder $builder): QueryBuilder
    {
        if ($this->getUserId() !== null) {
            $builder->andWhere('r.userId = :userId')->setParameter('userId', $this->getUserId());
        }

        if ($this->getName() !== null) {
            $builder->andWhere('r.name like :name')->setParameter('name', '%' . $this->getName() . '%');
        }

        if ($this->getAuthor() !== null) {
            $builder->andWhere('r.author = :author')->setParameter('author', $this->getAuthor());
        }

        if ($this->getPage() !== null) {
            $builder->setFirstResult(($this->getPage() - 1) * 20);
        }

        return $builder;
    }
}
