<?php

namespace App\Entity;

use App\Repository\BuyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BuyRepository::class)
 */
class Buy
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Games::class, inversedBy="buys")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game_id;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="buys")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $game_key;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pay_id;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameId(): ?Games
    {
        return $this->game_id;
    }

    public function setGameId(?Games $game_id): self
    {
        $this->game_id = $game_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id[] = $userId;
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        if ($this->user_id->contains($userId)) {
            $this->user_id->removeElement($userId);
        }

        return $this;
    }

    public function getGameKey(): ?string
    {
        return $this->game_key;
    }

    public function setGameKey(string $game_key): self
    {
        $this->game_key = $game_key;

        return $this;
    }

    public function getPayId(): ?string
    {
        return $this->pay_id;
    }

    public function setPayId(string $pay_id): self
    {
        $this->pay_id = $pay_id;

        return $this;
    }
}
