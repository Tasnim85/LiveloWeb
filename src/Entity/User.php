<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use App\Entity\Livraison;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
class User implements UserInterface, PasswordAuthenticatedUserInterface

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[Groups(['user:read'])]
    #[ORM\Column(name: "idUser", type: "integer")]

    private int $idUser;

    #[ORM\Column(type: "string", length: 100)]
    #[Groups(['user:read'])]
    #[Assert\NotBlank(message: "First name cannot be empty.")]
    #[Assert\Length(min: 3, max: 100, minMessage: "First name must be at least {{ limit }} characters long.", maxMessage: "First name cannot exceed {{ limit }} characters.")]
    private string $nom;

    #[ORM\Column(type: "string", length: 100)]
    #[Groups(['user:read'])]
    #[Assert\NotBlank(message: "Last name cannot be empty.")]
    #[Assert\Length(min: 3, max: 100, minMessage: "Last name must be at least {{ limit }} characters long.", maxMessage: "Last name cannot exceed {{ limit }} characters.")]
    private string $prenom;

    #[ORM\Column(type: "string")]
    #[Groups(['user:read'])]
    #[Assert\NotBlank(message: "Role cannot be empty.")]
    #[Assert\Choice(choices: ['client', 'admin', 'delivery_person', 'partner'], message: "Choose a valid role.")]
    private string $role;

    #[ORM\Column(type: "boolean")]
    #[Groups(['user:read'])]
    private bool $verified;

    #[ORM\Column(type: "string", length: 100)]
    #[Groups(['user:read'])]
    #[Assert\NotBlank(message: "Address cannot be empty.")]
    private string $adresse;

    #[ORM\Column(type: "string", nullable: true)]
    #[Groups(['user:read'])]
    #[Assert\Choice(choices: ['e_bike', 'Bike','e_scooter', null])]
    private ?string $type_vehicule = null;


    #[ORM\Column(type: "string", length: 100)]
    #[Groups(['user:read'])]
    #[Assert\NotBlank(message: "Email cannot be empty.")]
    #[Assert\Email(message: "The email '{{ value }}' is not valid.")]
    private string $email;

    #[ORM\Column(type: "string", length: 300, nullable: true)]
    #[Groups(['user:read'])]
    #[Assert\NotBlank(message: "Password cannot be empty.")]
    #[Assert\Length(min: 8, minMessage: "Password must be at least {{ limit }} characters long.")]
    private ?string $password = null;

    #[ORM\Column(type: "string", length: 8)]
    #[Groups(['user:read'])]
    private string $num_tel;

    #[ORM\Column(type: "string", length: 8)]
    #[Groups(['user:read'])]
    #[Assert\NotBlank(message: "Le CIN ne peut pas être vide.")]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: "Le CIN doit comporter exactement 8 caractères."
    )]
    private string $cin;

    #[ORM\Column(type: "string", length: 500)]
    #[Groups(['user:read'])]
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

    public function getPassword(): string
{
    return $this->password;
}

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function getNumTel()
    {
        return $this->num_tel;
    }

    public function setNumTel($value)
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

    public function getUrlImage()
    {
        return $this->url_image;
    }

    public function setUrlImage($value)
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

        //edhouma teb3in l'interface mta3 hachage
        public function getRoles(): array
        {
            return [$this->role];
        }

        public function getUserIdentifier(): string
        {
            return $this->cin; // ou le champ que tu utilises comme identifiant
        }

        // Nécessaire pour UserInterface
        public function eraseCredentials()
        {
            // Si tu stockes des données temporaires sensibles, nettoie-les ici
        }

}
