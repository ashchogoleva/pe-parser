<?php

namespace app\controllers;

use app\components\VarDumper;
use app\models\File;
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


        // clear table
        File::deleteAll();

        foreach ($filesystem->listContents() as $content) {

            if ($content['filename'] === '' || ($content['type'] !== 'file')) {
                continue;
            }

            $fileName = $content['basename'];
            $fileSize = $content['size'];

            $filePath = join(DIRECTORY_SEPARATOR, [$dir, $fileName]);

            $record = $this->prepareFileModel($fileName, $filePath, $fileSize);

            $record->insert();
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

    private function getDump($filePath, $header)
    {
        /** @noinspection PhpUndefinedMethodInspection */

        $dump = sh::pedump("--format yaml", $header, $filePath);

        return $dump;
    }

    /**
     * @param $fileName
     * @param $filePath
     * @param $fileSize
     *
     * @return File
     */
    private function prepareFileModel($fileName, $filePath, $fileSize)
    {
        $record = new File();

        $record->filename = $fileName;
        $record->size     = $fileSize;

        $fileContent = file_get_contents($filePath);

        $record->md5    = md5($fileContent);
        $record->sha1   = sha1($fileContent);
        $record->sha256 = hash('sha256', $fileContent);


        $mzDump   = $this->getDump($filePath, "--mz");
        $mzResult = spyc_load($mzDump);

        VarDumper::dump($mzResult);

        $mzFields = [
            'bytes_in_last_block' => true,
        ];

        foreach ($mzFields as $resultField => $dbField) {
            if ($dbField === true) {
                $dbField = $resultField;
            }

            if (isset($mzResult[$resultField])) {
                $record->$dbField = $mzResult[$resultField];
            }

        }

        return $record;
    }
}
