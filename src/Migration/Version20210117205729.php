<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210117205729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE realisation_technologie (realisation_id INT NOT NULL, technologie_numtechnologie INT NOT NULL, INDEX IDX_6B3ED48CB685E551 (realisation_id), INDEX IDX_6B3ED48CD93522A1 (technologie_numtechnologie), PRIMARY KEY(realisation_id, technologie_numtechnologie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE realisation_technologie ADD CONSTRAINT FK_6B3ED48CB685E551 FOREIGN KEY (realisation_id) REFERENCES CV_Realisation (id)');
        $this->addSql('ALTER TABLE realisation_technologie ADD CONSTRAINT FK_6B3ED48CD93522A1 FOREIGN KEY (technologie_numtechnologie) REFERENCES CV_Technologie (NumTechnologie)');
        $this->addSql('ALTER TABLE CV_Technologie ADD link VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE realisation_technologie');
        $this->addSql('ALTER TABLE CV_Technologie DROP link');
    }
}
