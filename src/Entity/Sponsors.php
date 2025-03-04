<?php

namespace App\Entity;

use App\Repository\SponsorsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SponsorsRepository::class)]
class Sponsors
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"nom is require")]
    #[ORM\Column(length: 255)]
    private ?string $nom_sponsor = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image_path = null;

    /**
     * @var Collection<int, Evenements>
     */
    #[ORM\ManyToMany(targetEntity: Evenements::class, mappedBy: 'sponsors')]
    private Collection $evenements;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_creation = null;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
        $this->date_creation = new \DateTime(); // Set current date
    }  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomSponsor(): ?string
    {
        return $this->nom_sponsor;
    }

    public function setNomSponsor(string $nom_sponsor): static
    {
        $this->nom_sponsor = $nom_sponsor;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->image_path;
    }

    public function setImagePath(?string $image_path): static
    {
        $this->image_path = $image_path;

        return $this;
    }

    /**
     * @return Collection<int, Evenements>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenements $evenement): static
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->addSponsor($this);
        }

        return $this;
    }

    public function removeEvenement(Evenements $evenement): static
    {
        if ($this->evenements->removeElement($evenement)) {
            $evenement->removeSponsor($this);
        }

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }
}
