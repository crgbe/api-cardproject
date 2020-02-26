<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200226160017 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card_group ADD family_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card_group ADD CONSTRAINT FK_55F4B503C35E566A FOREIGN KEY (family_id) REFERENCES family (id)');
        $this->addSql('CREATE INDEX IDX_55F4B503C35E566A ON card_group (family_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card_group DROP FOREIGN KEY FK_55F4B503C35E566A');
        $this->addSql('DROP INDEX IDX_55F4B503C35E566A ON card_group');
        $this->addSql('ALTER TABLE card_group DROP family_id');
    }
}
