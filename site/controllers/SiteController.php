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
    public function init()
    {
        parent::init();
        ini_set('max_execution_time', 300);
    }

    const FILE_STATUS_TEST = 'test';
    const FILE_STATUS_MALWARE = 'malware';
    const FILE_STATUS_BENING = 'bening';

    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionProcess()
    {
        header('Content-Type: text/html; charset=utf-8');
        ini_set('output_buffering', 'off');
        ini_set('zlib.output_compression', false);
        while (@ob_end_flush()) {

        }
        ini_set('implicit_flush', true);
        ob_implicit_flush(true);


        $this->processDirectory('/home/vagrant/malware', self::FILE_STATUS_MALWARE);
        $this->processDirectory('/home/vagrant/bening', self::FILE_STATUS_BENING);

        @ob_flush();
        flush();

        return "Done";
    }

    public function actionValidate()
    {
        return $this->render('index');
    }

    public function actionClear()
    {

        // clear table
        File::deleteAll();

        return "Cleared";
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
     * @param $dir
     * @param $status
     *
     * @throws \Exception
     */
    private function processDirectory($dir, $status)
    {
        $filesystem = new Filesystem(new Local($dir));
        foreach ($filesystem->listContents() as $content) {

            if ($content['type'] !== 'file') {
                continue;
            }

            $fileName = $content['basename'];
            $fileSize = $content['size'];

            $filePath = join(DIRECTORY_SEPARATOR, [$dir, $fileName]);

            $record = $this->prepareFileModel($fileName, $filePath, $fileSize, $status);
            if ($record && $record->validate()) {
                $record->insert();
                echo "{$fileName} processed.<br/>";
            } else {
                if ($record && $record->hasErrors()) {
                    VarDumper::dump($record->getErrors());
                    echo "<i style='color: #dd0000'>{$fileName} has errors.</i><br/>";

                } else {

                    echo "<i style='color: #dddd00'>{$fileName} skiped.</i><br/>";
                }

            }

            @ob_flush();
            flush();
        }
        sleep(1);
    }

    /**
     * @param $fileName
     * @param $filePath
     * @param $fileSize
     *
     * @return File
     */
    private function prepareFileModel($fileName, $filePath, $fileSize, $status)
    {
        $record = new File();

        $record->filename = $fileName;
        $record->size     = $fileSize;
        $record->malware  = $status;

        $fileContent = file_get_contents($filePath);

        $record->md5 = md5($fileContent);

        list($mzResult, $mzFields) = $this->extractMZData($filePath);
        if (empty($mzResult)) {
            return null;
        }
        $this->processResultsToRecord($record, $mzResult, $mzFields);

        list($peResult, $peFields) = $this->extractPEData($filePath);
        $this->processResultsToRecord($record, $peResult, $peFields);

        list($ddResult, $ddFields) = $this->extractDataDirectoryData($filePath);
        $this->processResultsToRecord($record, $ddResult, $ddFields);

        return $record;
    }

    /**
     * @param $filePath
     *
     * @return array
     */
    private function extractMZData($filePath)
    {
        $dump    = $this->getDump($filePath, "--mz");
        $results = spyc_load($dump);

        $fields = [
            'bytes_in_last_block'  => true,
            'blocks_in_file'       => true,
            'min_extra_paragraphs' => true,
            'overlay_number'       => true,
        ];

        return [$results, $fields];
    }

    /**
     * @param $filePath
     *
     * @return array
     */
    private function extractPEData($filePath)
    {
        $dump = $this->getDump($filePath, "--pe");
        //$dump = str_replace("  - !ruby/struct:PEdump::IMAGE_DATA_DIRECTORY\n", '  -', $dump);
        $dumpResults = spyc_load($dump);


        $results = [
            'sizeOfInitializedData' => isset($dumpResults['image_optional_header']['SizeOfInitializedData'])
                ? $dumpResults['image_optional_header']['SizeOfInitializedData']
                : null,
            'numberOfSymbols'       => isset($dumpResults['image_file_header']['NumberOfSymbols'])
                ? $dumpResults['image_file_header']['NumberOfSymbols']
                : null,
        ];


        $fields = [
            'sizeOfInitializedData' => true,
            'numberOfSymbols'       => true,
        ];

        return [$results, $fields];
    }


    /**
     * @param $filePath
     *
     * @return array
     */
    private function extractDataDirectoryData($filePath)
    {
        $dump        = $this->getDump($filePath, "--data-directory");
        $dumpResults = spyc_load($dump);

        $types   = ['EXPORT', 'IAT', 'Bound_IAT', 'LOAD_CONFIG', 'BASERELOC', 'CLR_Header'];
        $results = [];
        $fields  = [];

        foreach ($dumpResults as $data) {
            if (!is_array($data)) {
                continue;
            }

            if (isset($data['type']) && in_array($data['type'], $types)) {
                $fieldName           = 'size_' . $data['type'];
                $results[$fieldName] = $data['size'];
                $fields[$fieldName]  = true;
            }
        }

        return [$results, $fields];
    }

    /**
     * @param $record
     * @param $results
     * @param $fields
     */
    private function processResultsToRecord($record, $results, $fields)
    {
        foreach ($fields as $resultField => $dbField) {
            if ($dbField === true) {
                $dbField = $resultField;
            }

            if (isset($results[$resultField])) {
                $record->$dbField = $results[$resultField];
            }
        }
    }

}
