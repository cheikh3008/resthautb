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
 * , "put" = {
 *  "normalization_context"={"groups"={"plat:edit"}},
 * }, 
 * "delete" = {
 *  "normalization_context"={"groups"={"plat:delete"}},
 * }},
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
     * @Groups({"plat:read", "menu:read", "plat:edit", "plat:delete", "commande:read", "plat_commande:read" , "resto:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"plat:read", "plat:edit", "plat:delete" ,"menu:read", "commande:read", "plat_commande:read" })
     */
    private $nomPlat;

    /**
     * @ORM\Column(type="text")
     * @Groups({"plat:read", "plat:edit", "plat:delete" ,"menu:read", "commande:read", "plat_commande:read" })
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"plat:read", "plat:edit", "plat:delete" ,"menu:read", "commande:read", "plat_commande:read" })
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
     * @Groups({"plat:edit"})
     */
    private $menus;

    /**
     * @ORM\OneToMany(targetEntity=PlatCommande::class, mappedBy="plat", cascade="remove")
     * @Groups({"plat:read", "plat:delete"})
     */
    private $platCommandes;
   

    public function __construct()
    {
        $this->quantite = 1;
        $this->menus = new ArrayCollection();
        $this->commande = new ArrayCollection();
        $this->platCommandes = new ArrayCollection();
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
     * @return Collection|PlatCommande[]
     */
    public function getPlatCommandes(): Collection
    {
        return $this->platCommandes;
    }

    public function addPlatCommande(PlatCommande $platCommande): self
    {
        if (!$this->platCommandes->contains($platCommande)) {
            $this->platCommandes[] = $platCommande;
            $platCommande->setPlat($this);
        }

        return $this;
    }

    public function removePlatCommande(PlatCommande $platCommande): self
    {
        if ($this->platCommandes->removeElement($platCommande)) {
            // set the owning side to null (unless already changed)
            if ($platCommande->getPlat() === $this) {
                $platCommande->setPlat(null);
            }
        }

        return $this;
    }

    
}
