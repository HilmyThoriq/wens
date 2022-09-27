<?
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";
$view_count = 0;
$ip = $REMOTE_ADDR;
if (!$reg_date) $reg_date = date("Y-m-d H:i:s");
$return_url = explode("?", $return_url);
$dir = $_SERVER['DOCUMENT_ROOT'] . "/upload/reservation/".$serve_name."/";
$phone = $phone1."-".$phone2."-".$phone3;
if($email2) $email3 = $email2;
$email = $email1."@".$email3;

$start_days_ = explode("-",$start_days);
$start_year = $start_days_['0'];
$start_month = $start_days_['1'];
$start_day = $start_days_['2'];
$start_month = str_pad($start_month,2,"0",STR_PAD_LEFT);
$start_day = str_pad($start_day,2,"0",STR_PAD_LEFT);

$end_days_ = explode("-",$end_days);
$end_year = $end_days_['0'];
$end_month = $end_days_['1'];
$end_day = $end_days_['2'];
$end_month = str_pad($end_month,2,"0",STR_PAD_LEFT);
$end_day = str_pad($end_day,2,"0",STR_PAD_LEFT);
/*
// 관리자 페이지 체크
if ($PHP_SELF ==  "/koweb_manager/program/manager_program.php" || $return_url[0] == "/koweb_manager/program/manager_program.php") {
	$is_admin = true;
} else {
	$is_admin = false;
}
*/

//해당예약 여부
$query = "SELECT * FROM koweb_reservation_date WHERE no='{$no}'";
$result = mysql_query($query);
$row = mysql_fetch_array($result);

$online_table = empty($row['input_id']) ? $board['online_config_id'] : $row['input_id'];

if($mode == "modify_proc"){

	//============================== 수정 ============================== //

	// if (isblank($online_id)) error("비정상적 접근");

	//변수 만들기
	$add_query = "";
	for($i = 1; $i <= 10; $i++){
		//변수명 생성
		$tmp_variable = "variable_".$i;

		if(${"variable_type_".$i} == "file"){
			if($_FILES[$tmp_variable][size] > 0){
				${"variable_".$i} = upload_file($dir, $_FILES[$tmp_variable][tmp_name], $_FILES[$tmp_variable][name]);
			}
		}
		$add_query .=  ", ".$tmp_variable." = '".${"variable_".$i}."'";
	}

	$update_query = "UPDATE $online_table SET
											password = '$password',
											phone = '$phone',
											email = '$email',
											zip = '$zip',
											address1 = '$address1',
											address2 = '$address2'
											$add_query,
											secret = '$secret',
											reg_date = '$reg_date',
											ip = '$ip'
										WHERE no='{$row['input_no']}' LIMIT 1";

	$result = mysql_query($update_query);

	$update_query = "UPDATE koweb_reservation_date SET
												start_date='{$start_year}{$start_month}{$start_day}',
												start_year='{$start_year}',
												start_month='{$start_month}',
												start_day='{$start_day}',
												end_date='{$end_year}{$end_month}{$end_day}',
												end_year='{$end_year}',
												end_month='{$end_month}',
												end_day='{$end_day}'
										WHERE no='{$no}' LIMIT 1";


	$result = mysql_query($update_query);


	alert("수정되었습니다.");
	url($return_url[0]."?type={$type}&core={$core}&manager_type={$manager_type}&detail={$detail}&year={$year}&month={$month}&mode=modify&no={$no}");

	//============================== 수정 끝 ============================== //


} else if($mode == "delete"){

	//============================== 삭제 ============================== //

	$delete_query = "DELETE FROM koweb_reservation_date WHERE no='$no'";
	$result = mysql_query($delete_query);
	alert("삭제되었습니다.");
	url($common_url);

	//============================== 삭제 끝 ============================== //

} else if($mode == "state_proc"){

	//============================== 상태변경 ============================== //
	if(!$_SESSION["ss_view_reservation_{$no}"]){
		alert("정상적인 경로로 접근해주세요.");
		url($common_url);
		exit;
	}
	unset($_SESSION["ss_view_reservation_{$no}"]);
	$accept_state = array("10,20,99"); //허용하는 키값
	if(in_array($state,$accept_state)){ alert("state값을 체크해주세요"); url($common_url); exit;}

	$update_query = "update koweb_reservation_date set state = '{$state}' where no='{$no}'";
	mysql_query($update_query);

	alert("수정되었습니다.");
	url($common_url);

	//============================== 상태변경 끝 ============================== //

}
