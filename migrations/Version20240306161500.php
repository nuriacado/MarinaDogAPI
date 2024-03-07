<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306161500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detalles_reserva (id INT AUTO_INCREMENT NOT NULL, habitacion_id INT NOT NULL, fecha_entrada DATE NOT NULL, fecha_salida DATE NOT NULL, nombre VARCHAR(100) NOT NULL, apellidos VARCHAR(100) NOT NULL, correo VARCHAR(100) NOT NULL, telefono VARCHAR(9) NOT NULL, direccion VARCHAR(100) NOT NULL, INDEX IDX_D0454BC5B009290D (habitacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE habitacion (id INT AUTO_INCREMENT NOT NULL, num_camas INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detalles_reserva ADD CONSTRAINT FK_D0454BC5B009290D FOREIGN KEY (habitacion_id) REFERENCES habitacion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalles_reserva DROP FOREIGN KEY FK_D0454BC5B009290D');
        $this->addSql('DROP TABLE detalles_reserva');
        $this->addSql('DROP TABLE habitacion');
    }
}
