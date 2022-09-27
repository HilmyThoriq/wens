<? 
include_once $_SERVER['DOCUMENT_ROOT'] . "/head.php";
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

$ip = $_SERVER[REMOTE_ADDR];
if (!$reg_date) $reg_date = date("Y-m-d H:i:s");
$program_table = "koweb_mail";
if($mode == "checked"){

	//============================== 수정 ============================== //

	//파일 업로드
	if($_FILES[title_img][size] > 0){
		$title_img =  upload_file($dir, $_FILES[title_img][tmp_name], $_FILES[title_img][name]);
		$add_query .= "img='$title_img',";
	}

	$update_query = "UPDATE $program_table SET title = '$title',
											type = '$popup_type',
											contents = '$contents',
											link_url = '$link_url', 
											link_type = '$link_type',
											start_date = '$start_date', 
											$add_query
											end_date = '$end_date',
											state = '$state',
											width='$width', 
											height = '$height',
											position_width='$position_width',
											position_height='$position_height',
											zindex='$zindex',
											reg_date='$reg_date'
										WHERE no='$no' LIMIT 1";

	$result = mysql_query($update_query);

	alert("수정되었습니다.");
	url($return_url);

	//============================== 수정 끝 ============================== //

} else {

	//============================== 등록 ============================== //
	$recive = $_POST['recive'];

	foreach($recive  AS $rc){
		$code = rand().date("Ymdhis").rand();
		$contents = "<div style='margin:25px;'><img src='http://combine.daeguweb.gethompy.com/ko_admin/program/koweb_mail/resame.php?code=$code'></div>".$_POST[contents];

		$html_contents = sanitizeString($contents);

		$member_ = mysql_fetch_array(mysql_query("SELECT * FROM koweb_member WHERE id='$rc'"));
		$ref_member = $member_[id];
		$send = $_SESSION[member_id];
		$recive = $member_[id];
		$ref_mail = $member_[email];

		mysql_query("INSERT $program_table SET code = '$code',
												ref_member = '$ref_member',
												ref_mail = '$ref_mail',
												send = '$send', 
												recive = '$recive',
												title = '$title', 
												contents = '$html_contents',
												send_state = '$send_state',
												read_state='N', 
												ip = '$ip',
												reg_date='$reg_date'");
		
		@send_Mail_file($send, $_SESSION[member_mail], $rc, $ref_mail, $title, $contents, $dir="", $file=false);

	}

	alert("발송되었습니다");
	//============================== 등록 끝 ============================== //
}


?>

<script type="text/javascript">
	self.close();
</script>
