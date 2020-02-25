<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200224174022 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE group_card DROP FOREIGN KEY FK_3F4EC3A3FE54D947');
        $this->addSql('CREATE TABLE card_group (id INT AUTO_INCREMENT NOT NULL, family_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_55F4B503C35E566A (family_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card_group_card (card_group_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_533C5EAD476517C4 (card_group_id), INDEX IDX_533C5EAD4ACC9A20 (card_id), PRIMARY KEY(card_group_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE card_group ADD CONSTRAINT FK_55F4B503C35E566A FOREIGN KEY (family_id) REFERENCES family (id)');
        $this->addSql('ALTER TABLE card_group_card ADD CONSTRAINT FK_533C5EAD476517C4 FOREIGN KEY (card_group_id) REFERENCES card_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card_group_card ADD CONSTRAINT FK_533C5EAD4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE group_card');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE card_group_card DROP FOREIGN KEY FK_533C5EAD476517C4');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, family_id INT DEFAULT NULL, name VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_6DC044C5C35E566A (family_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE group_card (group_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_3F4EC3A3FE54D947 (group_id), INDEX IDX_3F4EC3A34ACC9A20 (card_id), PRIMARY KEY(group_id, card_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `group` ADD CONSTRAINT FK_6DC044C5C35E566A FOREIGN KEY (family_id) REFERENCES family (id)');
        $this->addSql('ALTER TABLE group_card ADD CONSTRAINT FK_3F4EC3A34ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_card ADD CONSTRAINT FK_3F4EC3A3FE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE card_group');
        $this->addSql('DROP TABLE card_group_card');
    }
}
