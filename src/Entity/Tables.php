<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use App\Repository\TablesRepository;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"tables:read"}},
 * )
 * @ORM\Entity(repositoryClass=TablesRepository::class)
 */
class Tables
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"reservation:read", "tables:read", "resto:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"reservation:read", "tables:read", "resto:read"})
     */
    private $numero;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"reservation:read", "tables:read", "resto:read"})
     */
    private $nbPersonne;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Resto::class, inversedBy="tables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resto;

    /**
     * @ORM\ManyToMany(targetEntity=Reservation::class, inversedBy="tables")
     * @JoinTable(name="tables_reservation")
     * @Groups({"tables:read"})
     */
    private $reservation;

    public function __construct()
    {
        $this->reservation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getNbPersonne(): ?int
    {
        return $this->nbPersonne;
    }

    public function setNbPersonne(int $nbPersonne): self
    {
        $this->nbPersonne = $nbPersonne;

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

    public function getResto(): ?Resto
    {
        return $this->resto;
    }

    public function setResto(?Resto $resto): self
    {
        $this->resto = $resto;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation[] = $reservation;
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        $this->reservation->removeElement($reservation);

        return $this;
    }
}
