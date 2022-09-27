<?
	include_once $_SERVER['DOCUMENT_ROOT'] . "/head.php";
	//$setting_table = "koweb_".$database."_config";

	$tmp = explode("|", $sort_data);
	$count = 1;
	if($type == "online"){
		$update_ = mysql_query("UPDATE $database SET sort='$sort_data' WHERE no='$no'");
	}else{
		foreach($tmp as $v){
			$update_ = mysql_query("UPDATE $database SET sort='$count' WHERE no='$v'");
			if($update_){
				echo "UPDATE $database SET sort='$count' WHERE no='$v'";
			}
			$count++;
		}
	}
?>
