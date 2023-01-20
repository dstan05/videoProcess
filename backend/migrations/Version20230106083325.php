<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106083325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE public.resize_video (id INT NOT NULL, path VARCHAR(255) NOT NULL, quality INT NOT NULL, video_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE public.video (id INT NOT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE SEQUENCE public.resize_video_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE public.video_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE public.resize_video');
        $this->addSql('DROP TABLE public.video');
        $this->addSql('DROP SEQUENCE public.resize_video_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE public.video_id_seq CASCADE');
    }
}
