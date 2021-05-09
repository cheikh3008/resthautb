<?php

namespace App\Entity;

use App\Entity\Reservation;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\RestoController;
use App\Repository\RestoRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 * collectionOperations={
 *   "get" = {
 *      "normalization_context"={"groups"={"resto_read"}}    
 *  },
 *      "post"
 * },
 *     itemOperations={
 *  "get" = {
 *      "normalization_context"={"groups"={"resto_read_details"}}    
 *  }
 * , "put", "delete"},
 *   
 * )
 * @ORM\Entity(repositoryClass=RestoRepository::class)
 * @ApiResource(iri="http://schema.org/Book")
 */
class Resto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"resto_read", "resto_read_details"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"resto_read", "resto_read_details"})
     * @Assert\NotBlank()
     */
    private $nomResto;

    /**
     * @ORM\Column(type="text")
     * @Groups({"resto_read", "resto_read_details"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"resto_read", "resto_read_details"})
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="resto")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Plat::class, mappedBy="resto")
     */
    private $plat;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="resto")
     */
    private $reservation;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="resto")
     */
    private $commande;

    /**
     * @ORM\Column(type="blob")
     */
    private $image;

    public function __construct()
    {
        $this->plat = new ArrayCollection();
        $this->commande = new ArrayCollection();
        $this->reservation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomResto(): ?string
    {
        return $this->nomResto;
    }

    public function setNomResto(string $nomResto): self
    {
        $this->nomResto = $nomResto;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

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
     * @return Collection|Plat[]
     */
    public function getPlat(): Collection
    {
        return $this->plat;
    }

    public function addPlat(Plat $plat): self
    {
        if (!$this->plat->contains($plat)) {
            $this->plat[] = $plat;
            $plat->setResto($this);
        }

        return $this;
    }

    public function removePlat(Plat $plat): self
    {
        if ($this->plat->removeElement($plat)) {
            // set the owning side to null (unless already changed)
            if ($plat->getResto() === $this) {
                $plat->setResto(null);
            }
        }

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
            $reservation->setResto($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getResto() === $this) {
                $reservation->setResto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commande->contains($commande)) {
            $this->commande[] = $commande;
            $commande->setResto($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getResto() === $this) {
                $commande->setResto(null);
            }
        }

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }
    

}
