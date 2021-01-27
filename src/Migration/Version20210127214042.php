<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127214042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE CV_Competence ADD CompetenceCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD CompetenceEdition DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE CV_CompetenceCategorie ADD CompetenceCategorieCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD CompetenceCategorieEdition DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE CV_Realisation ADD RealisationCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD RealisationEdition DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE CV_RealisationImage ADD RealisationImageCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD RealisationImageEdition DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE CV_Technologie ADD TechnologieCreation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD TechnologieEdition DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE CV_Competence DROP CompetenceCreation, DROP CompetenceEdition');
        $this->addSql('ALTER TABLE CV_CompetenceCategorie DROP CompetenceCategorieCreation, DROP CompetenceCategorieEdition');
        $this->addSql('ALTER TABLE CV_Realisation DROP RealisationCreation, DROP RealisationEdition');
        $this->addSql('ALTER TABLE CV_RealisationImage DROP RealisationImageCreation, DROP RealisationImageEdition');
        $this->addSql('ALTER TABLE CV_Technologie DROP TechnologieCreation, DROP TechnologieEdition');
    }
}
