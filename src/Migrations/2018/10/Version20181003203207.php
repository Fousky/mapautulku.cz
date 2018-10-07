<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20181003203207 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE geo_district_zip (district_zip_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', district_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', zip_code INT NOT NULL, city VARCHAR(100) NOT NULL, city_part VARCHAR(100) NOT NULL, INDEX IDX_41AEB85CB08FA272 (district_id), INDEX IDX_41AEB85CA1ACE158 (zip_code), INDEX IDX_41AEB85C2D5B0234 (city), INDEX IDX_41AEB85CAA414DAE (city_part), PRIMARY KEY(district_zip_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE geo_district_zip ADD CONSTRAINT FK_41AEB85CB08FA272 FOREIGN KEY (district_id) REFERENCES geo_district (district_id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE geo_district_zip');
    }
}
