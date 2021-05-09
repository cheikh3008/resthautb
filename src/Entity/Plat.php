<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\PlatController;
use App\Repository\PlatRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 * collectionOperations={
 *   "get" = {
 *      "normalization_context"={"groups"={"plat_read"}}    
 *  },
 *      "post" = {}
 * },
 *     itemOperations={
 *  "get" = {
 *      "normalization_context"={"groups"={"plat_read_details"}}    
 *  }
 * , "put", "delete"},
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
     * @Groups({"menu_read", "menu_read_details", "plat_read_details", "plat_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"menu_read", "menu_read_details", "plat_read_details", "plat_read"})
     */
    private $nomPlat;

    /**
     * @ORM\Column(type="blob")
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     * @Groups({"menu_read", "menu_read_details", "plat_read_details", "plat_read"})
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups({"menu_read", "menu_read_details", "plat_read_details", "plat_read"})
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="plat")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Menu::class, inversedBy="plat")
     * Groups({plat_read_details", "plat_read"})
     */
    private $menu;

    /**
     * @ORM\ManyToOne(targetEntity=Resto::class, inversedBy="plat")
     */
    private $resto;

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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

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

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

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
}
