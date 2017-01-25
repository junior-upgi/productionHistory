<?php
/**
 * Created by PhpStorm.
 * User: Spark
 * Date: 2017/1/25
 * Time: 下午2:24
 */

namespace App\Service;

use App\Repositories\QCRepository;

/**
 * Class QCService
 * @package App\Service
 */
class QCService
{
    use FileService;
    /**
     * @var QCRepository
     */
    public $qc;

    /**
     * QCService constructor.
     * @param QCRepository $qc
     */
    public function __construct(QCRepository $qc)
    {
        $this->qc =  $qc;
    }

    /**
     * 儲存品管表
     *
     *
     * @param $request
     * @return array|mixed
     */
    public function saveQC($request)
    {
        $input = $request->input();
        $file = $request->file('draw');
        $input = $this->fileCheck($input, $file);
        $input = $this->setInspection($input);
        return $this->qc->saveQC($input);
    }

    private function fileCheck($input, $file)
    {
        if (isset($file)) {
            return $this->setFile($input, $file);
        }
        return $this->setFileID($input, null);
    }

    /**
     * 設定檔案
     *
     * @param $input
     * @param $file
     * @return array
     */
    private function setFile($input, $file)
    {
        return $this->setFileID($input, $this->saveFile($file));
    }

    /**
     * 設定檔案ID
     *
     * @param $input
     * @param $id
     * @return array
     */
    private function setFileID($input, $id)
    {
        $newID = substr($id . $input['setDraw'], 0, 36);
        $input['draw'] = $newID;
        $input = array_except($input, 'setDraw');
        return $input;
    }

    /**
     * 設定加工項目
     *
     * @param $input
     * @return mixed
     */
    private function setInspection($input)
    {
        $input['fullInspection'] = implode(',', $input['fullInspection']);
        return $input;
    }
}