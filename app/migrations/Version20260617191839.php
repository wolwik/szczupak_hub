<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260617191839 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, answer_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_518B7ACFAA334807 (answer_id), INDEX IDX_518B7ACFA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFAA334807 FOREIGN KEY (answer_id) REFERENCES answers (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY `FK_5A108564A76ED395`');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY `FK_5A108564AA334807`');
        $this->addSql('DROP TABLE vote');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, answer_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_5A108564AA334807 (answer_id), INDEX IDX_5A108564A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT `FK_5A108564A76ED395` FOREIGN KEY (user_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT `FK_5A108564AA334807` FOREIGN KEY (answer_id) REFERENCES answers (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFAA334807');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFA76ED395');
        $this->addSql('DROP TABLE votes');
    }
}
