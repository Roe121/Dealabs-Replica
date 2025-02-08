<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250207221407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deal ADD delivery_price INT DEFAULT NULL, CHANGE deal_url url VARCHAR(2083) NOT NULL');
        $this->addSql('ALTER TABLE merchant CHANGE logo image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deal DROP delivery_price, CHANGE url deal_url VARCHAR(2083) NOT NULL');
        $this->addSql('ALTER TABLE merchant CHANGE image logo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user DROP image');
    }
}
