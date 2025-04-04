<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Livraison;

#[ORM\Entity]
class Avis
{

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "aviss")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $created_by;

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idAvis;

        #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: "aviss")]
    #[ORM\JoinColumn(name: 'livraisonId', referencedColumnName: 'idLivraison', onDelete: 'CASCADE')]
    private Livraison $livraisonId;

    #[ORM\Column(type: "date")]
    private \DateTimeInterface $created_at;

    #[ORM\Column(type: "string", length: 100)]
    private string $description;

    public function getCreated_by()
    {
        return $this->created_by;
    }

    public function setCreated_by($value)
    {
        $this->created_by = $value;
    }

    public function getIdAvis()
    {
        return $this->idAvis;
    }

    public function setIdAvis($value)
    {
        $this->idAvis = $value;
    }

    public function getLivraisonId()
    {
        return $this->livraisonId;
    }

    public function setLivraisonId($value)
    {
        $this->livraisonId = $value;
    }

    public function getCreated_at()
    {
        return $this->created_at;
    }

    public function setCreated_at($value)
    {
        $this->created_at = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }
}
