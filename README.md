# Livelo 🚲🌱

**Livelo** est **une application web** de livraison écologique **développée avec Symfony 6.4**. Gestion multi-rôles (Admin, Partenaire, Client, Livreur), statistiques en temps réel, chatbot intégré et optimisation des livraisons urbaines durables.Ce projet a été développé dans le cadre du cours **PIDEV 3A** à **Esprit School of Engineering**. Il explore la gestion de tâches en temps réel en mettant l'accent sur le design responsive et les fonctionnalités collaboratives.
## 🌍 Objectif

L’objectif de Livelo est de :
- Réduire l'empreinte carbone liée aux livraisons urbaines.
- Optimiser la gestion des commandes et des livreurs.
- Proposer une alternative écologique aux services traditionnels.

## 🛠️ Fonctionnalités principales

- Gestion des clients, livreurs et commandes.
- Suivi des livraisons en temps réel.
- Interface d'administration intuitive.
- Notifications de statut de commande.
- Sécurité et gestion des rôles utilisateurs.

---

## 📋 Table des matières

- [Installation](#installation)
- [Utilisation](#utilisation)
- [Contribution](#contribution)
- [Technologies utilisées ](#technologies_utilisées)
- [Licence](#licence)

---

## 🚀 Installation

1. Clonez le projet :
   ```bash
   git clone https://github.com/Tasnim85/LiveloWeb.git
   cd LiveloWeb
2. Installez les dépendances PHP :
   ```bash
   composer install
  3. Configurez votre .env :
       ```bash
      DATABASE_URL="mysql://utilisateur:motdepasse@127.0.0.1:3306/nom_de_la_base"
 4. Créez la base de données et lancez les migrations :
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
5. Lancez le serveur local :
   ```bash
   symfony server:start
## 💻 Utilisation
Livelo propose une expérience différente selon le rôle de l’utilisateur. Voici comment chaque rôle interagit avec l’application :

👩‍💼 Administrateur
- Gère tous les utilisateurs (clients, livreurs, partenaires).
- Crée et modifie les catégories d’articles.
- Supervise l’ensemble des statistiques.
- Dispose d’un tableau de bord complet pour la gestion du système.

🤝 Partenaire
- Ajoute des articles dans les catégories créées par l’administrateur.
- Consulte les statistiques liées à ses propres articles.
- Interagit avec les utilisateurs via le chatbot intelligent intégré aux articles.

🛒 Client
- Parcourt les articles disponibles via la boutique.
- Passe une commande en ligne(paiement en ligne ou cash).
- Suit l’état de ses commandes en temps réel.

🚴 Livreur
- Accède à la liste des commandes en attente.
-Accepte une commande à livrer.
- Met à jour le statut de livraison.
- Suit l’historique de ses livraisons.

## 🤝 Contribution
Nous remercions tous ceux qui ont contribué a ce projet !
   ### Contributeurs
- [Tasnim Benhassine](https://github.com/Tasnim85) -Développeuse du module utilisateur
- [Mohamed Aziz Trabelsi](https://github.com/AzizzTrabelsi) -Développeur du module commande
- [Nouha Saidane](https://github.com/nouhasaidanee) -Développeuse du module partenaire
- [Zied Filali](https://github.com/Ziedfilali) -Développeur du module livraison

Si vous souhaitez contribuer, suivez les étapes ci-dessous pour faire un **fork**, créer une nouvelle branche et soumettre une **pull request**.

  ### Comment contribuer?
1. Forkez le projet.
2. Créez votre branche de fonctionnalité
    ```bash
     git checkout -b nouvelle-fonctionnalité.
4. Commitez vos changements .
    ```bash
     git commit -am 'Ajout d’une fonctionnalité'
6. Poussez votre branche .
    ```bash
     git push origin nouvelle-fonctionnalité
8. Créez une Pull Request.
## 🧰 Technologies utilisées
- **Symfony 6.4** – Framework backend PHP
- **Doctrine ORM** – Gestion de la base de données
- **Twig** – Moteur de templates
- **MySQL** – Base de données relationnelle
- **API REST** – Communication entre les modules
- **HTML/CSS/JavaScript** – Front-end de base
## 📄 Licence
Ce projet est sous licence MIT. Vous êtes libre de l’utiliser, le modifier et le redistribuer tant que vous conservez les mentions de droits d’auteur d’origine.




























