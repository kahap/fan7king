<?php
    class Motorbike_Cellphone_Orders{
        var $db;
        var $statusArr = array(
            0 => "已下單，Email未驗證",
            110 => "未完成下單",
            1 => "審查中",
            2 => "審查中",
            3 => "審核通過",
            4 => "審核未通過",
            5 => "資料不全需補件",
            6 => "審查中",
            7 => "取消訂單",
            701 => "取消訂單",
            8 => "出貨中",
            9 => "已到貨",
            10 => "我要繳款",
            11 => "換貨中",
            12 => "退貨中",
            13 => "完成退貨"
        );
        var $statusDirectArr = array(
            0 => "處理中",
            1 => "取消訂單",
            2 => "出貨中",
            3 => "已收貨",
            4 => "已完成",
            5 => "換貨中",
            6 => "退貨中",
            7 => "完成退貨"
        );
        //補件原因
        var $reasonArr = array(
            0 => "無",
            1 => "自訂",
            3 => "請重新上傳清楚不反光及不切字的【身分證】正反面，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            4 => "請重新上傳清楚不反光及不切字的【學生證】正反面，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            5 => "請重新補上第二步驟之正楷中文簽名，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            6 => "請補上一親一友之姓名及市內電話資料，留言1.親屬:姓名、關係、手機、市內電話；2.朋友:姓名、手機，麻煩用LINE傳給我們，如有疑問請洽LINE客服，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            7 => "請補上【軍人證】正反面影本，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            8 => "請重新上傳提供最新補換發【身分證】正反面影本，補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            9 => "請您補上財力證明(近半年存摺[薪轉更加分])，有帳號的封面拍一張，若無帳號之存摺封面請再補拍帳號那一面一張，存摺內頁麻煩刷到最新，內頁上下兩頁合拍在一起(從今天往回推提供近6個月)不要反光不要模糊不要切邊之多張照片上傳用LINE傳遞。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            10 => "請您確認戶籍地址、收貨地址以及現住地址是否完整，並補上正確資料。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            11 => "請您補上勞保異動明細表。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            12 => "請您登入學校網站需要有您學校名稱、姓名、學號的畫面，麻煩截圖上傳LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            13 => "請您提供一位法定代理人(父or母)當保證人(盡量有市內電話以及工作滿半年以上較佳)。您需先加入客服LINE索取保人申請書並詢問填寫及回覆方式，並上傳保人身分證正反面影本到LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            14 => "請您提供一位正職保證人(盡量有市內電話以及工作滿半年以上較佳)。您需先加入LINE客服索取保人申請書並詢問填寫及回覆方式，還要上傳保人身分證正反面影本一併傳到LINE客服。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件",
            15 => "請您補上可接照會電話時段。如審查時段早上9點~12點，下午13點~18點。您本人可接電話時段:ex:10-14點；您的聯絡人可接電話時段:ex:13-18點；您公司內有人可接照會電話時段：ex:9-12點；保人可接電話時段：ex:9-18點(若無告知補保人則免填)。補件後請務必加入客服LINE ID：@happyfan7告知您已補資料及留下您的大名，以便加速審核您的案件");
        
        //建構函式
        public function Motorbike_Cellphone_Orders(){
            $this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
            return TRUE;
        }
        
        //編號取得單一訂單
        public function getOneOrderByNo($mcoNo) {
            $sql = "select
                        *
                    from
                        `motorbike_cellphone_orders`
                    where
                        `mcoNo`='" . $mcoNo . "'";
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        //根據會員取得一筆訂單
        public function getTwinOrByMemberAndMethod($memNo) {
            $sql = "select
                        *
                    from
                        `motorbike_cellphone_orders`
                    where
                        `memNo`=" . $memNo . "
                    order by `mcoDate` desc
                    limit 2";
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        //編輯處理狀態 1 已處理 0 未處理
        public function updateMcoIfProcess($mcoIfProcessInCurrentStatus,$mcoNo){
            $sql = "update
                        `motorbike_cellphone_orders`
                    set
                        `mcoIfProcessInCurrentStatus`= '".$mcoIfProcessInCurrentStatus."'
                    where
                        `mcoNo` = '".$mcoNo."'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //根據會員完成的訂單
        public function getDealFinish($memNo) {
            $sql = "select
                        *
                    from
                        `motorbike_cellphone_orders`
                    where
                        `memNo`=" . $memNo . "
                    and
                        `mcoIfEditable` = '1' 
                    order by `mcoDate` desc
                    limit 1";
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        //mcoRecentTransactionImgTop 近6個月薪資往來
        public function updateMcoRecentTransactionImgTop($mcoRecentTransactionImgTop, $mcoNo) {
            
            $sql = "update `motorbike_cellphone_orders` set `mcoRecentTransactionImgTop` ='" . $mcoRecentTransactionImgTop . "' where `mcoNo`='" . $mcoNo . "'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //mcoBankBookImgTop 存摺正面
        public function updateMcoBankBookImgTop($mcoBankBookImgTop, $mcoNo) {
                
            $sql = "update `motorbike_cellphone_orders` set `mcoBankBookImgTop` ='" . $mcoBankBookImgTop . "' where `mcoNo`='" . $mcoNo . "'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //mcoCarIdImgTop 行照正面
        public function updateMcoCarIdImgTop($mcoCarIdImgTop, $mcoNo) {
                
            $sql = "update `motorbike_cellphone_orders` set `mcoCarIdImgTop` ='" . $mcoCarIdImgTop . "' where `mcoNo`='" . $mcoNo . "'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //mcoIdImgTop 身分證正面
        public function updateMcoIdImgTop($mcoIdImgTop, $mcoNo) {
                
            $sql = "update `motorbike_cellphone_orders` set `mcoIdImgTop` ='" . $mcoIdImgTop . "' where `mcoNo`='" . $mcoNo . "'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //mcoIdImgBot 身分證反面
        public function updateMcoIdImgBot($mcoIdImgBot, $mcoNo) {
                
            $sql = "update `motorbike_cellphone_orders` set `mcoIdImgBot` ='" . $mcoIdImgBot . "' where `mcoNo`='" . $mcoNo . "'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //mcoStudentIdImgTop 學生證正面
        public function updateMcoStudentIdImgTop($mcoStudentIdImgTop, $mcoNo) {
                
            $sql = "update `motorbike_cellphone_orders` set `mcoStudentIdImgTop` ='" . $mcoStudentIdImgTop . "' where `mcoNo`='" . $mcoNo . "'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //mcoStudentIdImgBot 學生證反面
        public function updateMcoStudentIdImgBot($mcoStudentIdImgBot, $mcoNo) {
                
            $sql = "update `motorbike_cellphone_orders` set `mcoStudentIdImgBot` ='" . $mcoStudentIdImgBot . "' where `mcoNo`='" . $mcoNo . "'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //mcoSubIdImgTop 健保卡正面/駕照
        public function updateMcoSubIdImgTop($mcoSubIdImgTop, $mcoNo) {
                
            $sql = "update `motorbike_cellphone_orders` set `mcoSubIdImgTop` ='" . $mcoSubIdImgTop . "' where `mcoNo`='" . $mcoNo . "'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //mcoExtraInfoUpload 補件資料上傳
        public function updateMcoExtraInfoUpload($mcoExtraInfoUpload, $mcoNo) {
                
            $sql = "update `motorbike_cellphone_orders` set `mcoExtraInfoUpload` ='" . $mcoExtraInfoUpload . "' where `mcoNo`='" . $mcoNo . "'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //更新身分證內容
        public function updateForId($array, $mcoNo) {
            foreach ($array as $key => $value){
                $str .= $key . "='" . $value . "',";
            }
            $sql = "update
                        `motorbike_cellphone_orders`
                    set
                        " . substr($str, 0, -1) . "                       
                    where
                        `mcoNo`='" . $mcoNo . "'";
                
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //編輯進件狀態
        public function updateStatus($mcoStatus, $mcoNo) {
            $sql = "update
                        `motorbike_cellphone_orders`
                    set
                        `mcoStatus`='" . $mcoStatus . "'
                    where
                        `mcoNo`='" . $mcoNo . "'";
            
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //編輯進件時間
        public function updateStatusTime($whichStat, $mcoNo) {
            date_default_timezone_set('Asia/Taipei');
            $date = date('Y-m-d H:i:s', time());
            $sql = "update
                        `motorbike_cellphone_orders`
                    set
                        `orReportPeriod" . $whichStat . "Date`='" . $date . "'
                    where
                        `mcoNo`='" . $mcoNo . "'";
        
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        public function updateMcoIfEditable($mcoIfEditable, $mcoNo) {
            $sql = "update
                        `motorbike_cellphone_orders`
                    set
                        `mcoIfEditable`='" . $mcoIfEditable . "'
                    where
                        `mcoNo`='" . $mcoNo . "'";
                
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //根據會員取得訂單編號
        public function getmcoNoByMember($memNo) {
            $sql = "select
                        `mcoNo`
                    from
                        `motorbike_cellphone_orders`
                    where
                        `memNo`= " . $memNo . "
                    order by mcoDate desc";
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        //根據會員取得訂單
        public function getmcoByMember($memNo) {
            $sql = "select
                        *
                    from
                        `motorbike_cellphone_orders`
                    where
                        `memNo`= " . $memNo . "
                    order by mcoDate desc";
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        public function updateorMcoIfProcessInCurrentStatus($mcoIfProcessInCurrentStatus, $mcoNo) {
            $sql = "update
                        `motorbike_cellphone_orders`
                    set
                        `mcoIfProcessInCurrentStatus`='" . $mcoIfProcessInCurrentStatus . "'
                    where
                        `mcoNo`='" . $mcoNo . "'";
                
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //取得欄位中文名
        public function getAllColumnNames($tableName){
            $sql = "select
                        COLUMN_COMMENT, COLUMN_NAME 
                    from
                        information_schema.COLUMNS 
                    WHERE 
                        TABLE_SCHEMA = '".SYSTEM_DBNAME."' 
                    AND 
                        TABLE_NAME = '".$tableName."'";
            $data = $this->db->selectRecords($sql);
            return $data;
        }


        public function status($key){
            return $this->statusArr[$key];
        }
        
    }
?>