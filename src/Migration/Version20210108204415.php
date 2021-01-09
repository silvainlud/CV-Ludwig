<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210108204415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE CV_Competence (niveau_id INT NOT NULL, CodeCompetence INT AUTO_INCREMENT NOT NULL, EstScolaire TINYINT(1) NOT NULL, EstAutoDitacte TINYINT(1) NOT NULL, NumCompetenceCategorie INT NOT NULL, NumTechnologie INT NOT NULL, INDEX IDX_F306E305701CA12 (NumCompetenceCategorie), UNIQUE INDEX UNIQ_F306E30A257188 (NumTechnologie), INDEX IDX_F306E30B3E9C81 (niveau_id), PRIMARY KEY(CodeCompetence)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE CV_CompetenceCategorie (NumCompetenceCategorie INT AUTO_INCREMENT NOT NULL, NomCompetenceCategorie VARCHAR(64) NOT NULL, OrdreCompetenceCategorie INT DEFAULT NULL, UNIQUE INDEX UNIQ_79E9314BDDD3D0F5 (NomCompetenceCategorie), PRIMARY KEY(NumCompetenceCategorie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE CV_CompetenceNiveau (id INT AUTO_INCREMENT NOT NULL, NomNiveau VARCHAR(32) NOT NULL, ClassNiveau VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE CV_Technologie (NumTechnologie INT AUTO_INCREMENT NOT NULL, NomTechnologie VARCHAR(64) NOT NULL, DescriptionTechnologie LONGTEXT NOT NULL, ImageTechnologie LONGBLOB NOT NULL, ImageExtensionTechnologie VARCHAR(10) NOT NULL, ColorTechnologie VARCHAR(25) DEFAULT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4B798E73847DB174 (NomTechnologie), UNIQUE INDEX UNIQ_4B798E73989D9B62 (slug), PRIMARY KEY(NumTechnologie)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE SilvainEu_Service (id INT AUTO_INCREMENT NOT NULL, NomService VARCHAR(32) NOT NULL, DescriptionService LONGTEXT NOT NULL, LinkService VARCHAR(128) NOT NULL, ImageService LONGBLOB NOT NULL, ImageExtensionService VARCHAR(10) NOT NULL, SlugService VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_B8EFFE451881D84A (NomService), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Utilisateur (CodeUtilisateur INT AUTO_INCREMENT NOT NULL, Utilisateur VARCHAR(180) NOT NULL, EmailUtilisateur VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, DateCreationUtilisateur DATETIME NOT NULL, UNIQUE INDEX UNIQ_9B80EC649B80EC64 (Utilisateur), UNIQUE INDEX UNIQ_9B80EC64434795BE (EmailUtilisateur), PRIMARY KEY(CodeUtilisateur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE CV_Competence ADD CONSTRAINT FK_F306E305701CA12 FOREIGN KEY (NumCompetenceCategorie) REFERENCES CV_CompetenceCategorie (NumCompetenceCategorie)');
        $this->addSql('ALTER TABLE CV_Competence ADD CONSTRAINT FK_F306E30A257188 FOREIGN KEY (NumTechnologie) REFERENCES CV_Technologie (NumTechnologie)');
        $this->addSql('ALTER TABLE CV_Competence ADD CONSTRAINT FK_F306E30B3E9C81 FOREIGN KEY (niveau_id) REFERENCES CV_CompetenceNiveau (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE CV_Competence DROP FOREIGN KEY FK_F306E305701CA12');
        $this->addSql('ALTER TABLE CV_Competence DROP FOREIGN KEY FK_F306E30B3E9C81');
        $this->addSql('ALTER TABLE CV_Competence DROP FOREIGN KEY FK_F306E30A257188');
        $this->addSql('DROP TABLE CV_Competence');
        $this->addSql('DROP TABLE CV_CompetenceCategorie');
        $this->addSql('DROP TABLE CV_CompetenceNiveau');
        $this->addSql('DROP TABLE CV_Technologie');
        $this->addSql('DROP TABLE SilvainEu_Service');
        $this->addSql('DROP TABLE Utilisateur');
    }
}
