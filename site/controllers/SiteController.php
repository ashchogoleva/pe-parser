<?php

namespace app\controllers;

use app\components\VarDumper;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use MrRio\ShellWrap as sh;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{

    public function actionIndex()
    {
        $dir        = '/home/vagrant/malware';
        $filesystem = new Filesystem(new Local($dir));

        foreach ($filesystem->listContents() as $content) {
            if ($content['filename'] === '' || ($content['type'] !== 'file')) {
                continue;
            }

            $file = $filesystem->get($content['basename']);


            $mzDump   = $this->getDump(join(DIRECTORY_SEPARATOR, [$dir, $file->getPath()]), '--mz');
            $mzResult = spyc_load($mzDump);

            VarDumper::dump($mzResult);
        }


        return $this->render('index');
    }

    public function actionProcess()
    {
        return $this->render('index');
    }

    public function actionValidate()
    {
        return $this->render('index');
    }


    public function actionInfo()
    {
        if (YII_DEBUG) {
            phpinfo();
        }
    }

    private function getDump($filePath, $format)
    {
        /** @noinspection PhpUndefinedMethodInspection */

        $dump = sh::pedump("--format yaml", $format, $filePath);

        return $dump;
    }
}
