<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241030103728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD marche_id INT NOT NULL, ADD jour VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_979CC42B9E494911 FOREIGN KEY (marche_id) REFERENCES `Marche` (id)');
        $this->addSql('CREATE INDEX IDX_979CC42B9E494911 ON commande (marche_id)');
        $this->addSql('ALTER TABLE produit CHANGE image_file_name image_file_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `Commande` DROP FOREIGN KEY FK_979CC42B9E494911');
        $this->addSql('DROP INDEX IDX_979CC42B9E494911 ON `Commande`');
        $this->addSql('ALTER TABLE `Commande` DROP marche_id, DROP jour');
        $this->addSql('ALTER TABLE `Produit` CHANGE image_file_name image_file_name VARCHAR(255) NOT NULL');
    }
}
