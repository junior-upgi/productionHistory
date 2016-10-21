<?php
/**
 * 前端頁面基本處理方法
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 16/10/21
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Presenters;

/**
 * Class WebBasepresenter
 *
 * @package App\Presenters
 */
class WebBasePresenter
{

    /**
     * 建構式
     *
     * @return void
     */
    public function __construct() {

    }

    /**
    * 轉換日期格式
    * 
    * @return string 回傳string 日期
    */
    public function getDate($date)
    {
        if (isset($date)) {
            return date('Y-m-d', strtotime($date));
        }
        return '';
    }

    public function getFormLink($o, $type)
    {
        $OS_NO = $o->OS_NO;
        $ITM = $o->ITM;
        $COMB_ITEM_NAME = str_replace("#", "%23", $o->COMB_ITEM_NAME);
        $UNIT = $o->UNIT;
        switch ($type) {
            case 0:
                //基本資料
                $url = "https://docs.google.com/forms/d/e/1FAIpQLSehNS4idn8yHXa2IPNzu5kNwNGE2aJbvHoTw3mS4OcpaxB99w/viewform?entry.1932040804=$OS_NO&entry.1999602853=$ITM&entry.1518767994=$COMB_ITEM_NAME&entry.1336051967&entry.1089230296&entry.1706891687";
                break;

            case 1:
                //設備材料
                $url = "https://docs.google.com/forms/d/e/1FAIpQLSd_VfqjrXDVmS7QiwJMB9AjttrYIora9438j4mxtTW7fWPxfA/viewform?entry.577854762=$OS_NO&entry.371094610=$ITM&entry.866667811=$COMB_ITEM_NAME&entry.1281753251=$UNIT&entry.1765344146&entry.1771398894";
                break;
                
            case 2:
                //製程條件
                $url ="https://docs.google.com/forms/d/e/1FAIpQLScBQf7LugzQ6zabTvMrxIqIPLgprDRX7yi9DyX0PkxaeIzSpw/viewform?entry.2073060511=$OS_NO&entry.1347295307=$ITM&entry.228686488=$COMB_ITEM_NAME&entry.653745443=$UNIT&entry.1900150380&entry.302003372";
                break;

            case 3:
                //管制要求
                $url ="https://docs.google.com/forms/d/e/1FAIpQLSc3tluppaQ8QUVDETH1YpQg4EcH9rIt693TVBFtGlrk20dt5A/viewform?entry.1051238327=$OS_NO&entry.439906299=$ITM&entry.595725591=$COMB_ITEM_NAME&entry.13192576=$UNIT&entry.1184824552&entry.1488671416";
                break;
                
            case 4:
                //問題缺點
                $url ="https://docs.google.com/forms/d/e/1FAIpQLScF4PfOP4CmFo9jBWZvR9oYKY1q0u8yaBH9NqaaqptoxRSfcQ/viewform?entry.1803405858=$OS_NO&entry.163619270=$ITM&entry.1593599709=$COMB_ITEM_NAME&entry.1344506444=$UNIT&entry.1778061586&entry.1209850397&entry.654197155&entry.268453642&entry.297244570";
                break;
            
            case 5:
                //生產狀況
                $url ="https://docs.google.com/forms/d/e/1FAIpQLSc_Bh-AWzayzds8vCMwH-_hP7gN9TbVLGOa7lYuf9ErBtan1w/viewform?entry.1105292631=$OS_NO&entry.1350121442=$ITM&entry.1885281696=$COMB_ITEM_NAME&entry.587605203=$UNIT&entry.1213200485&entry.1714776233&entry.1709772504";
                break;
        }
        
        return $url;
    }
}