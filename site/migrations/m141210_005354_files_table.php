<?php

use yii\db\Migration;

class m141210_005354_files_table extends Migration
{
    public function up()
    {
        $columns = [
            "filename" => "varchar(255) default null",
            "md5"      => "varchar(255) default null",
            "size"     => "int default null",
            /* -- */
            "malware"     => "tinyint(1) default null",
        ];

        $dataFields = [ // int, text,
            /* MZ -- */

            'bytes_in_last_block'   => 'int',
            'blocks_in_file'        => 'int',
            'min_extra_paragraphs'  => 'int',
            'overlay_number'        => 'int',
            /* PE -- */
            'sizeOfInitializedData' => 'int',
            'numberOfSymbols'       => 'int',
            /* DD -- */
            'size_EXPORT'           => 'int',
            'size_IAT'              => 'int',
            'size_Bound_IAT'        => 'int',
            'size_LOAD_CONFIG'      => 'int',
            'size_BASERELOC'        => 'int',
            'size_CLR_Header'       => 'int',


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
