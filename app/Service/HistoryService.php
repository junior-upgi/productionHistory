<?php
/**
 * 履歷表資料處理類別
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Service;

use App\Repositories\HistoryRepository;

/**
 * Class ProductRepository
 *
 * @package App\Repositories
 */
class HistoryService
{
    /**
     * @var HistoryRepository
     */
    public $history;

    /**
     *
     *
     * HistoryService constructor.
     * @param HistoryRepository $history
     */
    public function __construct(HistoryRepository $history)
    {
        $this->history = $history;
    }

    /**
     *
     *
     * @param $input
     * @return array|mixed
     */
    public function saveHistoryPrework($input)
    {
        return $this->checkHistoryDataExists($input);
    }

    /**
     *
     *
     * @param $input
     * @return array|mixed
     */
    private function checkHistoryDataExists($input)
    {
        if ($input['type'] == 'add' && $this->history->checkExists($input)) {
            return ['success' => false, 'msg' => '此次生產履歷資料已存在!',
            ];
        }
        return $this->checkHistorySchedule($input);
    }

    /**
     *
     *
     * @param $input
     * @return mixed
     */
    private function checkHistorySchedule($input)
    {   
        $checkSchedule = $this->history->checkSchedule($input);
        if ($checkSchedule['result']) {
            $input['sampling'] = '--';
            $input['id'] = $checkSchedule['id'];
            return $this->saveHistoryNewSchedule($this->setHistoryID($input));
        }
        return $this->saveHistoryAndOldSchedule($this->setHistoryID($input));
    }

    /**
     *
     *
     * @param $input
     * @return mixed
     */
    private function setHistoryID($input)
    {
        if ($input['id'] == '' || $input['id'] == null) {
            $input['id'] = $this->history->common->getNewGUID();
        }
        $input['schedate'] = date('Y/m/d', strtotime($input['schedate']));
        return $input;
    }

    /**
     *
     *
     * @param $input
     * @return mixed
     */
    private function saveHistoryNewSchedule($input)
    {
        $params = array_except($input, ['cus_no', 'orderQty', 'sampling']);
        $result = $this->history->saveHistory($params);
        return $result;
    }

    /**
     *
     *
     * @param $history_params
     * @return mixed
     */
    private function saveHistoryAndOldSchedule($history_params)
    {
        $params = $this->service->getScheduleParams($history_params);
        $history_params = array_except($history_params, ['orderQty']);
        $result = $this->history->saveHistory($history_params);
        if ($result['success']) {
            $insertOld = $this->history->saveOldSchedule($params);
            if (!$insertOld) {
                return $insertOld;
            }
        }
        return $result;
    }

    /**
     *
     *
     * @param $array
     * @return array
     */
    public function getScheduleParams($array)
    {
        $params = [
            'id' => $array['id'],
            'type' => $array['type'],
            'sampling' => $array['sampling'],
            'glassProdLineID' => $array['glassProdLineID'],
            'schedate' => $array['schedate'],
            'prd_no' => $array['prd_no'],
            'orderQty' => $array['orderQty'],
        ];
        if ($array['type'] == 'add') {
            $params['created'] = \Carbon\Carbon::now();
        } else {
            $params['modified'] = \Carbon\Carbon::now();
        }
        return $params;
    }
}