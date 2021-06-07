<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"reservation:read"}},
 * )
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"reservation:read" , "tables:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"reservation:read" , "tables:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

        /**
     * @ORM\Column(type="time")
     * @Groups({"reservation:read" , "tables:read"})
     */
    private $heure;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservation")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"reservation:read"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Tables::class, mappedBy="reservation")
     * @Groups({"reservation:read"})
     */
    private $tables;

    public function __construct()
    {
        $this->updatedAt = new \DateTime("now");
        $this->tables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
   
    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Tables[]
     */
    public function getTables(): Collection
    {
        return $this->tables;
    }

    public function addTable(Tables $table): self
    {
        if (!$this->tables->contains($table)) {
            $this->tables[] = $table;
            $table->addReservation($this);
        }

        return $this;
    }

    public function removeTable(Tables $table): self
    {
        if ($this->tables->removeElement($table)) {
            $table->removeReservation($this);
        }

        return $this;
    }
}
