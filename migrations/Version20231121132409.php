<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121132409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products_users (products_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_F4DA5B396C8A81A9 (products_id), INDEX IDX_F4DA5B3967B3B43D (users_id), PRIMARY KEY(products_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE products_users ADD CONSTRAINT FK_F4DA5B396C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_users ADD CONSTRAINT FK_F4DA5B3967B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products_users DROP FOREIGN KEY FK_F4DA5B396C8A81A9');
        $this->addSql('ALTER TABLE products_users DROP FOREIGN KEY FK_F4DA5B3967B3B43D');
        $this->addSql('DROP TABLE products_users');
    }
}
