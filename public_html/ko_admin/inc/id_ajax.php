<?
/*----------------------------------------------------------------------------*/
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";  
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";  
/*----------------------------------------------------------------------------*/

$total = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM $variable WHERE id='$variable_data'"));

if(!$total[0]){
	echo "사용 가능한 ID 입니다.";
} else {
	echo "사용 불가능한 ID 입니다.";
}
?>
