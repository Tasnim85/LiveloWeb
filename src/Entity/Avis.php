<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Entity\Livraison;

#[ORM\Entity]
class Avis
{
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "aviss")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $created_by;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"idAvis",type: "integer")]
    private ?int $idAvis;

    #[ORM\ManyToOne(targetEntity: Livraison::class, inversedBy: "aviss")]
    #[ORM\JoinColumn(name: 'livraisonId', referencedColumnName: 'idLivraison', onDelete: 'CASCADE')]
    private Livraison $livraisonId;

    #[ORM\Column(name:"created_at",type: "date")]
    private \DateTimeInterface $created_at;

    #[ORM\Column(type: "string", length: 100)]
    #[Assert\NotBlank(message: "Description cannot be empty.")]
    #[Assert\Length(
        min: 3,
        minMessage: "The description must contain at least 3 characters.",
    )]
    private string $description;

    // #[ORM\Column(name:"rate",type:"integer", nullable: true)]
    // #[Assert\Range(
    //     min: 1,
    //     max: 5,
    //     notInRangeMessage: "La note doit être comprise entre {{ min }} et {{ max }}."
    // )]
    // private ?int $rate=null;

    // public function getRate(): ?int
    // {
    //     return $this->rate;
    // }

    // public function setRate($value)
    // {
    //     $this->rate = $value;
    // }


    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy($value)
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

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($value)
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
