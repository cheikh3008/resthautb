<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\PlatController;
use App\Repository\PlatRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * collectionOperations={
 *   "get" = {
 *         
 *  },
 *      "post" = {}
 * },
 *     itemOperations={
 *  "get" = {
 *          
 *  }
 * , "put", "delete"},
 * normalizationContext={"groups"={"plat:read"}},
 *   
 * )
 * @ORM\Entity(repositoryClass=PlatRepository::class)
 */
class Plat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"plat:read", "menu:read", "commande:read" , "resto:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"plat:read", "menu:read", "commande:read" })
     */
    private $nomPlat;

    /**
     * @ORM\Column(type="text")
     * @Groups({"plat:read", "menu:read", "commande:read" })
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"plat:read", "menu:read", "commande:read" })
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="plat")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"plat:read", "menu:read", "commande:read" , "resto:read"})
     */
    private $quantite;

    /**
     * @ORM\ManyToMany(targetEntity=Menu::class, mappedBy="plat")
     */
    private $menus;

    /**
     * @ORM\ManyToMany(targetEntity=Commande::class, inversedBy="plats")
     * @Groups({"plat:read"})
     */
    private $commande;

   

    public function __construct()
    {
        $this->quantite = 1;
        $this->menus = new ArrayCollection();
        $this->commande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPlat(): ?string
    {
        return $this->nomPlat;
    }

    public function setNomPlat(string $nomPlat): self
    {
        $this->nomPlat = $nomPlat;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

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


    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->addPlat($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removePlat($this);
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
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        $this->commande->removeElement($commande);

        return $this;
    }

    
}
