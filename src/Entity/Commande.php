<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"commande:read"}},
 * )
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"commande:read" , "plat:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"commande:read" , "plat:read"})
     */
    private $numCommande;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"commande:read" , "plat:read"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"commande:read" , "plat:read"})
     */
    private $createdAt;

    
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commande")
     * @Groups({"commande:read" , "plat:read"})
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Plat::class, mappedBy="commande")
     * @Groups({"commande:read"})
     */
    private $plats;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"commande:read" , "plat:read"})
     */
    private $isValid;

    public function __construct()
    {
        $this->plats = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
        $this->isValid = false;
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCommande(): ?string
    {
        return $this->numCommande;
    }

    public function setNumCommande(string $numCommande): self
    {
        $this->numCommande = $numCommande;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): self
    {
        if (!$this->plats->contains($plat)) {
            $this->plats[] = $plat;
            $plat->addCommande($this);
        }

        return $this;
    }

    public function removePlat(Plat $plat): self
    {
        if ($this->plats->removeElement($plat)) {
            $plat->removeCommande($this);
        }

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    
}
