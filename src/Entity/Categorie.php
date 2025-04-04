<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use App\Entity\Article;

#[ORM\Entity]
class Categorie
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    private int $id_categorie;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "categories")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $created_by;

    #[ORM\Column(type: "string", length: 1000)]
    private string $description;

    #[ORM\Column(type: "text")]
    private string $url_image;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom;

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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($value)
    {
        $this->description = $value;
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

    #[ORM\OneToMany(mappedBy: "id_categorie", targetEntity: Article::class)]
    private Collection $articles;

        public function getArticles(): Collection
        {
            return $this->articles;
        }
    
        public function addArticle(Article $article): self
        {
            if (!$this->articles->contains($article)) {
                $this->articles[] = $article;
                $article->setId_categorie($this);
            }
    
            return $this;
        }
    
        public function removeArticle(Article $article): self
        {
            if ($this->articles->removeElement($article)) {
                // set the owning side to null (unless already changed)
                if ($article->getId_categorie() === $this) {
                    $article->setId_categorie(null);
                }
            }
    
            return $this;
        }
}
