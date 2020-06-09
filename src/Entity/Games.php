<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GamesRepository::class)
 */
class Games
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $game_name;

    /**
     * @ORM\Column(type="float")
     */
    private $game_price;

    /**
     * @ORM\Column(type="integer")
     */
    private $game_note;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $game_img;

    /**
     * @ORM\Column(type="string", length=2000)
     */
    private $game_desc;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="category_id")
     */
    private $game_cat;

    /**
     * @ORM\OneToMany(targetEntity=Buy::class, mappedBy="game_id")
     */
    private $buys;

    public function __construct()
    {
        $this->buys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameName(): ?string
    {
        return $this->game_name;
    }

    public function setGameName(string $game_name): self
    {
        $this->game_name = $game_name;

        return $this;
    }

    public function getGamePrice(): ?float
    {
        return $this->game_price;
    }

    public function setGamePrice(float $game_price): self
    {
        $this->game_price = $game_price;

        return $this;
    }

    public function getGameNote(): ?int
    {
        return $this->game_note;
    }

    public function setGameNote(int $game_note): self
    {
        $this->game_note = $game_note;

        return $this;
    }

    public function getGameImg(): ?string
    {
        return $this->game_img;
    }

    public function setGameImg(string $game_img): self
    {
        $this->game_img = $game_img;

        return $this;
    }

    public function getGameDesc(): ?string
    {
        return $this->game_desc;
    }

    public function setGameDesc(string $game_desc): self
    {
        $this->game_desc = $game_desc;

        return $this;
    }

    public function getCategoryName(): ?Category
    {
        return $this->category_name;
    }

    public function setCategoryName(?Category $category_name): self
    {
        $this->category_name = $category_name;

        return $this;
    }

    public function getGameCat(): ?Category
    {
        return $this->game_cat;
    }

    public function setGameCat(?Category $game_cat): self
    {
        $this->game_cat = $game_cat;

        return $this;
    }

    /**
     * @return Collection|Buy[]
     */
    public function getBuys(): Collection
    {
        return $this->buys;
    }

    public function addBuy(Buy $buy): self
    {
        if (!$this->buys->contains($buy)) {
            $this->buys[] = $buy;
            $buy->setGameId($this);
        }

        return $this;
    }

    public function removeBuy(Buy $buy): self
    {
        if ($this->buys->contains($buy)) {
            $this->buys->removeElement($buy);
            // set the owning side to null (unless already changed)
            if ($buy->getGameId() === $this) {
                $buy->setGameId(null);
            }
        }

        return $this;
    }

}
