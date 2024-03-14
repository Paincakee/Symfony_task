<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314150428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2599B8CBC0');
        $this->addSql('DROP INDEX IDX_527EDB2599B8CBC0 ON task');
        $this->addSql('ALTER TABLE task ADD user_uuid VARCHAR(180) NOT NULL, DROP user_uuid_id');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25ABFE1C6F FOREIGN KEY (user_uuid) REFERENCES user (uuid)');
        $this->addSql('CREATE INDEX IDX_527EDB25ABFE1C6F ON task (user_uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25ABFE1C6F');
        $this->addSql('DROP INDEX IDX_527EDB25ABFE1C6F ON task');
        $this->addSql('ALTER TABLE task ADD user_uuid_id INT NOT NULL, DROP user_uuid');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2599B8CBC0 FOREIGN KEY (user_uuid_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_527EDB2599B8CBC0 ON task (user_uuid_id)');
    }
}
