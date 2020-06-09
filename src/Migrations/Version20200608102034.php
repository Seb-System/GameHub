<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200608102034 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE buy (id INT AUTO_INCREMENT NOT NULL, game_id_id INT NOT NULL, date DATE NOT NULL, game_key VARCHAR(255) NOT NULL, pay_id VARCHAR(255) NOT NULL, INDEX IDX_CF8382774D77E7D8 (game_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE buy_user (buy_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_73058A654AFB9379 (buy_id), INDEX IDX_73058A65A76ED395 (user_id), PRIMARY KEY(buy_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE buy ADD CONSTRAINT FK_CF8382774D77E7D8 FOREIGN KEY (game_id_id) REFERENCES games (id)');
        $this->addSql('ALTER TABLE buy_user ADD CONSTRAINT FK_73058A654AFB9379 FOREIGN KEY (buy_id) REFERENCES buy (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE buy_user ADD CONSTRAINT FK_73058A65A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE buy_user DROP FOREIGN KEY FK_73058A654AFB9379');
        $this->addSql('DROP TABLE buy');
        $this->addSql('DROP TABLE buy_user');
    }
}
