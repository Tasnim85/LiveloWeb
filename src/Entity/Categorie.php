<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use App\Entity\Article;

#[ORM\Entity]
class Categorie
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_categorie", type: "integer")]
    private int $id_categorie;

        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "categories")]
    #[ORM\JoinColumn(name: 'created_by', referencedColumnName: 'idUser', onDelete: 'CASCADE')]
    private User $created_by ;

    #[ORM\Column(type: "string", length: 1000)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
#[Assert\Length(
    max: 100,
    maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
)]
    private string $description;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: "L'image est obligatoire.")]
#[Assert\Image(
    maxSize: "2M",
    mimeTypes: ["image/jpeg", "image/png", "image/webp"],
    mimeTypesMessage: "Seuls les formats JPEG, PNG et WebP sont acceptés.",
    maxSizeMessage: "L'image ne doit pas dépasser {{ limit }}."
)]
    private ?string $url_image = null;


    #[ORM\Column(type: "string", length: 100)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
#[Assert\Length(
    max: 20,
    maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
)]
#[Assert\Regex(
    pattern: "/^[a-zA-Z]/",
    match: false,
    message: "Le nom ne doit pas commencer par une lettre."
)]
#[Assert\Regex(
    pattern: "/^[a-zA-Z0-9\s\-_]+$/",
    message: "Le nom contient des caractères non autorisés."
)]
    private string $nom;

    public function getIdCategorie(): int
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

    public function getUrl_image(): ?string
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
