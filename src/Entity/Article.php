<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use App\Entity\Articlecommande;

#[ORM\Entity]
class Article
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_article", type: "integer")]
    private int $id_article;
 

        #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: "articles")]
    #[ORM\JoinColumn(name: 'id_categorie', referencedColumnName: 'id_categorie', onDelete: 'CASCADE')]
    private Categorie $id_categorie;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "articles")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $created_by;

    #[ORM\Column(type: "string", length: 10000)]
    private string $url_image;

    #[ORM\Column(type: "string", length: 1000)]
    private string $nom;

    #[ORM\Column(type: "float")]
    private float $prix;

    #[ORM\Column(type: "string", length: 100)]
    private string $description;

    #[ORM\Column(type: "integer")]
    private int $quantite;

    #[ORM\Column(type: "string")]
    private string $statut;
    
    #[ORM\Column(type: "date", name: "createdAt")]
private \DateTimeInterface $createdAt;

    

#[ORM\Column(name: "nbViews", type: "integer", options: ["default" => 0])]
private int $nbViews = 0;

    public function getId_article()
    {
        return $this->id_article;
    }

    public function setId_article($value)
    {
        $this->id_article = $value;
    }

    public function getId_categorie()
    {
        return $this->id_categorie;
    }

    public function setId_categorie($value)
    {
        $this->id_categorie = $value;
    }

    public function getCreated_by()
    {
        return $this->created_by;
    }

    public function setCreated_by($value)
    {
        $this->created_by = $value;
    }

    public function getUrl_image()
    {
        return $this->url_image;
    }

    public function setUrl_image($value)
    {
        $this->url_image = $value;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($value)
    {
        $this->nom = $value;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($value)
    {
        $this->prix = $value;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function setQuantite($value)
    {
        $this->quantite = $value;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function setStatut($value)
    {
        $this->statut = $value;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($value)
    {
        $this->createdAt = $value;
    }
    public function getNbViews()
    {
        return $this->nbViews;
    }

    public function setNbViews($value)
    {
        $this->nbViews = $value;
    }

    #[ORM\OneToMany(mappedBy: "idArticle", targetEntity: Articlecommande::class)]
    private Collection $articlecommandes;

        public function getArticlecommandes(): Collection
        {
            return $this->articlecommandes;
        }
    
        public function addArticlecommande(Articlecommande $articlecommande): self
        {
            if (!$this->articlecommandes->contains($articlecommande)) {
                $this->articlecommandes[] = $articlecommande;
                $articlecommande->setIdArticle($this);
            }
    
            return $this;
        }
    
        public function removeArticlecommande(Articlecommande $articlecommande): self
        {
            if ($this->articlecommandes->removeElement($articlecommande)) {
                // set the owning side to null (unless already changed)
                if ($articlecommande->getIdArticle() === $this) {
                    $articlecommande->setIdArticle(null);
                }
            }
    
            return $this;
        }
}
