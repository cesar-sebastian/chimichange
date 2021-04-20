<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210418233110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cash_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE account (id INT NOT NULL, user_id INT DEFAULT NULL, cash_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4A76ED395 ON account (user_id)');
        $this->addSql('CREATE INDEX IDX_7D3656A43D7A0C28 ON account (cash_id)');
        $this->addSql('CREATE TABLE cash (id INT NOT NULL, account_id INT DEFAULT NULL, currency VARCHAR(3) NOT NULL, amount NUMERIC(10, 4) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6B9E5B99B6B5FBA ON cash (account_id)');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, account_id INT DEFAULT NULL, operation VARCHAR(50) NOT NULL, currency_from VARCHAR(3) NOT NULL, currency_to VARCHAR(3) NOT NULL, rate NUMERIC(10, 4) NOT NULL, amount_from NUMERIC(10, 4) NOT NULL, amount_to NUMERIC(10, 4) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D19B6B5FBA ON transaction (account_id)');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account ADD CONSTRAINT FK_7D3656A43D7A0C28 FOREIGN KEY (cash_id) REFERENCES cash (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cash ADD CONSTRAINT FK_6B9E5B99B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cash DROP CONSTRAINT FK_6B9E5B99B6B5FBA');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D19B6B5FBA');
        $this->addSql('ALTER TABLE account DROP CONSTRAINT FK_7D3656A43D7A0C28');
        $this->addSql('DROP SEQUENCE account_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cash_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transaction_id_seq CASCADE');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE cash');
        $this->addSql('DROP TABLE transaction');
    }
}
