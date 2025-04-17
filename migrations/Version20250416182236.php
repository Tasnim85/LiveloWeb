<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416182236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE articlecommande DROP FOREIGN KEY FK_F77003B712836594
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE articlecommande DROP FOREIGN KEY FK_F77003B73D498C26
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_articleee ON articlecommande
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F77003B712836594 ON articlecommande (idArticle)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_commandeeeee ON articlecommande
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F77003B73D498C26 ON articlecommande (idCommande)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE articlecommande ADD CONSTRAINT FK_F77003B712836594 FOREIGN KEY (idArticle) REFERENCES article (id_article) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE articlecommande ADD CONSTRAINT FK_F77003B73D498C26 FOREIGN KEY (idCommande) REFERENCES commande (id_commande) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis MODIFY idAvis INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY fl_livraison
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY fk_userrrrrrr
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON avis
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY fl_livraison
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY fk_userrrrrrr
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD id_avis INT NOT NULL, DROP idAvis, CHANGE created_by created_by INT DEFAULT NULL, CHANGE livraisonId livraisonId INT DEFAULT NULL, CHANGE created_at created_at DATE NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0DE12AB56 FOREIGN KEY (created_by) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0875765B5 FOREIGN KEY (livraisonId) REFERENCES livraison (idLivraison) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD PRIMARY KEY (id_avis)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_userrrrrrr ON avis
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F91ABF0DE12AB56 ON avis (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fl_livraison ON avis
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8F91ABF0875765B5 ON avis (livraisonId)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT fl_livraison FOREIGN KEY (livraisonId) REFERENCES livraison (idLivraison) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT fk_userrrrrrr FOREIGN KEY (created_by) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie DROP FOREIGN KEY fk_userr
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie DROP FOREIGN KEY fk_userr
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie CHANGE id_categorie id_categorie INT NOT NULL, CHANGE created_by created_by INT DEFAULT NULL, CHANGE url_image url_image LONGTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie ADD CONSTRAINT FK_497DD634DE12AB56 FOREIGN KEY (created_by) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_userr ON categorie
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_497DD634DE12AB56 ON categorie (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie ADD CONSTRAINT fk_userr FOREIGN KEY (created_by) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP FOREIGN KEY fk_userrr
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP FOREIGN KEY fk_userrr
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande CHANGE id_commande id_commande INT NOT NULL, CHANGE created_by created_by INT DEFAULT NULL, CHANGE horaire horaire DATETIME NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DDE12AB56 FOREIGN KEY (created_by) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_userrr ON commande
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6EEAA67DDE12AB56 ON commande (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT fk_userrr FOREIGN KEY (created_by) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture MODIFY idFacture INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY fk_commande
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY fk_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON facture
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY fk_commande
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY fk_user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD id_facture INT NOT NULL, DROP idFacture, CHANGE userId userId INT DEFAULT NULL, CHANGE commandeId commandeId INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT FK_FE86641064B64DCC FOREIGN KEY (userId) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT FK_FE8664108F992C7E FOREIGN KEY (commandeId) REFERENCES commande (id_commande) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD PRIMARY KEY (id_facture)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_user ON facture
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FE86641064B64DCC ON facture (userId)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_commande ON facture
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_FE8664108F992C7E ON facture (commandeId)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT fk_commande FOREIGN KEY (commandeId) REFERENCES commande (id_commande) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT fk_user FOREIGN KEY (userId) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison MODIFY idLivraison INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY fk_facture
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY fk_commandeee
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY fk_userrrr
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY fk_facture
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY fk_livreur
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY fk_commandeee
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY fk_userrrr
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD zone_id INT NOT NULL, DROP zoneId, CHANGE created_by created_by INT DEFAULT NULL, CHANGE commandeId commandeId INT DEFAULT NULL, CHANGE created_at created_at DATE NOT NULL, CHANGE factureId factureId INT DEFAULT NULL, CHANGE idLivraison id_livraison INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FDE12AB56 FOREIGN KEY (created_by) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F8F992C7E FOREIGN KEY (commandeId) REFERENCES commande (id_commande) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F2AA00A64 FOREIGN KEY (factureId) REFERENCES facture (idFacture) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD PRIMARY KEY (id_livraison)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_userrrr ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A60C9F1FDE12AB56 ON livraison (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_livreur ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A60C9F1F35E7E71D ON livraison (id_livreur)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_commandeee ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A60C9F1F8F992C7E ON livraison (commandeId)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_facture ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A60C9F1F2AA00A64 ON livraison (factureId)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT fk_facture FOREIGN KEY (factureId) REFERENCES facture (idFacture) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT fk_livreur FOREIGN KEY (id_livreur) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT fk_commandeee FOREIGN KEY (commandeId) REFERENCES commande (id_commande) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT fk_userrrr FOREIGN KEY (created_by) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet DROP FOREIGN KEY fk_zoneeeee
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet DROP FOREIGN KEY fk_zoneeeee
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet CHANGE idZone idZone INT DEFAULT NULL, CHANGE idTrajet id_trajet INT NOT NULL, ADD PRIMARY KEY (id_trajet)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CD3169E99 FOREIGN KEY (idZone) REFERENCES zone (idZone) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fk_zoneeeee ON trajet
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2B5BA98CD3169E99 ON trajet (idZone)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet ADD CONSTRAINT fk_zoneeeee FOREIGN KEY (idZone) REFERENCES zone (idZone) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX cin ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD url_image VARCHAR(500) NOT NULL, CHANGE type_vehicule type_vehicule VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone MODIFY idZone INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY fkLivraison
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY fkUser
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON zone
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY fkLivraison
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY fkUser
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD id_zone INT NOT NULL, DROP idZone, CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_livraison id_livraison INT DEFAULT NULL, CHANGE latitude_centre latitude_centre DOUBLE PRECISION NOT NULL, CHANGE longitude_centre longitude_centre DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT FK_A0EBC0076B3CA4B FOREIGN KEY (id_user) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT FK_A0EBC00726392338 FOREIGN KEY (id_livraison) REFERENCES livraison (idLivraison) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD PRIMARY KEY (id_zone)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fkuser ON zone
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A0EBC0076B3CA4B ON zone (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX fklivraison ON zone
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A0EBC00726392338 ON zone (id_livraison)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT fkLivraison FOREIGN KEY (id_livraison) REFERENCES livraison (idLivraison) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT fkUser FOREIGN KEY (id_user) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE articlecommande DROP FOREIGN KEY FK_F77003B712836594
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE articlecommande DROP FOREIGN KEY FK_F77003B73D498C26
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f77003b712836594 ON articlecommande
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_articleee ON articlecommande (idArticle)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_f77003b73d498c26 ON articlecommande
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_commandeeeee ON articlecommande (idCommande)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE articlecommande ADD CONSTRAINT FK_F77003B712836594 FOREIGN KEY (idArticle) REFERENCES article (id_article) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE articlecommande ADD CONSTRAINT FK_F77003B73D498C26 FOREIGN KEY (idCommande) REFERENCES commande (id_commande) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0875765B5
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON avis
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0875765B5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD idAvis INT AUTO_INCREMENT NOT NULL, DROP id_avis, CHANGE created_by created_by INT NOT NULL, CHANGE created_at created_at DATE DEFAULT 'CURRENT_TIMESTAMP' NOT NULL, CHANGE livraisonId livraisonId INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT fl_livraison FOREIGN KEY (livraisonId) REFERENCES livraison (idLivraison) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT fk_userrrrrrr FOREIGN KEY (created_by) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD PRIMARY KEY (idAvis)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_8f91abf0de12ab56 ON avis
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_userrrrrrr ON avis (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_8f91abf0875765b5 ON avis
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fl_livraison ON avis (livraisonId)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0DE12AB56 FOREIGN KEY (created_by) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0875765B5 FOREIGN KEY (livraisonId) REFERENCES livraison (idLivraison) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie CHANGE id_categorie id_categorie INT AUTO_INCREMENT NOT NULL, CHANGE created_by created_by INT NOT NULL, CHANGE url_image url_image MEDIUMTEXT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie ADD CONSTRAINT fk_userr FOREIGN KEY (created_by) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_497dd634de12ab56 ON categorie
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_userr ON categorie (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie ADD CONSTRAINT FK_497DD634DE12AB56 FOREIGN KEY (created_by) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DDE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DDE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande CHANGE id_commande id_commande INT AUTO_INCREMENT NOT NULL, CHANGE created_by created_by INT NOT NULL, CHANGE horaire horaire DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT fk_userrr FOREIGN KEY (created_by) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_6eeaa67dde12ab56 ON commande
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_userrr ON commande (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DDE12AB56 FOREIGN KEY (created_by) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY FK_FE86641064B64DCC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY FK_FE8664108F992C7E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON facture
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY FK_FE86641064B64DCC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY FK_FE8664108F992C7E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD idFacture INT AUTO_INCREMENT NOT NULL, DROP id_facture, CHANGE userId userId INT NOT NULL, CHANGE commandeId commandeId INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT fk_commande FOREIGN KEY (commandeId) REFERENCES commande (id_commande) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT fk_user FOREIGN KEY (userId) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD PRIMARY KEY (idFacture)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_fe86641064b64dcc ON facture
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_user ON facture (userId)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_fe8664108f992c7e ON facture
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_commande ON facture (commandeId)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT FK_FE86641064B64DCC FOREIGN KEY (userId) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT FK_FE8664108F992C7E FOREIGN KEY (commandeId) REFERENCES commande (id_commande) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison MODIFY id_livraison INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FDE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F8F992C7E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F2AA00A64
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FDE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F35E7E71D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F8F992C7E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F2AA00A64
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD zoneId INT DEFAULT NULL, DROP zone_id, CHANGE created_by created_by INT NOT NULL, CHANGE created_at created_at DATE DEFAULT 'CURRENT_TIMESTAMP' NOT NULL, CHANGE commandeId commandeId INT NOT NULL, CHANGE factureId factureId INT NOT NULL, CHANGE id_livraison idLivraison INT AUTO_INCREMENT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT fk_facture FOREIGN KEY (factureId) REFERENCES facture (idFacture) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT fk_commandeee FOREIGN KEY (commandeId) REFERENCES commande (id_commande) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT fk_userrrr FOREIGN KEY (created_by) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD PRIMARY KEY (idLivraison)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_a60c9f1f8f992c7e ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_commandeee ON livraison (commandeId)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_a60c9f1f2aa00a64 ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_facture ON livraison (factureId)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_a60c9f1f35e7e71d ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_livreur ON livraison (id_livreur)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_a60c9f1fde12ab56 ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_userrrr ON livraison (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FDE12AB56 FOREIGN KEY (created_by) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F35E7E71D FOREIGN KEY (id_livreur) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F8F992C7E FOREIGN KEY (commandeId) REFERENCES commande (id_commande) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F2AA00A64 FOREIGN KEY (factureId) REFERENCES facture (idFacture) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CD3169E99
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `primary` ON trajet
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CD3169E99
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet CHANGE idZone idZone INT NOT NULL, CHANGE id_trajet idTrajet INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet ADD CONSTRAINT fk_zoneeeee FOREIGN KEY (idZone) REFERENCES zone (idZone) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_2b5ba98cd3169e99 ON trajet
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fk_zoneeeee ON trajet (idZone)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CD3169E99 FOREIGN KEY (idZone) REFERENCES zone (idZone) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP url_image, CHANGE type_vehicule type_vehicule VARCHAR(255) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX cin ON user (cin)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC0076B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC00726392338
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX `PRIMARY` ON zone
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC0076B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC00726392338
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD idZone INT AUTO_INCREMENT NOT NULL, DROP id_zone, CHANGE id_user id_user INT NOT NULL, CHANGE id_livraison id_livraison INT NOT NULL, CHANGE latitude_centre latitude_centre NUMERIC(10, 0) NOT NULL, CHANGE longitude_centre longitude_centre NUMERIC(10, 0) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT fkLivraison FOREIGN KEY (id_livraison) REFERENCES livraison (idLivraison) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT fkUser FOREIGN KEY (id_user) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD PRIMARY KEY (idZone)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_a0ebc0076b3ca4b ON zone
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fkUser ON zone (id_user)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_a0ebc00726392338 ON zone
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX fkLivraison ON zone (id_livraison)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT FK_A0EBC0076B3CA4B FOREIGN KEY (id_user) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT FK_A0EBC00726392338 FOREIGN KEY (id_livraison) REFERENCES livraison (idLivraison) ON DELETE CASCADE
        SQL);
    }
}
