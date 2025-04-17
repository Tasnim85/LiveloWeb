<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416015359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0875765B5 FOREIGN KEY (livraisonId) REFERENCES livraison (idLivraison) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie ADD CONSTRAINT FK_497DD634DE12AB56 FOREIGN KEY (created_by) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DDE12AB56 FOREIGN KEY (created_by) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT FK_FE86641064B64DCC FOREIGN KEY (userId) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture ADD CONSTRAINT FK_FE8664108F992C7E FOREIGN KEY (commandeId) REFERENCES commande (id_commande) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_A60C9F1FFE98B76E ON livraison
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livraison DROP zoneId, CHANGE id_livraison id_livraison INT AUTO_INCREMENT NOT NULL
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
            ALTER TABLE trajet ADD CONSTRAINT FK_2B5BA98CD3169E99 FOREIGN KEY (idZone) REFERENCES zone (idZone) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT FK_A0EBC0076B3CA4B FOREIGN KEY (id_user) REFERENCES user (idUser) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone ADD CONSTRAINT FK_A0EBC00726392338 FOREIGN KEY (id_livraison) REFERENCES livraison (idLivraison) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0875765B5
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE categorie DROP FOREIGN KEY FK_497DD634DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DDE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY FK_FE86641064B64DCC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE facture DROP FOREIGN KEY FK_FE8664108F992C7E
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
            ALTER TABLE livraison ADD zoneId INT DEFAULT NULL, CHANGE id_livraison id_livraison INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A60C9F1FFE98B76E ON livraison (zoneId)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trajet DROP FOREIGN KEY FK_2B5BA98CD3169E99
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC0076B3CA4B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE zone DROP FOREIGN KEY FK_A0EBC00726392338
        SQL);
    }
}
