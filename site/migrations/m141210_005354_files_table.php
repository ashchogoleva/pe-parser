<?php

use yii\db\Migration;

class m141210_005354_files_table extends Migration
{
    public function up()
    {
        $columns = [
            "filename" => "varchar(255) default null",
            "md5"      => "varchar(255) default null",
            "sha1"     => "varchar(255) default null",
            "sha256"   => "varchar(255) default null",
            "size"     => "int default null",
            /* -- */
        ];

        $dataFields = [ // int, text,
            'bytes_in_last_block'  => 'int',
            'blocks_in_file'       => 'int',
            'num_relocs'           => 'int',
            //'header_paragraphs'    => 'int',
            'min_extra_paragraphs' => 'int',
            //'max_extra_paragraphs' => 'int',
            'overlay_number'       => 'int',
        ];
        foreach ($dataFields as $field => $type) {
            $columns[$field] = "{$type} default null";
        }

        $this->createTable(
            'files',
            $columns,
            'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM'
        );
    }

    public function down()
    {
        $this->dropTable('files');
    }
}
