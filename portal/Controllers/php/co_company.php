<?php
	include('../model/php_model.php');
	$co_company = new co_company();
	if(!empty($_POST)){
		foreach($_POST as $key =>$value){
			$state[] = ($value != "") ? 1:0;
		}
			if(!in_array('0',$state)){
				$co_company->inser_co_company($_POST);
				alert_message('../index.php?item=co_company','新增成功,已通知管理員');
			}else{
				echo "<script>alert('請填寫完整')</script>";
				echo "<script>history.go(-1)</script>";
			}
	}else{
		alert_message('../index.php?item=co_company','請填寫完整');
	}
?>