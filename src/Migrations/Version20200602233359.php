<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200602233359 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE games (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, game_name VARCHAR(255) NOT NULL, game_price DOUBLE PRECISION NOT NULL, game_note INT NOT NULL, game_img VARCHAR(255) NOT NULL, game_desc VARCHAR(255) NOT NULL, game_cat VARCHAR(255) NOT NULL, INDEX IDX_FF232B3112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notices (id INT AUTO_INCREMENT NOT NULL, user_id_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, created_date DATE NOT NULL, INDEX IDX_6E2C61D29D86650F (user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE games ADD CONSTRAINT FK_FF232B3112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE notices ADD CONSTRAINT FK_6E2C61D29D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE games DROP FOREIGN KEY FK_FF232B3112469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE games');
        $this->addSql('DROP TABLE notices');
    }
}
