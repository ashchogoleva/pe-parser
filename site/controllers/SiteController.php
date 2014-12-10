<?php

namespace app\controllers;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use MrRio\ShellWrap as sh;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{

    public function actionIndex()
    {
        $dir = '/home/vagrant/malware';

        $filesystem = new Filesystem(new Local($dir));

        foreach ($filesystem->listContents() as $content) {
            if ($content['filename'] === '' || ($content['type'] !== 'file')) {
                continue;
            }

            $file = $filesystem->get($content['basename']);


            echo sh::pedump("--mz", "--format yaml", join(DIRECTORY_SEPARATOR, [$dir, $file->getPath()]));

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
}
