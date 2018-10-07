<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20181005234423 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organization ADD parent_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD is_root TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C727ACA70 FOREIGN KEY (parent_id) REFERENCES organization (organization_id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C1EE637C727ACA70 ON organization (parent_id)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C727ACA70');
        $this->addSql('DROP INDEX IDX_C1EE637C727ACA70 ON organization');
        $this->addSql('ALTER TABLE organization DROP parent_id, DROP is_root');
    }
}
