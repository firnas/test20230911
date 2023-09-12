<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230911092446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicle (id INTEGER PRIMARY KEY  NOT NULL AUTO_INCREMENT, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, plate VARCHAR(255) NOT NULL, license_required VARCHAR(1) NOT NULL)');
        $this->addSql('CREATE TABLE driver (id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL  , name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, license VARCHAR(1) NOT NULL)');
        $this->addSql('CREATE TABLE trip (id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL , vehicle_id INTEGER NOT NULL, driver_id INTEGER NOT NULL, date DATETIME NOT NULL, CONSTRAINT FK_7656F53B545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) , CONSTRAINT FK_7656F53BC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id) )');
        $this->addSql('CREATE INDEX IDX_7656F53B545317D1 ON trip (vehicle_id)');
        $this->addSql('CREATE INDEX IDX_7656F53BC3423909 ON trip (driver_id)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY  NOT NULL AUTO_INCREMENT, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE trip');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
