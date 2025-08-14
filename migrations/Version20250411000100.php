<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250411000100 extends AbstractMigration
{
    public function getDescription(): string { return 'Create client & appointment'; }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE client (
          id INT AUTO_INCREMENT NOT NULL,
          first_name VARCHAR(100) NOT NULL,
          last_name VARCHAR(100) NOT NULL,
          email VARCHAR(180) NOT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE appointment (
          id INT AUTO_INCREMENT NOT NULL,
          client_id INT NOT NULL,
          appointment_date DATETIME NOT NULL,
          INDEX IDX_APPOINTMENT_CLIENT (client_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_APPOINTMENT_CLIENT FOREIGN KEY (client_id) REFERENCES client (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_APPOINTMENT_CLIENT');
        $this->addSql('DROP TABLE appointment');
        $this->addSql('DROP TABLE client');
    }
}
