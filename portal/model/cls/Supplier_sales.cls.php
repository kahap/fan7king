<?php
    class Supplier_sales{
        var $db;

        //建構函式
        public function Supplier_sales(){
            $this->db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
            return TRUE;
        }

        //判斷是否為經銷商業務人員
        public function getSupplier_sales_information($ssLogId,$ssPwd){
            $sql = "select
                        *
                    from
                        `supplier`
                    where
                        `supLogId`='".$ssLogId."'&&
                        `supPwd` = '".$ssPwd."'
                    ";
            $data = $this->db->selectRecords($sql);
            return $data[0];
        }

        public function getSupplier_sales($supNo,$ssLogId,$ssPwd){
            $sql = "select
                        *
                    from
                        `supplier_sales`
                    where
                        `supNo`='".$supNo."'&&
                        `ssLogId`='".$ssLogId."'&&
                        `ssPwd` = '".$ssPwd."'
                    ";
            $data = $this->db->selectRecords($sql);
            return $data[0];
        }

        //編號取得單一供應商 encore
        public function getOneSupplier_salesByNo($supNo){
            $sql = "select
                        *
                    from
                        `supplier_sales`
                    where
                        `supNo`=".$supNo;
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        //編號取得單一業務員人員 encore
        public function getOneSupplier_salesByssNo($ssNo){
            $sql = "select
                        *
                    from
                        `supplier_sales`
                    where
                        `ssNo`=".$ssNo;
            $data = $this->db->selectRecords($sql);
            return $data;
        }

        //編輯 encore
        public function update($array,$ssNo){
            foreach($array as $key =>$value){
                $$key = mysqli_real_escape_string($this->db->oDbLink, $value);
            }
            $sql = "update
                        `supplier_sales`
                    set
                        `ssName`='".$ssName."',
                        `ssLogId`='".$ssLogId."',
                        `ssPwd`='".$ssPwd."'
                    where
                        `ssNo`=".$ssNo;
            
            $update = $this->db->updateRecords($sql);
            return $update;
        }

        //新增 encore
        function insert($array,$supNo){
            foreach($array as $key =>$value){
                $$key = mysqli_real_escape_string($this->db->oDbLink, $value);
            }
            $sql = "insert into `supplier_sales`(`supNo`, `ssName` ,`ssLogId` ,`ssPwd`)
                    values('".$supNo."',
                            '".$ssName."',
                            '".$ssLogId."',
                            '".$ssPwd."')";
            $insert = $this->db->insertRecords($sql);
            return $insert;
        }

        //刪除 encore
        function delete($ssNo){
            $sql = "delete from `supplier_sales` where `ssNo` = ".$ssNo;
            $delete = $this->db->deleteRecords($sql);
            return $delete;
        }
    }
?>