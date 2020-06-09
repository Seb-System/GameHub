<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=games::class, mappedBy="game_cat")
     */
    private $category_id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $category_name;

    public function __construct()
    {
        $this->category_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|games[]
     */
    public function getCategoryId(): Collection
    {
        return $this->category_id;
    }

    public function addCategoryId(games $categoryId): self
    {
        if (!$this->category_id->contains($categoryId)) {
            $this->category_id[] = $categoryId;
            $categoryId->setGameCat($this);
        }

        return $this;
    }

    public function removeCategoryId(games $categoryId): self
    {
        if ($this->category_id->contains($categoryId)) {
            $this->category_id->removeElement($categoryId);
            // set the owning side to null (unless already changed)
            if ($categoryId->getGameCat() === $this) {
                $categoryId->setGameCat(null);
            }
        }

        return $this;
    }

    public function getCategoryName(): ?string
    {
        return $this->category_name;
    }

    public function setCategoryName(string $category_name): self
    {
        $this->category_name = $category_name;

        return $this;
    }
}
