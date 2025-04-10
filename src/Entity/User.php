<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use App\Entity\Livraison;

#[ORM\Entity]
class User
{

    #[ORM\Id]
    #[ORM\GeneratedValue] // Add this for auto-increment
    #[ORM\Column(name: "idUser", type: "integer")] // Explicitly map to "idUser" column
    private int $idUser;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom;

    #[ORM\Column(type: "string", length: 100)]
    private string $prenom;

    #[ORM\Column(type: "string")]
    private string $role;

    #[ORM\Column(type: "boolean")]
    private bool $verified;

    #[ORM\Column(type: "string", length: 100)]
    private string $adresse;

    #[ORM\Column(type: "string")]
    private string $type_vehicule;

    #[ORM\Column(type: "string", length: 100)]
    private string $email;

    #[ORM\Column(type: "string", length: 300)]
    private string $password;

    #[ORM\Column(type: "string", length: 8)]
    private string $num_tel;

    #[ORM\Column(type: "string", length: 8)]
    private string $cin;

    #[ORM\Column(type: "string", length: 500)]
    private string $url_image;

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser($value)
    {
        $this->idUser = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($value)
    {
        $this->prenom = $value;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($value)
    {
        $this->role = $value;
    }

    public function getVerified()
    {
        return $this->verified;
    }

    public function setVerified($value)
    {
        $this->verified = $value;
    }

    public function getAdresse()
    {
        return $this->adresse;
    }

    public function setAdresse($value)
    {
        $this->adresse = $value;
    }

    public function getType_vehicule()
    {
        return $this->type_vehicule;
    }

    public function setType_vehicule($value)
    {
        $this->type_vehicule = $value;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function getNum_tel()
    {
        return $this->num_tel;
    }

    public function setNum_tel($value)
    {
        $this->num_tel = $value;
    }

    public function getCin()
    {
        return $this->cin;
    }

    public function setCin($value)
    {
        $this->cin = $value;
    }

    public function getUrl_image()
    {
        return $this->url_image;
    }

    public function setUrl_image($value)
    {
        $this->url_image = $value;
    }

    #[ORM\OneToMany(mappedBy: "created_by", targetEntity: Categorie::class)]
    private Collection $categories;

        public function getCategories(): Collection
        {
            return $this->categories;
        }
    
        public function addCategorie(Categorie $categorie): self
        {
            if (!$this->categories->contains($categorie)) {
                $this->categories[] = $categorie;
                $categorie->setCreated_by($this);
            }
    
            return $this;
        }
    
        public function removeCategorie(Categorie $categorie): self
        {
            if ($this->categories->removeElement($categorie)) {
                // set the owning side to null (unless already changed)
                if ($categorie->getCreated_by() === $this) {
                    $categorie->setCreated_by(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "created_by", targetEntity: Commande::class)]
    private Collection $commandes;

    #[ORM\OneToMany(mappedBy: "created_by", targetEntity: Article::class)]
    private Collection $articles;

    #[ORM\OneToMany(mappedBy: "created_by", targetEntity: Avis::class)]
    private Collection $aviss;

    #[ORM\OneToMany(mappedBy: "userId", targetEntity: Facture::class)]
    private Collection $factures;

        public function getFactures(): Collection
        {
            return $this->factures;
        }
    
        public function addFacture(Facture $facture): self
        {
            if (!$this->factures->contains($facture)) {
                $this->factures[] = $facture;
                $facture->setUserId($this);
            }
    
            return $this;
        }
    
        public function removeFacture(Facture $facture): self
        {
            if ($this->factures->removeElement($facture)) {
                // set the owning side to null (unless already changed)
                if ($facture->getUserId() === $this) {
                    $facture->setUserId(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "id_user", targetEntity: Zone::class)]
    private Collection $zones;

        public function getZones(): Collection
        {
            return $this->zones;
        }
    
        public function addZone(Zone $zone): self
        {
            if (!$this->zones->contains($zone)) {
                $this->zones[] = $zone;
                $zone->setId_user($this);
            }
    
            return $this;
        }
    
        public function removeZone(Zone $zone): self
        {
            if ($this->zones->removeElement($zone)) {
                // set the owning side to null (unless already changed)
                if ($zone->getId_user() === $this) {
                    $zone->setId_user(null);
                }
            }
    
            return $this;
        }

    #[ORM\OneToMany(mappedBy: "created_by", targetEntity: Livraison::class)]
    private Collection $livraisonsNon;

    #[ORM\OneToMany(mappedBy: "id_livreur", targetEntity: Livraison::class)]
    private Collection $livraisons;

        public function getLivraisons(): Collection
        {
            return $this->livraisons;
        }
    
        public function addLivraison(Livraison $livraison): self
        {
            if (!$this->livraisons->contains($livraison)) {
                $this->livraisons[] = $livraison;
                $livraison->setId_livreur($this);
            }
    
            return $this;
        }
    
        public function removeLivraison(Livraison $livraison): self
        {
            if ($this->livraisons->removeElement($livraison)) {
                // set the owning side to null (unless already changed)
                if ($livraison->getId_livreur() === $this) {
                    $livraison->setId_livreur(null);
                }
            }
    
            return $this;
        }
}
