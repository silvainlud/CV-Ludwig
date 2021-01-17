<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115182140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE CV_Realisation (id INT AUTO_INCREMENT NOT NULL, NomRealisation VARCHAR(255) NOT NULL, DescriptionRealisation VARCHAR(255) NOT NULL, EntrepriseRealisation VARCHAR(32) DEFAULT NULL, DateMiseEnLigneRealisation DATETIME DEFAULT NULL, TempsRealisation VARCHAR(32) DEFAULT NULL, LienRealisation VARCHAR(255) DEFAULT NULL, mainImage_id VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C5DD909C359E60E (NomRealisation), UNIQUE INDEX UNIQ_C5DD90944D00FAF (mainImage_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE CV_RealisationImage (id VARCHAR(255) NOT NULL, realisation_id INT DEFAULT NULL, realisations_id INT DEFAULT NULL, image LONGBLOB NOT NULL, discr VARCHAR(255) NOT NULL, INDEX IDX_9A614DD6B685E551 (realisation_id), INDEX IDX_9A614DD6FBAB59A2 (realisations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE technologie_technologie (source_technologie_num INT NOT NULL, target_technologie_num INT NOT NULL, INDEX IDX_883B8DA79734029 (source_technologie_num), INDEX IDX_883B8DA9D628C41 (target_technologie_num), PRIMARY KEY(source_technologie_num, target_technologie_num)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE CV_Realisation ADD CONSTRAINT FK_C5DD90944D00FAF FOREIGN KEY (mainImage_id) REFERENCES CV_RealisationImage (id)');
        $this->addSql('ALTER TABLE CV_RealisationImage ADD CONSTRAINT FK_9A614DD6B685E551 FOREIGN KEY (realisation_id) REFERENCES CV_Realisation (id)');
        $this->addSql('ALTER TABLE CV_RealisationImage ADD CONSTRAINT FK_9A614DD6FBAB59A2 FOREIGN KEY (realisations_id) REFERENCES CV_Realisation (id)');
        $this->addSql('ALTER TABLE technologie_technologie ADD CONSTRAINT FK_883B8DA79734029 FOREIGN KEY (source_technologie_num) REFERENCES CV_Technologie (NumTechnologie)');
        $this->addSql('ALTER TABLE technologie_technologie ADD CONSTRAINT FK_883B8DA9D628C41 FOREIGN KEY (target_technologie_num) REFERENCES CV_Technologie (NumTechnologie)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE CV_RealisationImage DROP FOREIGN KEY FK_9A614DD6B685E551');
        $this->addSql('ALTER TABLE CV_RealisationImage DROP FOREIGN KEY FK_9A614DD6FBAB59A2');
        $this->addSql('ALTER TABLE CV_Realisation DROP FOREIGN KEY FK_C5DD90944D00FAF');
        $this->addSql('DROP TABLE CV_Realisation');
        $this->addSql('DROP TABLE CV_RealisationImage');
        $this->addSql('DROP TABLE technologie_technologie');
    }
}
