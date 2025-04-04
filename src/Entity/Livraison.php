<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Zone;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
class Livraison
{

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "livraisons")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $created_by;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "livraisons")]
    #[ORM\JoinColumn(name: 'id_livreur', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $id_livreur;

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idLivraison;

        #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: "livraisons")]
    #[ORM\JoinColumn(name: 'commandeId', referencedColumnName: 'id_commande', onDelete: 'CASCADE')]
    private Commande $commandeId;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $created_at;

        #[ORM\ManyToOne(targetEntity: Facture::class, inversedBy: "livraisons")]
    #[ORM\JoinColumn(name: 'factureId', referencedColumnName: 'idFacture', onDelete: 'CASCADE')]
    private Facture $factureId;

        #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: "livraisons")]
    #[ORM\JoinColumn(name: 'zoneId', referencedColumnName: 'idZone', onDelete: 'CASCADE')]
    private Zone $zoneId;

    public function getCreated_by()
    {
        return $this->created_by;
    }

    public function setCreated_by($value)
    {
        $this->created_by = $value;
    }

    public function getId_livreur()
    {
        return $this->id_livreur;
    }

    public function setId_livreur($value)
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

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($value)
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

    public function getZoneId()
    {
        return $this->zoneId;
    }

    public function setZoneId($value)
    {
        $this->zoneId = $value;
    }

    #[ORM\OneToMany(mappedBy: "livraisonId", targetEntity: Avis::class)]
    private Collection $aviss;

    #[ORM\OneToMany(mappedBy: "id_livraison", targetEntity: Zone::class)]
    private Collection $zones;
}
