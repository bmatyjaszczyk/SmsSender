<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201002202410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create sms and sms status tables';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sms (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, status_id INT NOT NULL, body LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B0A93A77F624B39D (sender_id), INDEX IDX_B0A93A776BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sms_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sms ADD CONSTRAINT FK_B0A93A77F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sms ADD CONSTRAINT FK_B0A93A776BF700BD FOREIGN KEY (status_id) REFERENCES sms_status (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sms DROP FOREIGN KEY FK_B0A93A776BF700BD');
        $this->addSql('DROP TABLE sms');
        $this->addSql('DROP TABLE sms_status');
    }
}
