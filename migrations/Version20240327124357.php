<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240327124357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_categories DROP FOREIGN KEY FK_26E00DC7B8E08577');
        $this->addSql('DROP INDEX IDX_26E00DC7B8E08577 ON task_categories');
        $this->addSql('ALTER TABLE task_categories CHANGE task_id_id task_id INT NOT NULL');
        $this->addSql('ALTER TABLE task_categories ADD CONSTRAINT FK_26E00DC78DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('CREATE INDEX IDX_26E00DC78DB60186 ON task_categories (task_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task_categories DROP FOREIGN KEY FK_26E00DC78DB60186');
        $this->addSql('DROP INDEX IDX_26E00DC78DB60186 ON task_categories');
        $this->addSql('ALTER TABLE task_categories CHANGE task_id task_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE task_categories ADD CONSTRAINT FK_26E00DC7B8E08577 FOREIGN KEY (task_id_id) REFERENCES task (id)');
        $this->addSql('CREATE INDEX IDX_26E00DC7B8E08577 ON task_categories (task_id_id)');
    }
}
