<?php

namespace app\controllers;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{

    public function actionIndex()
    {
        $filesystem = new Filesystem(new Local('/home/vagrant/malware'));

        foreach ($filesystem->listContents() as $content) {
            if ($content['filename'] === '' || ($content['type'] !== 'file')) {
                continue;
            }

            $file = $filesystem->get($content['basename']);

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
