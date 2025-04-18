<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Commande;
use Doctrine\Common\Collections\Collection;
use App\Entity\Livraison;

#[ORM\Entity]
class Facture
{

    #[ORM\Id]
#[ORM\GeneratedValue]
#[ORM\Column(type: "integer")]
private int $idFacture;


    #[ORM\Column(type: "float")]
    private float $montant;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $date;

    #[ORM\Column(type: "string")]
    private string $type_payement;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "factures")]
    #[ORM\JoinColumn(name: 'userId', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $userId;

        #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: "factures")]
    #[ORM\JoinColumn(name: 'commandeId', referencedColumnName: 'id_commande', onDelete: 'CASCADE')]
    private Commande $commandeId;

    public function getIdFacture()
    {
        return $this->idFacture;
    }

    public function setIdFacture($value)
    {
        $this->idFacture = $value;
    }

    public function getMontant()
    {
        return $this->montant;
    }

    public function setMontant($value)
    {
        $this->montant = $value;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($value)
    {
        $this->date = $value;
    }

    public function getType_payement()
    {
        return $this->type_payement;
    }

    public function setType_payement($value)
    {
        $this->type_payement = $value;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($value)
    {
        $this->userId = $value;
    }

    public function getCommandeId()
    {
        return $this->commandeId;
    }

    public function setCommandeId($value)
    {
        $this->commandeId = $value;
    }

    #[ORM\OneToMany(mappedBy: "factureId", targetEntity: Livraison::class)]
    private Collection $livraisons;

        public function getLivraisons(): Collection
        {
            return $this->livraisons;
        }
    
        public function addLivraison(Livraison $livraison): self
        {
            if (!$this->livraisons->contains($livraison)) {
                $this->livraisons[] = $livraison;
                $livraison->setFactureId($this);
            }
    
            return $this;
        }
    
        public function removeLivraison(Livraison $livraison): self
        {
            if ($this->livraisons->removeElement($livraison)) {
                // set the owning side to null (unless already changed)
                if ($livraison->getFactureId() === $this) {
                    $livraison->setFactureId(null);
                }
            }
    
            return $this;
        }
}
