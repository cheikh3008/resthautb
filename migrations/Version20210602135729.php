<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210602135729 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_plat (menu_id INT NOT NULL, plat_id INT NOT NULL, INDEX IDX_E8775249CCD7E912 (menu_id), INDEX IDX_E8775249D73DB560 (plat_id), PRIMARY KEY(menu_id, plat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tables_reservation (tables_id INT NOT NULL, reservation_id INT NOT NULL, INDEX IDX_8052986785405FD2 (tables_id), INDEX IDX_80529867B83297E7 (reservation_id), PRIMARY KEY(tables_id, reservation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_plat ADD CONSTRAINT FK_E8775249CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_plat ADD CONSTRAINT FK_E8775249D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tables_reservation ADD CONSTRAINT FK_8052986785405FD2 FOREIGN KEY (tables_id) REFERENCES tables (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tables_reservation ADD CONSTRAINT FK_80529867B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commande ADD plat_id INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DD73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DD73DB560 ON commande (plat_id)');
        $this->addSql('ALTER TABLE menu ADD user_id INT NOT NULL, ADD resto_id INT NOT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A932978E8D1 FOREIGN KEY (resto_id) REFERENCES resto (id)');
        $this->addSql('CREATE INDEX IDX_7D053A93A76ED395 ON menu (user_id)');
        $this->addSql('CREATE INDEX IDX_7D053A932978E8D1 ON menu (resto_id)');
        $this->addSql('ALTER TABLE reservation ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)');
        $this->addSql('ALTER TABLE tables ADD user_id INT NOT NULL, ADD resto_id INT NOT NULL');
        $this->addSql('ALTER TABLE tables ADD CONSTRAINT FK_84470221A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE tables ADD CONSTRAINT FK_844702212978E8D1 FOREIGN KEY (resto_id) REFERENCES resto (id)');
        $this->addSql('CREATE INDEX IDX_84470221A76ED395 ON tables (user_id)');
        $this->addSql('CREATE INDEX IDX_844702212978E8D1 ON tables (resto_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE menu_plat');
        $this->addSql('DROP TABLE tables_reservation');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DD73DB560');
        $this->addSql('DROP INDEX IDX_6EEAA67DD73DB560 ON commande');
        $this->addSql('ALTER TABLE commande DROP plat_id');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93A76ED395');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A932978E8D1');
        $this->addSql('DROP INDEX IDX_7D053A93A76ED395 ON menu');
        $this->addSql('DROP INDEX IDX_7D053A932978E8D1 ON menu');
        $this->addSql('ALTER TABLE menu DROP user_id, DROP resto_id');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('DROP INDEX IDX_42C84955A76ED395 ON reservation');
        $this->addSql('ALTER TABLE reservation DROP user_id');
        $this->addSql('ALTER TABLE tables DROP FOREIGN KEY FK_84470221A76ED395');
        $this->addSql('ALTER TABLE tables DROP FOREIGN KEY FK_844702212978E8D1');
        $this->addSql('DROP INDEX IDX_84470221A76ED395 ON tables');
        $this->addSql('DROP INDEX IDX_844702212978E8D1 ON tables');
        $this->addSql('ALTER TABLE tables DROP user_id, DROP resto_id');
    }
}
