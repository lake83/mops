<?php

use yii\db\Migration;

/**
 * Class m230920_132647_table_temperature_monthly
 */
class m230920_132647_table_temperature_monthly extends Migration
{
    public function up()
    {
        $this->createTable('temperature_monthly', [
            'id' => $this->primaryKey(),
            'day' => $this->integer()->notNull(),
            'month' => $this->integer()->notNull(),
            'year' => $this->integer()->notNull(),
            'temperature' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull()
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);
    }

    public function down()
    {
        $this->dropTable('temperature_monthly');
    }
}
