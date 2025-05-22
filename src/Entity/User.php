<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
/*     operations: [
        new Patch(
            formats: ['json' => ['application/json']]
        ),
        new GetCollection(),
    ], */
    paginationItemsPerPage:6,
    paginationClientItemsPerPage: true,
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ORM\Table(name: '`User`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Cet e-mail est déjà lié à un compte existant.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank(message:"L'email est obligatoire.")]
    #[Assert\Length(min:1, max:180, minMessage:"L'email doit faire au moiins {{ limit }} caractères", maxMessage:"L'email ne peut pas faire plus de {{ limit }} caractères.")]
    #[Assert\Regex('/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/g')]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['read', 'write'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message:"Le mot de passe est obligatoire.")]
    #[Assert\PasswordStrength(minScore: 4, message:"Votre mot de passe doit être plus fort")]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank(message:"Le pseudo est obligatoire.")]
    #[Assert\Length(min:1, max:255, minMessage:"Votre pseudo doit faire au moins {{ limit }} caractères", maxMessage:"Votre pseudo ne peut pas faire plus de {{ limit }} caractères.")]
    private ?string $userName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank(message:"Le numéro de téléphone est obligatoire.")]
    #[Assert\Length(min:4, max:12, minMessage:"Le numéro doit faire au moins {{ limit }} caractères", maxMessage:"Le numéro ne peut pas faire plus de {{ limit }} caractères.")]
    private ?string $tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read', 'write'])]
    #[Assert\NotBlank(message:"Le nom de l'entreprise est obligatoire.")]
    #[Assert\Length(min:4, max:255, minMessage:"Le nom de l'entreprise doit faire au moins {{ limit }} caractères", maxMessage:"Le nom de l'entreprise ne peut pas faire plus de {{ limit }} caractères.")]
    private ?string $nameBusiness = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['read', 'write'])]
    private ?array $stats = null;

    /**
     * @var Collection<int, Marche>
     */
    #[ORM\ManyToMany(targetEntity: Marche::class, mappedBy: 'commercant_marche' , fetch: "EXTRA_LAZY")]
    #[MaxDepth(1)]
    private Collection $commercant_marche;

    /**
     * @var Collection<int, Historique>
     */
    #[ORM\OneToMany(targetEntity: Historique::class, mappedBy: 'userHisto' , fetch: "LAZY")]
    #[MaxDepth(1)]
    private Collection $historiques;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\ManyToMany(targetEntity: Commande::class, mappedBy: 'UserCommande' , fetch: "LAZY")]
    #[MaxDepth(1)]
    private Collection $commandes;

    /**
     * @var Collection<int, Produit>
    */
    #[ORM\OneToMany(targetEntity: Produit::class, mappedBy: 'userProduct', cascade: ['persist', 'remove'] , fetch: "EXTRA_LAZY")]
    #[MaxDepth(1)]
    #[Groups(['read', 'write'])]
    private Collection $produits;

    #[ORM\Column(length: 255)]
    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imageFileName')]
    #[Groups(['read', 'write'])]
    private ?string $imageFileName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $descriptionCommerce = null;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\ManyToMany(targetEntity: Comment::class, mappedBy: 'user' , fetch: "LAZY")]
    #[MaxDepth(1)]
    private Collection $comments;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read'])] 
    private ?\DateTimeInterface $dateDeCreation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $numSiret = null;

    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'users' , fetch: "LAZY")]
    #[ORM\JoinTable(name: 'user_categorie')]
    #[MaxDepth(1)]
    #[Groups(['read', 'write'])]
    private Collection $userCategorie;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $resetTokenExpiresAt = null;

    // Getter et setter pour resetToken
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    // Getter et setter pour resetTokenExpiresAt
    public function getResetTokenExpiresAt():?\DateTime 
    {
        return $this->resetTokenExpiresAt;
    }

    public function setResetTokenExpiresAt(?\DateTime $resetTokenExpiresAt): self
    {
        $this->resetTokenExpiresAt = $resetTokenExpiresAt;
        return $this;
    }

    public function __construct()
    {
        $this->commercant_marche = new ArrayCollection();
        $this->historiques = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->produits = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->userCategorie = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->userName ?? 'Unknown User';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): static
    {
        $this->userName = $userName;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getNameBusiness(): ?string
    {
        return $this->nameBusiness;
    }

    public function setNameBusiness(string $nameBusiness): static
    {
        $this->nameBusiness = $nameBusiness;

        return $this;
    }

    public function getStats(): ?array
    {
        return $this->stats;
    }

    public function setStats(?array $stats): static
    {
        $this->stats = $stats;

        return $this;
    }

    /**
     * @return Collection<int, Marche>
     */
    public function getCommercantMarche(): Collection
    {
        return $this->commercant_marche;
    }

    public function addCommercantMarche(Marche $commercantMarche): static
    {
        if (!$this->commercant_marche->contains($commercantMarche)) {
            $this->commercant_marche->add($commercantMarche);
            $commercantMarche->addCommercantMarche($this);
        }

        return $this;
    }

    public function removeCommercantMarche(Marche $commercantMarche): static
    {
        if ($this->commercant_marche->removeElement($commercantMarche)) {
            $commercantMarche->removeCommercantMarche($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Historique>
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): static
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques->add($historique);
            $historique->setUserHisto($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): static
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getUserHisto() === $this) {
                $historique->setUserHisto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->addUserCommande($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            $commande->removeUserCommande($this);
        }

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
            $produit->setUserProduct($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getUserProduct() === $this) {
                $produit->setUserProduct(null);
            }
        }

        return $this;
    }

    public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(string $imageFileName): static
    {
        $this->imageFileName = $imageFileName;

        return $this;
    }

    public function getDescriptionCommerce(): ?string
    {
        return $this->descriptionCommerce;
    }

    public function setDescriptionCommerce(string $descriptionCommerce): static
    {
        $this->descriptionCommerce = $descriptionCommerce;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            $comment->setUser($this);
        }

        return $this;
    }

    public function getDateDeCreation(): ?\DateTimeInterface
    {
        return $this->dateDeCreation;
    }

    public function setDateDeCreation(\DateTimeInterface $dateDeCreation): static
    {
        $this->dateDeCreation = $dateDeCreation;

        return $this;
    }

    public function getNumSiret(): ?string
    {
        return $this->numSiret;
    }

    public function setNumSiret(?string $numSiret): static
    {
        $this->numSiret = $numSiret;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getUserCategorie(): Collection
    {
        return $this->userCategorie;
    }

    public function addUserCategorie(Categorie $userCategorie): static
    {
        if (!$this->userCategorie->contains($userCategorie)) {
            $this->userCategorie->add($userCategorie);
        }

        return $this;
    }

    public function removeUserCategorie(Categorie $userCategorie): static
    {
        $this->userCategorie->removeElement($userCategorie);

        return $this;
    }
}
