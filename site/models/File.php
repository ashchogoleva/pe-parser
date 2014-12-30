<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property string  $filename
 * @property string  $md5
 * @property integer $size
 * @property string  $malware
 * @property integer $bytes_in_last_block
 * @property integer $blocks_in_file
 * @property integer $min_extra_paragraphs
 * @property integer $overlay_number
 * @property integer $sizeOfInitializedData
 * @property integer $numberOfSymbols
 * @property integer $size_EXPORT
 * @property integer $size_IAT
 * @property integer $size_Bound_IAT
 * @property integer $size_LOAD_CONFIG
 * @property integer $size_BASERELOC
 * @property integer $size_CLR_Header
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
                    'bytes_in_last_block',
                    'blocks_in_file',
                    'min_extra_paragraphs',
                    'overlay_number',
                    'sizeOfInitializedData',
                    'numberOfSymbols',
                    'size_EXPORT',
                    'size_IAT',
                    'size_Bound_IAT',
                    'size_LOAD_CONFIG',
                    'size_BASERELOC',
                    'size_CLR_Header'
                ],
                'integer'
            ],
            [['malware'], 'string', 'max' => 255],
            [['filename', 'md5'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filename'              => 'Filename',
            'md5'                   => 'Md5',
            'size'                  => 'Size',
            'malware'               => 'Malware',
            'bytes_in_last_block'   => 'Bytes In Last Block',
            'blocks_in_file'        => 'Blocks In File',
            'min_extra_paragraphs'  => 'Min Extra Paragraphs',
            'overlay_number'        => 'Overlay Number',
            'sizeOfInitializedData' => 'Size Of Initialized Data',
            'numberOfSymbols'       => 'Number Of Symbols',
            'size_EXPORT'           => 'Size  Export',
            'size_IAT'              => 'Size  Iat',
            'size_Bound_IAT'        => 'Size  Bound  Iat',
            'size_LOAD_CONFIG'      => 'Size  Load  Config',
            'size_BASERELOC'        => 'Size  Basereloc',
            'size_CLR_Header'       => 'Size  Clr  Header',
        ];
    }
}
