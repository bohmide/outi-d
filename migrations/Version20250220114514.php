<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250220114514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_kids CHANGE id_challenge_id id_challenge_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quiz_kids ADD CONSTRAINT FK_88092DCFBB636FB4 FOREIGN KEY (id_challenge_id) REFERENCES challenge (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_88092DCFBB636FB4 ON quiz_kids (id_challenge_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_kids DROP FOREIGN KEY FK_88092DCFBB636FB4');
        $this->addSql('DROP INDEX IDX_88092DCFBB636FB4 ON quiz_kids');
        $this->addSql('ALTER TABLE quiz_kids CHANGE id_challenge_id id_challenge_id INT NOT NULL');
    }
}
