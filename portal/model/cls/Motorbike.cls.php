<?php
    class Motorbike {
        var $db;
        
        //建構函式
        public function Motorbike() {
            $this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
            return true;
        }
        
        public function getAllMbName() {
            $sql = "select
                        `mbName`
                    from
                        `motorbike_brand`;";
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        public function getAllMcData() {
            $sql = "select
                        *
                    from
                        `motorbike_cc`;";
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        public function getYearDiffAndMaxAmountbyMcNo($mcNo) {

            $sql = "select 
                        `mmaYearDiff`, `mmaAmount`
                    from
                        `motorbike_max_amount`
                    where
                        `mcNo` = " . $mcNo . 

                    " order by `mcNo`, `mmaYearDiff` asc;";
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        public function getAllMonthlyBasisMin() {

            $sql = "select
                        `mbMin`
                    from
                        `monthly_basis` as mb
                    inner join
                        `loan_periods` as lp
                    on (mb.lpNo = lp.lpNo)
                    order by `mbNo` asc;";
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        public function getSalesUserNum() {//jimmy

            $sql = "select 
                        `aauNo`, `aauName`
                    from  
                        `admin_advanced_user` 
                    where 
                        `aarNo` like '%15%'";
            $data = $this->db->selectRecords($sql);
            return $data;
        }
    }
?>