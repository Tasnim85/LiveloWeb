<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Zone;

#[ORM\Entity]
class Trajet
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $idTrajet;

    #[ORM\Column(type: "string", length: 100)]
    private string $point_depart;

    #[ORM\Column(type: "string", length: 100)]
    private string $point_arrivee;

        #[ORM\ManyToOne(targetEntity: Zone::class, inversedBy: "trajets")]
    #[ORM\JoinColumn(name: 'idZone', referencedColumnName: 'idZone', onDelete: 'CASCADE')]
    private Zone $idZone;

    public function getIdTrajet()
    {
        return $this->idTrajet;
    }

    public function setIdTrajet($value)
    {
        $this->idTrajet = $value;
    }

    public function getPoint_depart()
    {
        return $this->point_depart;
    }

    public function setPoint_depart($value)
    {
        $this->point_depart = $value;
    }

    public function getPoint_arrivee()
    {
        return $this->point_arrivee;
    }

    public function setPoint_arrivee($value)
    {
        $this->point_arrivee = $value;
    }

    public function getIdZone()
    {
        return $this->idZone;
    }

    public function setIdZone($value)
    {
        $this->idZone = $value;
    }
}
