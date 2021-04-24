<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TableRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TableRepository::class)
 * @ORM\Table(name="`table`")
 */
class Table
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nbPersonne;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tables")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Reservation::class, inversedBy="tables")
     */
    private $reservation;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPersonne(): ?string
    {
        return $this->nbPersonne;
    }

    public function setNbPersonne(string $nbPersonne): self
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

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }

}
