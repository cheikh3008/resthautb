<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
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
 *  "normalization_context"={"groups"={"menu:edit"}},
 * }, 
 * "delete"},
 * normalizationContext={"groups"={"menu:read"}},
 *)
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"menu:read", "plat:read", "plat:edit" , "resto:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"menu:read", "plat:read", "plat:edit" , "resto:read"})
     */
    private $categorie;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="menu")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Resto::class, inversedBy="menu")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resto;

    /**
     * @ORM\ManyToMany(targetEntity=Plat::class, inversedBy="menus")
     * @Groups({"menu:read"})
     */
    private $plat;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"menu:read" ,"resto:read"})
     */
    private $image;
  

    public function __construct()
    {
        $this->plat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

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
        }

        return $this;
    }

    public function removePlat(Plat $plat): self
    {
        $this->plat->removeElement($plat);

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
