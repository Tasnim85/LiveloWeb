<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Zone;
use App\Entity\Commande;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "idLivraison", type: "integer")]
    private ?int $idLivraison = null;

    public function getId(): ?int
    {
        return $this->idLivraison;
    }


    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "livraisons")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $created_by;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "livraisons")]
    #[ORM\JoinColumn(name: 'id_livreur', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $id_livreur;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: "livraisons")]
    #[ORM\JoinColumn(name: 'commandeId', referencedColumnName: 'id_commande', onDelete: 'CASCADE')]
    private Commande $commandeId;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $created_at;

    #[ORM\ManyToOne(targetEntity: Facture::class, inversedBy: "livraisons")]
    #[ORM\JoinColumn(name: 'factureId', referencedColumnName: 'idFacture', onDelete: 'CASCADE')]
    private Facture $factureId;

    // #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: "livraisons")]
    // #[ORM\JoinColumn(name: 'zoneId', referencedColumnName: 'idZone', onDelete: 'CASCADE')]
    // private Zone $zoneId;

    #[ORM\Column(name: "zoneId", type: "integer", nullable: true)]
private ?int $zoneId = null;
  // Default value of 5

    #[ORM\OneToMany(mappedBy: "livraisonId", targetEntity: Avis::class)]
    private Collection $aviss;

    public function getIdZone(): ?int
    {
        return $this->zoneId;
    }

    public function setIdZone(?int $idZone): self
    {
        $this->zoneId = $idZone;
        return $this;
    }

    public function getCreatedBy()
    {
        return $this->created_by;
    }

    public function setCreatedBy($value)
    {
        $this->created_by = $value;
    }

    public function getIdLivreur()
    {
        return $this->id_livreur;
    }

    public function setIdLivreur($value)
    {
        $this->id_livreur = $value;
    }

    public function getIdLivraison()
    {
        return $this->idLivraison;
    }

    public function setIdLivraison($value)
    {
        $this->idLivraison = $value;
    }

    public function getCommandeId()
    {
        return $this->commandeId;
    }

    public function setCommandeId($value)
    {
        $this->commandeId = $value;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $value)
    {
        $this->created_at = $value;
    }

    public function getFactureId()
    {
        return $this->factureId;
    }

    public function setFactureId($value)
    {
        $this->factureId = $value;
    }

    public function getZoneId(): int
    {
        return $this->zoneId;
    }

    public function setZoneId(int $zoneId): self
    {
        $this->zoneId = $zoneId;
        return $this;
    }
}
