<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260617195714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answers (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, question_id INT NOT NULL, author_id INT DEFAULT NULL, INDEX IDX_50D0C6061E27F6BF (question_id), INDEX IDX_50D0C606F675F31B (author_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE avatars (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(191) NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_B0C98520A76ED395 (user_id), UNIQUE INDEX uq_avatars_filename (filename), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE questions (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, category_id INT NOT NULL, author_id INT DEFAULT NULL, best_answer_id INT DEFAULT NULL, INDEX IDX_8ADC54D512469DE2 (category_id), INDEX IDX_8ADC54D5F675F31B (author_id), UNIQUE INDEX UNIQ_8ADC54D5B6DEA817 (best_answer_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE questions_tags (question_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_721C30741E27F6BF (question_id), INDEX IDX_721C3074BAD26311 (tag_id), PRIMARY KEY (question_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nickname VARCHAR(50) DEFAULT NULL, is_blocked TINYINT NOT NULL, UNIQUE INDEX UNIQ_1483A5E9A188FE64 (nickname), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE votes (id INT AUTO_INCREMENT NOT NULL, answer_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_518B7ACFAA334807 (answer_id), INDEX IDX_518B7ACFA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6061E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606F675F31B FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE avatars ADD CONSTRAINT FK_B0C98520A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D512469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5F675F31B FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D5B6DEA817 FOREIGN KEY (best_answer_id) REFERENCES answers (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE questions_tags ADD CONSTRAINT FK_721C30741E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questions_tags ADD CONSTRAINT FK_721C3074BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFAA334807 FOREIGN KEY (answer_id) REFERENCES answers (id)');
        $this->addSql('ALTER TABLE votes ADD CONSTRAINT FK_518B7ACFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C6061E27F6BF');
        $this->addSql('ALTER TABLE answers DROP FOREIGN KEY FK_50D0C606F675F31B');
        $this->addSql('ALTER TABLE avatars DROP FOREIGN KEY FK_B0C98520A76ED395');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D512469DE2');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5F675F31B');
        $this->addSql('ALTER TABLE questions DROP FOREIGN KEY FK_8ADC54D5B6DEA817');
        $this->addSql('ALTER TABLE questions_tags DROP FOREIGN KEY FK_721C30741E27F6BF');
        $this->addSql('ALTER TABLE questions_tags DROP FOREIGN KEY FK_721C3074BAD26311');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFAA334807');
        $this->addSql('ALTER TABLE votes DROP FOREIGN KEY FK_518B7ACFA76ED395');
        $this->addSql('DROP TABLE answers');
        $this->addSql('DROP TABLE avatars');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE questions_tags');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE votes');
    }
}
