<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20181003205142 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE organization ADD zip_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C7D662686 FOREIGN KEY (zip_id) REFERENCES geo_district_zip (district_zip_id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_C1EE637C7D662686 ON organization (zip_id)');
        $this->addSql('ALTER TABLE geo_district_zip ADD municipality_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE geo_district_zip ADD CONSTRAINT FK_41AEB85CAE6F181C FOREIGN KEY (municipality_id) REFERENCES geo_municipality (municipality_id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_41AEB85CAE6F181C ON geo_district_zip (municipality_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE geo_district_zip DROP FOREIGN KEY FK_41AEB85CAE6F181C');
        $this->addSql('DROP INDEX IDX_41AEB85CAE6F181C ON geo_district_zip');
        $this->addSql('ALTER TABLE geo_district_zip DROP municipality_id');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C7D662686');
        $this->addSql('DROP INDEX IDX_C1EE637C7D662686 ON organization');
        $this->addSql('ALTER TABLE organization DROP zip_id');
    }
}
