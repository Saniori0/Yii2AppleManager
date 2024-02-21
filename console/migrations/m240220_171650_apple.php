<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m240220_171650_apple
 */
class m240220_171650_apple extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {

        $this->createTable("apple", [
            "id" => $this->primaryKey(),
            "status" => $this->integer(1),
            "size" => $this->integer(3),
            "color" => $this->string(24),
            "create_time" => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT CURRENT_TIMESTAMP",
            "update_time" => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT CURRENT_TIMESTAMP",
            "fell_time" => Schema::TYPE_TIMESTAMP . " NOT NULL DEFAULT CURRENT_TIMESTAMP",
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {

        $this->dropTable("apple");

        return false;
    }

}
