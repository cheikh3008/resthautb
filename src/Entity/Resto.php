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
 *         
 *  },
 *      "post"
 * },
 *     itemOperations={
 *      "get" = {
 *             
 *      },
 *      "put"={
 *      "normalization_context"={"groups"={"resto:edit"}},
 * }, 
 *      "delete"},
 *      normalizationContext={"groups"={"resto:read"}},
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
     * @Groups({"resto:read", "user:edit", "resto:edit"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"resto:read", "user:edit", "resto:edit"})
     * @Assert\NotBlank()
     */
    private $nomResto;

    /**
     * @ORM\Column(type="text")
     * @Groups({"resto:read", "user:edit", "resto:edit"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"resto:read", "user:edit", "resto:edit"})
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="resto")
     * @Groups({"resto:read", "resto:edit"})
    */
    private $user;

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
     * @Groups({"resto:read"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Tables::class, mappedBy="resto", orphanRemoval=true)
     *  @Groups({"resto:read"})
     */
    private $tables;

    /**
     * @ORM\OneToMany(targetEntity=Menu::class, mappedBy="resto", orphanRemoval=true)
     */
    private $menu;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
        $this->reservation = new ArrayCollection();
        $this->tables = new ArrayCollection();
        $this->menu = new ArrayCollection();
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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

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
            $table->setResto($this);
        }

        return $this;
    }

    public function removeTable(Tables $table): self
    {
        if ($this->tables->removeElement($table)) {
            // set the owning side to null (unless already changed)
            if ($table->getResto() === $this) {
                $table->setResto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenu(): Collection
    {
        return $this->menu;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menu->contains($menu)) {
            $this->menu[] = $menu;
            $menu->setResto($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menu->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getResto() === $this) {
                $menu->setResto(null);
            }
        }

        return $this;
    }
    

}
