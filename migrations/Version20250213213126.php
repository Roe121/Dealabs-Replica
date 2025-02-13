<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213213126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deal_category DROP FOREIGN KEY FK_E5CB085B12469DE2');
        $this->addSql('ALTER TABLE deal_category DROP FOREIGN KEY FK_E5CB085BF60E2305');
        $this->addSql('DROP TABLE deal_category');
        $this->addSql('ALTER TABLE deal ADD category_id INT DEFAULT NULL, DROP enable');
        $this->addSql('ALTER TABLE deal ADD CONSTRAINT FK_E3FEC11612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_E3FEC11612469DE2 ON deal (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE deal_category (deal_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_E5CB085B12469DE2 (category_id), INDEX IDX_E5CB085BF60E2305 (deal_id), PRIMARY KEY(deal_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE deal_category ADD CONSTRAINT FK_E5CB085B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deal_category ADD CONSTRAINT FK_E5CB085BF60E2305 FOREIGN KEY (deal_id) REFERENCES deal (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE deal DROP FOREIGN KEY FK_E3FEC11612469DE2');
        $this->addSql('DROP INDEX IDX_E3FEC11612469DE2 ON deal');
        $this->addSql('ALTER TABLE deal ADD enable TINYINT(1) NOT NULL, DROP category_id');
    }
}
