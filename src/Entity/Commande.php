<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use App\Entity\Livraison;

#[ORM\Entity]
class Commande
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_commande;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "commandes")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $created_by;

    #[ORM\Column(type: "string", length: 50)]
    private string $adresse_dep;

    #[ORM\Column(type: "string", length: 50)]
    private string $adresse_arr;

    #[ORM\Column(type: "string", length: 20)]
    private string $type_livraison;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $horaire;

    #[ORM\Column(type: "string")]
    private string $statut;

    public function getIdCommande()
    {
        return $this->id_commande;
    }

    public function setIdCommande($value)
    {
        $this->id_commande = $value;
    }

    public function getCreated_by()
    {
        return $this->created_by;
    }

    public function setCreated_by($value)
    {
        $this->created_by = $value;
    }

    public function getAdresse_dep()
    {
        return $this->adresse_dep;
    }

    public function setAdresse_dep($value)
    {
        $this->adresse_dep = $value;
    }

    public function getAdresse_arr()
    {
        return $this->adresse_arr;
    }

    public function setAdresse_arr($value)
    {
        $this->adresse_arr = $value;
    }

    public function getType_livraison()
    {
        return $this->type_livraison;
    }

    public function setType_livraison($value)
    {
        $this->type_livraison = $value;
    }

    public function getHoraire()
    {
        return $this->horaire;
    }

    public function setHoraire($value)
    {
        $this->horaire = $value;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($value)
    {
        $this->statut = $value;
    }

    #[ORM\OneToMany(mappedBy: "idCommande", targetEntity: Articlecommande::class)]
    private Collection $articlecommandes;

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
                // set the owning side to null (unless already changed)
                if ($articlecommande->getIdCommande() === $this) {
                    $articlecommande->setIdCommande(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "commandeId", targetEntity: Facture::class)]
    private Collection $factures;

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
                // set the owning side to null (unless already changed)
                if ($facture->getCommandeId() === $this) {
                    $facture->setCommandeId(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "commandeId", targetEntity: Livraison::class)]
    private Collection $livraisons;
}
