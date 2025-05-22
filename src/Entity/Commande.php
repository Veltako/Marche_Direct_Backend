<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Filter\UserCommandeFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource(
    operations: [
        new Patch(
            formats: ['json' => ['application/json']]
        ),
        new GetCollection(),
        new Post()
    ],
    paginationItemsPerPage:6,
    paginationClientItemsPerPage: true,
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ApiFilter(UserCommandeFilter::class)]
#[ORM\Table(name: '`Commande`')]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::JSON)]
    #[Groups(['read', 'write'])]
    private array $produits_commande = [];
    

    /**
     * @var Collection<int, Historique>
     */
    #[ORM\ManyToMany(targetEntity: Historique::class, inversedBy: 'commandes' , fetch: "LAZY")]
    #[MaxDepth(1)]
    private Collection $historique;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'commandes' , fetch: "LAZY")]
    #[MaxDepth(1)]
    #[Groups(['read', 'write'])]
    private Collection $UserCommande;

    /**
     * @var Collection<int, Produit>
     */
    #[ORM\ManyToMany(targetEntity: Produit::class, mappedBy: 'commande' , fetch: "LAZY")]
    #[MaxDepth(1)]
    #[Groups(['read', 'write'])]
    private Collection $produits;

    #[ORM\ManyToOne(inversedBy: 'commandes' , fetch: "LAZY")]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[Groups(['read', 'write'])]
    private ?Etat $etat = null;

    #[ORM\ManyToOne(inversedBy: 'Commande')]
    #[ORM\JoinColumn(nullable: false)]
    #[MaxDepth(1)]
    #[Groups(['read', 'write'])]
    private ?Marche $marche = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $jour = null;

    public function __construct()
    {
        $this->historique = new ArrayCollection();
        $this->UserCommande = new ArrayCollection();
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduitsCommande(): array
    {
        return $this->produits_commande;
    }

    public function setProduitsCommande(array $produits_commande): static
    {
        $this->produits_commande = $produits_commande;

        return $this;
    }

    /**
     * @return Collection<int, Historique>
     */
    public function getHistorique(): Collection
    {
        return $this->historique;
    }

    public function addHistorique(Historique $historique): static
    {
        if (!$this->historique->contains($historique)) {
            $this->historique->add($historique);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): static
    {
        $this->historique->removeElement($historique);

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getUserCommande(): Collection
    {
        return $this->UserCommande;
    }

    public function addUserCommande(user $userCommande): static
    {
        if (!$this->UserCommande->contains($userCommande)) {
            $this->UserCommande->add($userCommande);
        }

        return $this;
    }

    public function removeUserCommande(user $userCommande): static
    {
        $this->UserCommande->removeElement($userCommande);

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->addCommande($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            $produit->removeCommande($this);
        }

        return $this;
    }

    public function getEtat(): ?etat
    {
        return $this->etat;
    }

    public function setEtat(?etat $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getMarche(): ?Marche
    {
        return $this->marche;
    }

    public function setMarche(?Marche $marche): static
    {
        $this->marche = $marche;

        return $this;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): static
    {
        $this->jour = $jour;

        return $this;
    }
}
