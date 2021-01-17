<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210116212241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE CV_Realisation ADD EnLigne TINYINT(1) DEFAULT \'0\' NOT NULL, ADD slug VARCHAR(255) NOT NULL, ADD Resume VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C5DD909989D9B62 ON CV_Realisation (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C5DD909989D9B62 ON CV_Realisation');
        $this->addSql('ALTER TABLE CV_Realisation DROP EnLigne, DROP slug, DROP Resume');
    }
}
