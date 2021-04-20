<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210419022244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE rate_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE rate (id INT NOT NULL, currency VARCHAR(3) NOT NULL, value NUMERIC(10, 6) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT fk_7d3656a43d7a0c28');
        $this->addSql('DROP INDEX idx_7d3656a43d7a0c28');
        $this->addSql('ALTER TABLE account DROP cash_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE rate_id_seq CASCADE');
        $this->addSql('DROP TABLE rate');
        $this->addSql('ALTER TABLE account ADD cash_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT fk_7d3656a43d7a0c28 FOREIGN KEY (cash_id) REFERENCES cash (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_7d3656a43d7a0c28 ON account (cash_id)');
    }
}
