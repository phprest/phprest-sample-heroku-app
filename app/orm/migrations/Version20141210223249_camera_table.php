<?php namespace App\Orm\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20141210223249_camera_table extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE camera (state ENUM('on', 'off'), PRIMARY KEY(state)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
    }

    public function down(Schema $schema)
    {
        $this->addSql("DROP TABLE camera");
    }
}
