<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property string  $filename
 * @property string  $md5
 * @property string  $sha1
 * @property string  $sha256
 * @property integer $size
 * @property integer $magic
 * @property integer $bytes_in_last_block
 * @property integer $blocks_in_file
 * @property integer $num_relocs
 * @property integer $header_paragraphs
 * @property integer $min_extra_paragraphs
 * @property integer $max_extra_paragraphs
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'size',
                    'magic',
                    'bytes_in_last_block',
                    'blocks_in_file',
                    'num_relocs',
                    'header_paragraphs',
                    'min_extra_paragraphs',
                    'max_extra_paragraphs'
                ],
                'integer'
            ],
            [['filename', 'md5', 'sha1', 'sha256'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filename'             => 'Filename',
            'md5'                  => 'Md5',
            'sha1'                 => 'Sha1',
            'sha256'               => 'Sha256',
            'size'                 => 'Size',
            'magic'                => 'Magic',
            'bytes_in_last_block'  => 'Bytes In Last Block',
            'blocks_in_file'       => 'Blocks In File',
            'num_relocs'           => 'Num Relocs',
            'header_paragraphs'    => 'Header Paragraphs',
            'min_extra_paragraphs' => 'Min Extra Paragraphs',
            'max_extra_paragraphs' => 'Max Extra Paragraphs',
        ];
    }
}
