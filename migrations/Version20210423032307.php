<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210423032307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plat ADD resto_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A2072978E8D1 FOREIGN KEY (resto_id) REFERENCES resto (id)');
        $this->addSql('CREATE INDEX IDX_2038A2072978E8D1 ON plat (resto_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A2072978E8D1');
        $this->addSql('DROP INDEX IDX_2038A2072978E8D1 ON plat');
        $this->addSql('ALTER TABLE plat DROP resto_id');
    }
}
