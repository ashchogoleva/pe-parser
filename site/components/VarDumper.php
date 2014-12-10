<?php


namespace app\components;


use yii\helpers\BaseVarDumper;

class VarDumper extends BaseVarDumper
{


    public static function dump($var, $depth = 10, $highlight = true)
    {
        parent::dump($var, $depth, $highlight);
    }


} 