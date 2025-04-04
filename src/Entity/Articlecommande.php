<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\Commande;

#[ORM\Entity]
class Articlecommande
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_article_commande;

        #[ORM\ManyToOne(targetEntity: Article::class, inversedBy: "articlecommandes")]
    #[ORM\JoinColumn(name: 'idArticle', referencedColumnName: 'id_article', onDelete: 'CASCADE')]
    private Article $idArticle;

        #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: "articlecommandes")]
    #[ORM\JoinColumn(name: 'idCommande', referencedColumnName: 'id_commande', onDelete: 'CASCADE')]
    private Commande $idCommande;

    #[ORM\Column(type: "integer")]
    private int $quantity;

    public function getId_article_commande()
    {
        return $this->id_article_commande;
    }

    public function setId_article_commande($value)
    {
        $this->id_article_commande = $value;
    }

    public function getIdArticle()
    {
        return $this->idArticle;
    }

    public function setIdArticle($value)
    {
        $this->idArticle = $value;
    }

    public function getIdCommande()
    {
        return $this->idCommande;
    }

    public function setIdCommande($value)
    {
        $this->idCommande = $value;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($value)
    {
        $this->quantity = $value;
    }
}
