<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Entity\User;
use App\Entity\Livraison;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;
#[ORM\Entity]
class Commande
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idCommande;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "commandes")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $createdBy;
    
    #[Assert\NotBlank(message: "First adress is necessary !")]
    #[Assert\Regex(
    pattern: '/^[a-zA-Z0-9\s]+$/',
    message: "Only alphabetic characters,spaces or numbers First adress can have !."
    )]
    #[ORM\Column(type: "string", length: 50)]
    private string $adresseDep;

    #[Assert\NotBlank(message: "Second adress is necessary !")]
    #[Assert\Regex(
    pattern: "/^[a-zA-Z0-9\s]+$/",
    message: "Only alphabetic characters,spaces or numbers Second adress can have !."
    )]
    #[ORM\Column(type: "string", length: 50)]
    private string $adresseArr;
    
    #[Assert\Choice(
        choices: ['standard', 'express'],
        message: "Delivery type should be 'standard' OR 'express'."
    )]   
    #[ORM\Column(type: "string", length: 20)]
    private string $typeLivraison;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $horaire;
    
    #[Assert\Choice(
        choices: ['shipping', 'processing', 'delivered'],
        message: "Statut should be 'shipping', 'processing' or'delivered'."
    )]    
    #[ORM\Column(type: "string")]
    private string $statut;

    #[ORM\OneToMany(mappedBy: "idCommande", targetEntity: Articlecommande::class)]
    private Collection $articlecommandes;

    #[ORM\OneToMany(mappedBy: "commandeId", targetEntity: Facture::class)]
    private Collection $factures;

    #[ORM\OneToMany(mappedBy: "commandeId", targetEntity: Livraison::class)]
    private Collection $livraisons;

    // Getters / Setters
    public function getIdCommande(): int
    {
        return $this->idCommande;
    }

    public function setIdCommande(int $idCommande): void
    {
        $this->idCommande = $idCommande;
    }

    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getAdresseDep(): string
    {
        return $this->adresseDep;
    }

    public function setAdresseDep(string $adresseDep): void
    {
        $this->adresseDep = $adresseDep;
    }

    public function getAdresseArr(): string
    {
        return $this->adresseArr;
    }

    public function setAdresseArr(string $adresseArr): void
    {
        $this->adresseArr = $adresseArr;
    }

    public function getTypeLivraison(): string
    {
        return $this->typeLivraison;
    }

    public function setTypeLivraison(string $typeLivraison): void
    {
        $this->typeLivraison = $typeLivraison;
    }

    public function getHoraire(): \DateTimeInterface
    {
        return $this->horaire;
    }

    public function setHoraire(\DateTimeInterface $horaire): void
    {
        $this->horaire = $horaire;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function getArticlecommandes(): Collection
    {
        return $this->articlecommandes;
    }

    public function addArticlecommande(Articlecommande $articlecommande): self
    {
        if (!$this->articlecommandes->contains($articlecommande)) {
            $this->articlecommandes[] = $articlecommande;
            $articlecommande->setIdCommande($this);
        }

        return $this;
    }

    public function removeArticlecommande(Articlecommande $articlecommande): self
    {
        if ($this->articlecommandes->removeElement($articlecommande)) {
            if ($articlecommande->getIdCommande() === $this) {
                $articlecommande->setIdCommande(null);
            }
        }

        return $this;
    }

    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setCommandeId($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            if ($facture->getCommandeId() === $this) {
                $facture->setCommandeId(null);
            }
        }

        return $this;
    }

    public function getLivraisons(): Collection
    {
        return $this->livraisons;
    }
}
