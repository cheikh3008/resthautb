<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlatCommandeRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * normalizationContext={"groups"={"plat_commande:read"}},
 * )
 * @ORM\Entity(repositoryClass=PlatCommandeRepository::class)
 */
class PlatCommande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"plat:read", "plat_commande:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Plat::class, inversedBy="platCommandes")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"plat:read", "commande:read", "plat_commande:read"})
     */
    private $plat;

    /**
     * @ORM\ManyToOne(targetEntity=Commande::class, inversedBy="platCommandes", cascade="remove")
     * @ORM\JoinColumn(nullable=false)
     */
    private $commande;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"plat:read", "commande:read", "plat_commande:read"})
     */
    private $quantite;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlat(): ?Plat
    {
        return $this->plat;
    }

    public function setPlat(?Plat $plat): self
    {
        $this->plat = $plat;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    
}
