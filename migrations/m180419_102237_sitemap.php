<?php

/**
 * Class m180419_102237_sitemap
 */
class m180419_102237_sitemap extends \yii\db\Migration
{
    public $engine = 'ENGINE=MyISAM DEFAULT CHARSET=utf8';

    public function up()
    {
        $this->createTable('gr_sitemap', [
            'id' => $this->primaryKey(),
            'loc' => $this->string(),
            'lastmod' => $this->integer(),
            'changefreq' => $this->string(),
            'priority' => $this->decimal(11,1),
            'status' => $this->boolean()->defaultValue(true),
            'order_num' =>$this->integer(),
        ], $this->engine);
    }

    public function down()
    {
        echo "m180419_102237_sitemap cannot be reverted.\n";

        $this->dropTable('gr_sitemap');

        return false;
    }

}
