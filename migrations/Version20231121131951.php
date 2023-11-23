<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121131951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris_products DROP FOREIGN KEY FK_C365447C51E8871B');
        $this->addSql('ALTER TABLE favoris_products DROP FOREIGN KEY FK_C365447C6C8A81A9');
        $this->addSql('ALTER TABLE favoris_users DROP FOREIGN KEY FK_C7E6D68851E8871B');
        $this->addSql('ALTER TABLE favoris_users DROP FOREIGN KEY FK_C7E6D68867B3B43D');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE favoris_products');
        $this->addSql('DROP TABLE favoris_users');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favoris_products (favoris_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_C365447C6C8A81A9 (products_id), INDEX IDX_C365447C51E8871B (favoris_id), PRIMARY KEY(favoris_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favoris_users (favoris_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_C7E6D68851E8871B (favoris_id), INDEX IDX_C7E6D68867B3B43D (users_id), PRIMARY KEY(favoris_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favoris_products ADD CONSTRAINT FK_C365447C51E8871B FOREIGN KEY (favoris_id) REFERENCES favoris (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_products ADD CONSTRAINT FK_C365447C6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_users ADD CONSTRAINT FK_C7E6D68851E8871B FOREIGN KEY (favoris_id) REFERENCES favoris (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favoris_users ADD CONSTRAINT FK_C7E6D68867B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
