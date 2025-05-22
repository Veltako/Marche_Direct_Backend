<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Post;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ApiResource(
    operations: [
        new Patch(
            formats: ['json' => ['application/json']]
        ),
        new Delete(),
        new GetCollection(),
        new Post(
            formats: ['json' => ['application/json']]
        ),
    ],
    paginationItemsPerPage: 6,
    paginationClientItemsPerPage: true,
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
#[ORM\Table(name: '`Produit`')]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(name: 'product_name', length: 255)]
    #[Groups(['read', 'write'])]
    private ?string $productName = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['read', 'write'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    private ?float $prix = null;

    #[ORM\Column]
    #[Groups(['read', 'write'])]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[MaxDepth(1)]
    #[Groups(['read', 'write'])]
    private ?User $userProduct = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\ManyToMany(targetEntity: Commande::class, inversedBy: 'produits', fetch: "LAZY")]
    #[MaxDepth(1)]
    private Collection $commande;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Vich\UploadableField(mapping: 'Products', fileNameProperty: 'imageFileName')]
    #[Groups(['read', 'write'])]
    private ?string $imageFileName = null;

    #[ORM\ManyToOne(inversedBy: 'produit')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read', 'write'])]
    #[MaxDepth(1)]
    private ?Format $format = null;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
    }
    public function __toString(): string
    {
        return $this->productName ?? 'Unknown Produit'; // Fallback to a default string if name is null
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): static
    {
        $this->productName = $productName;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getUserProduct(): ?user
    {
        return $this->userProduct;
    }

    public function setUserProduct(?user $userProduct): static
    {
        $this->userProduct = $userProduct;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commande->contains($commande)) {
            $this->commande->add($commande);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        $this->commande->removeElement($commande);

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

    public function getFormat(): ?Format
    {
        return $this->format;
    }

    public function setFormat(?Format $format): static
    {
        $this->format = $format;

        return $this;
    }
}
