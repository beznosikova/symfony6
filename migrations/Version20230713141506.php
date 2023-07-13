<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230713141506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE survey SET slug=REPLACE(LOWER(name), ' ', '-')");
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD5F9BFC989D9B62 ON survey (slug)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_AD5F9BFC989D9B62 ON survey');
    }
}
