<?php
namespace App\Repositories;

use DB;
use App\Service\Common;
use App\Models\productionHistory\ProductionDuty;
use App\Models\productionHistory\ProductionHistory;
use App\Models\UPGWeb\Customer;
use App\Models\Z_DB_U105\Schedule;
use App\Models\UPGWeb\Staff;
use App\Models\productionHistory\GlassRun;
use App\Models\productionHistory\GlassRunDetail;
use App\Models\productionHistory\GlassRunPlan;
use App\Models\productionHistory\GlassRunPlanDetail;
use App\Models\productionHistory\AllGlassRun;
use App\Models\productionHistory\QualityControl;
use App\Models\productionHistory\IsProdData;
use App\Models\productionHistory\OldSchedule;
use App\Models\productionHistory\DefectItem;
use App\Models\productionHistory\DefectTemplate;
use App\Models\productionHistory\TemplateItem;
use App\Models\productionHistory\Defect;
use App\Models\productionHistory\DefectGroup;
use App\Models\UPGWeb\Glass;
use App\Models\taskTracking\TaskList;
use App\Models\taskTracking\TaskListDetail;

class BaseRepository
{
    public $common;
    public $histosy;
    public $customer;
    public $schedule;
    public $staff;
    public $run;
    public $runDetail;
    public $plan;
    public $planDetail;
    public $allGlass;
    public $qc;
    public $prod;
    public $glass;
    public $task;
    public $taskDetail;
    public $oldSchedule;
    public $item;
    public $template;
    public $templateItem;
    public $defect;
    public $defectGroup;
    
    public function __construct() {
        $this->common = new Common();
        $this->duty = new ProductionDuty();
        $this->schedule = new Schedule();
        $this->staff = new Staff();
        $this->history = new ProductionHistory();
        $this->customer = new Customer();
        $this->run = new GlassRun();
        $this->runDetail = new GlassRunDetail();
        $this->plan = new GlassRunPlan();
        $this->planDetail = new GlassRunPlanDetail();
        $this->allGlass = new AllGlassRun();
        $this->qc = new QualityControl();
        $this->prod = new IsProdData;
        $this->glass = new Glass();
        $this->task = new TaskList();
        $this->taskDetail = new TaskListDetail();
        $this->oldSchedule = new OldSchedule();
        $this->item = new DefectItem();
        $this->template = new DefectTemplate();
        $this->templateItem = new TemplateItem();
        $this->defect = new Defect();
        $this->defectGroup = new DefectGroup();
    }
    
    public function getCollection($table, $where = null)
    {
        $obj = $this->common->where($table, $where);
        return $obj;
    }

    public function save($table, $input, $addIgnore = [], $pk = 'id', $saveID = false)
    {   
        $id = $input['id'];
        $type = $input['type'];
        switch ($type) {
            case 'add':
                $params = $this->common->params($input, $addIgnore, $saveID);
                $tran = $this->insert($table, $params);
                break;
            case 'edit':
                $params = $this->common->params($input, $addIgnore);
                $tran = $this->update($table, $id, $params, $pk);
                break;
            case 'delete':
                 $tran = $this->delete($table, $id, $pk);
                 break;
            default:
                throw new Exception('no action Exception');
        }
        return $tran;
    }

    public function insert($table, $params)
    {
        $obj = $this->common->insert($table, $params);
        return $obj;
    }

    public function update($table, $id, $params, $pk = 'id')
    {
        $timestamps = $table->timestamps;
        $table = $table->where($pk, $id);
        $obj = $this->common->update($table, $params, $timestamps);
        return $obj;
    }

    public function delete($table, $id, $pk = 'id')
    {
        $table = $table->where($pk, $id);
        $obj = $this->common->delete($table);
        return $obj;
    }
    
    public function forceDelete($table, $id, $pk = 'id')
    {
        $table = $table->where($pk, $id);
        $obj = $table->forceDelete();
        return [
            'success' => true,
            'msg' => 'success'
        ];
    }
}