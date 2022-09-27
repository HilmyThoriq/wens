<?
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

$view_count = 0;
$ip = $REMOTE_ADDR;
if (!$reg_date) $reg_date = date("Y-m-d H:i:s");
$return_url = explode("?", $return_url);
$dir = $_SERVER['DOCUMENT_ROOT'] . "/upload/reservation/".$serve_name."/";
/*
// 관리자 페이지 체크
if ($PHP_SELF ==  "/koweb_manager/program/manager_program.php" || $return_url[0] == "/koweb_manager/program/manager_program.php") {
	$is_admin = true;
} else {
	$is_admin = false;
}
*/
$query = "SELECT * FROM koweb_reservation_config limit 0,1";
$result = mysql_query($query);
$row = mysql_fetch_array($result);
$temp = explode("|",$id);
$no = $temp['0'];
$id = $temp['1'];
$escape_field = array("online_config_no" , "online_config_id");
$online_query = "SELECT * FROM koweb_online_config WHERE no = '$no'";
$online_result = mysql_query($online_query);
$online = mysql_fetch_array($online_result);
$online_config_id = $online['id'];

$receive_phone_number = $receive_phone_number1."-".$receive_phone_number2."-".$receive_phone_number3;
$auto_make_query = make_set_query_str("koweb_reservation_config",$escape_field);

if(!$row){
	$update_query = "INSERT INTO koweb_reservation_config SET online_config_no='{$no}' ,online_config_id='{$online_config_id}', {$auto_make_query}";
	mysql_query($update_query);
}else{
	$update_query = "UPDATE koweb_reservation_config SET online_config_no='{$no}',online_config_id='{$online_config_id}',{$auto_make_query} LIMIT 1";
	mysql_query($update_query);
}
alert("수정되었습니다.");
url($common_url."&amp;mode=modify");
