<?php
		include('../model/php_model.php');
		$or = new Orders();
		$member = new Member();
		$memberData = $member->getAllMember();
		$lg = new Loyal_Guest();
		$lg_data = $lg->getAllLoyalGuest();
		foreach($lg_data as $key => $value){
			$lg_id[] = $value['lgIdNum'];
		}
		foreach($memberData as $key => $value ){
			if($value['memIdNum'] != "" && !in_array($value['memIdNum'],$lg_id)){
				$url = "http://api.21-finance.com/api/old/".$value['memIdNum'];
				$content = @file_get_contents($url);
				$status =  (str_replace('"','',$content) == "True") ? 1:0;
				if($status){
					$lg->insert($value['memIdNum']);
				}
			}

		}

?>
