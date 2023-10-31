<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarsRepository;

#[ORM\Entity(repositoryClass: CarsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Cars
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[ORM\Column(length: 255)]
    private ?string $coverImg = null;

    #[ORM\Column]
    private ?int $km = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $proprietaire = null;

    #[ORM\Column]
    private ?int $cylindree = null;

    #[ORM\Column]
    private ?int $puissance = null;

    #[ORM\Column(length: 255)]
    private ?string $carburant = null;

    #[ORM\Column(length: 4)]
    private ?string $annee = null;

    #[ORM\Column(length: 255)]
    private ?string $transmission = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $options = null;

    #[ORM\Column(length: 255)]
    private ?string $slugMarque = null;

    #[ORM\Column(length: 255)]
    private ?string $slugModele = null;

    #[ORM\OneToMany(mappedBy: 'cars', targetEntity: Image::class, orphanRemoval: true)]
    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    /**
     * Permet d'initialiser le slug automatiquement si on ne le donne pas
     *
     * @return void
     */
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeSlug(): void 
    {
        if(empty($this->slugMarque))
        {
            $slugify = new Slugify();
            $this->slugMarque = $slugify->slugify($this->marque);
        }

        if(empty($this->slugModele))
        {
            $slugify = new Slugify();
            $this->slugModele = $slugify->slugify($this->modele);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getCoverImg(): ?string
    {
        return $this->coverImg;
    }

    public function setCoverImg(string $coverImg): static
    {
        $this->coverImg = $coverImg;

        return $this;
    }

    public function getKm(): ?int
    {
        return $this->km;
    }

    public function setKm(int $km): static
    {
        $this->km = $km;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getProprietaire(): ?int
    {
        return $this->proprietaire;
    }

    public function setProprietaire(int $proprietaire): static
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getCylindree(): ?int
    {
        return $this->cylindree;
    }

    public function setCylindree(int $cylindree): static
    {
        $this->cylindree = $cylindree;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): static
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): static
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): static
    {
        $this->transmission = $transmission;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(string $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getSlugMarque(): ?string
    {
        return $this->slugMarque;
    }

    public function setSlugMarque(string $slugMarque): static
    {
        $this->slugMarque = $slugMarque;

        return $this;
    }

    public function getSlugModele(): ?string
    {
        return $this->slugModele;
    }

    public function setSlugModele(string $slugModele): static
    {
        $this->slugModele = $slugModele;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setCars($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getCars() === $this) {
                $image->setCars(null);
            }
        }

        return $this;
    }
}
