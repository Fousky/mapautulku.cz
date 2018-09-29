<?php declare(strict_types = 1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20180929195717 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', is_enabled TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_8D93D64983A00E68 (firstname), INDEX IDX_8D93D6493124B5B6 (lastname), INDEX IDX_8D93D64946C53D4C (is_enabled), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (category_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, is_public TINYINT(1) DEFAULT \'1\' NOT NULL, UNIQUE INDEX UNIQ_64C19C1989D9B62 (slug), PRIMARY KEY(category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization_has_category (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', organization_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', category_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', position INT DEFAULT NULL, INDEX IDX_D0CD17E532C8A3DE (organization_id), INDEX IDX_D0CD17E512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization (organization_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', region_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', district_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', municipality_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, crn VARCHAR(12) DEFAULT NULL, tin VARCHAR(12) DEFAULT NULL, www VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, gps POINT DEFAULT NULL COMMENT \'(DC2Type:point)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_C1EE637C989D9B62 (slug), INDEX IDX_C1EE637C98260155 (region_id), INDEX IDX_C1EE637CB08FA272 (district_id), INDEX IDX_C1EE637CAE6F181C (municipality_id), PRIMARY KEY(organization_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo_municipality (municipality_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', district_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, INDEX IDX_1029BC13B08FA272 (district_id), PRIMARY KEY(municipality_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo_region (region_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, PRIMARY KEY(region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geo_district (district_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', region_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, INDEX IDX_DF78232698260155 (region_id), INDEX IDX_DF7823262B36786B (title), PRIMARY KEY(district_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE organization_has_category ADD CONSTRAINT FK_D0CD17E532C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (organization_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organization_has_category ADD CONSTRAINT FK_D0CD17E512469DE2 FOREIGN KEY (category_id) REFERENCES category (category_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637C98260155 FOREIGN KEY (region_id) REFERENCES geo_region (region_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637CB08FA272 FOREIGN KEY (district_id) REFERENCES geo_district (district_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637CAE6F181C FOREIGN KEY (municipality_id) REFERENCES geo_municipality (municipality_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE geo_municipality ADD CONSTRAINT FK_1029BC13B08FA272 FOREIGN KEY (district_id) REFERENCES geo_district (district_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE geo_district ADD CONSTRAINT FK_DF78232698260155 FOREIGN KEY (region_id) REFERENCES geo_region (region_id)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE organization_has_category DROP FOREIGN KEY FK_D0CD17E512469DE2');
        $this->addSql('ALTER TABLE organization_has_category DROP FOREIGN KEY FK_D0CD17E532C8A3DE');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637CAE6F181C');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637C98260155');
        $this->addSql('ALTER TABLE geo_district DROP FOREIGN KEY FK_DF78232698260155');
        $this->addSql('ALTER TABLE organization DROP FOREIGN KEY FK_C1EE637CB08FA272');
        $this->addSql('ALTER TABLE geo_municipality DROP FOREIGN KEY FK_1029BC13B08FA272');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE organization_has_category');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE geo_municipality');
        $this->addSql('DROP TABLE geo_region');
        $this->addSql('DROP TABLE geo_district');
    }
}
