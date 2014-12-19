<?php namespace App\Orm\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20141126185448_temperatures_table extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE temperatures (id INT UNSIGNED AUTO_INCREMENT NOT NULL, value SMALLINT NOT NULL, created DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE temperatures');
    }
}
