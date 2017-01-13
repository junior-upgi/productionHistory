<?php

/*
    public function importToday($data)
    {
        $table = $this->excel->getTableArray($data, 1);
        $ref = ['線別', '瓶號', '重量', '機速', '引出量', '下支瓶號', '預計換模時間', '試模瓶號', null];
        if ($table[0] != $ref) {
            return ['success' => false, '上傳檔案㯗位格式錯誤!'];
        }
        try {
            $this->today->getConnection()->beginTransaction();
            foreach ($table as $item) {
                $params = [
                    'line' => $item[0],
                    'glassNumber' => $item[1],
                    'weight' => $item[2],
                    'speed' => $item[3],
                    'quantity' => $item[4],
                    'nextNumber' => $item[5],
                    'change' => $item[6],
                    'testNumber' => $item[7],
                    'date' => $this->carbon->today(),
                    'created_at' => $this->carbon->now()
                ];
                $this->today->insert($params);
            }
            $this->today->getConnection()->commit();
            return ['success' => true];
        } catch (\Exception $e) {
            $this->today->getConnection()->rollback();
            return ['success' => false, $e->getMessage()];
        }
    }
    */