<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Livraison;
use Doctrine\Common\Collections\Collection;
use App\Entity\Trajet;

#[ORM\Entity]
class Zone
{

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "zones")]
    #[ORM\JoinColumn(name: 'id_user', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $id_user;

    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: "zones")]
    #[ORM\JoinColumn(name: 'id_livraison', referencedColumnName: 'idLivraison', onDelete: 'CASCADE')]
    private Livraison $id_livraison;

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idZone;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom;

    #[ORM\Column(type: "float")]
    private float $latitude_centre;

    #[ORM\Column(type: "float")]
    private float $longitude_centre;

    #[ORM\Column(type: "float")]
    private float $rayon;

    #[ORM\Column(type: "integer")]
    private int $max;

    public function getId_user()
    {
        return $this->id_user;
    }

    public function setId_user($value)
    {
        $this->id_user = $value;
    }

    public function getId_livraison()
    {
        return $this->id_livraison;
    }

    public function setId_livraison($value)
    {
        $this->id_livraison = $value;
    }

    public function getIdZone()
    {
        return $this->idZone;
    }

    public function setIdZone($value)
    {
        $this->idZone = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getLatitude_centre()
    {
        return $this->latitude_centre;
    }

    public function setLatitude_centre($value)
    {
        $this->latitude_centre = $value;
    }

    public function getLongitude_centre()
    {
        return $this->longitude_centre;
    }

    public function setLongitude_centre($value)
    {
        $this->longitude_centre = $value;
    }

    public function getRayon()
    {
        return $this->rayon;
    }

    public function setRayon($value)
    {
        $this->rayon = $value;
    }

    public function getMax()
    {
        return $this->max;
    }

    public function setMax($value)
    {
        $this->max = $value;
    }

    #[ORM\OneToMany(mappedBy: "idZone", targetEntity: Trajet::class)]
    private Collection $trajets;

    #[ORM\OneToMany(mappedBy: "zoneId", targetEntity: Livraison::class)]
    private Collection $livraisons;

        public function getLivraisons(): Collection
        {
            return $this->livraisons;
        }
    
        public function addLivraison(Livraison $livraison): self
        {
            if (!$this->livraisons->contains($livraison)) {
                $this->livraisons[] = $livraison;
                $livraison->setZoneId($this);
            }
    
            return $this;
        }
    
        public function removeLivraison(Livraison $livraison): self
        {
            if ($this->livraisons->removeElement($livraison)) {
                // set the owning side to null (unless already changed)
                if ($livraison->getZoneId() === $this) {
                    $livraison->setZoneId(null);
                }
            }
    
            return $this;
        }
}
