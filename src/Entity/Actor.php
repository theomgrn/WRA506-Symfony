<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ActorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['actor:read']],
)]
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['actor:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 70)]
    #[Groups(['movie:read', 'actor:read'])]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    private ?string $firstName = null;

    #[ORM\Column(length: 70)]
    #[Groups(['movie:read', 'actor:read'])]
    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    private ?string $lastName = null;

    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'actor')]
    #[Groups(['actor:read'])]
    private Collection $movies;

    #[ORM\ManyToOne(inversedBy: 'actor')]
    #[Groups(['actor:read'])]
    private ?Nationality $nationality = null;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): static
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
            $movie->addActor($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): static
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeActor($this);
        }

        return $this;
    }

    public function getNationality(): ?Nationality
    {
        return $this->nationality;
    }

    public function setNationality(?Nationality $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }
}
