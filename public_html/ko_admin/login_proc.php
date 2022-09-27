<?
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";

if(!$admin_password || !$admin_id){
	alert_to_admin("아이디/패스워드를 작성해주세요");
	exit;
}

$id_enc = hash("sha256", $admin_id);
$password_enc = hash("sha256", $admin_password);
$auth_token = hash("sha256", $admin_id.$admin_password);

$query = "SELECT * FROM koweb_member WHERE id='$admin_id' AND id_enc = '$id_enc' AND password='$password_enc' LIMIT 1";
$result = mysql_query($query);
$check = mysql_num_rows($result);

if($check < 1){
	alert_to_admin("아이디/패스워드를 확인해주세요.");
	exit;
} else {
	$admin_row = mysql_fetch_array($result);
	$_SESSION['auth_admin_ci'] = $admin_row[id];
	$_SESSION['auth_admin_di'] = $admin_row[password];
	$_SESSION['auth_token'] = hash("sha256", $admin_id.$admin_password);
	$_SESSION['auth_level'] = $admin_row[auth_level];
	$_SESSION['member_id'] = $admin_row[id];
	$_SESSION['member_name'] = $admin_row[last_name]. ",". $admin_row[first_name];
	$_SESSION['member_first_name'] = $admin_row[first_name];
	$_SESSION['member_last_name'] = $admin_row[last_name];
	$_SESSION['member_mail'] = $admin_row[email];
	$_SESSION['is_admin'] = true;

	setHistory("관리자 로그인", "", "$_SESSION[member_id] 관리자 로그인");

	alert("로그인되었습니다.");
	echo "<script type='text/javascript'> parent.location.href='./index.html'; </script>";
}

?>
