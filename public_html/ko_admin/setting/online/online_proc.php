<?
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";
$view_count = 0;
$ip = $REMOTE_ADDR;
if (!$reg_date) $reg_date = date("Y-m-d H:i:s");
$return_url = explode("?", $return_url);
$dir = $_SERVER['DOCUMENT_ROOT'] . "/upload/online_form/".$online_id."/";

foreach($dept_auth as $value){
	$dept_query .= $value."|";
}
$dept_query = substr($dept_query, 0, -1);
if($use_phone_r) $use_phone .= "|{$use_phone_r}";
if($use_email_r) $use_email .= "|{$use_email_r}";

if(!$use_password){
	$use_password = "N";
}

//============================== 수정 , 등록 공용 ============================== //


//배열에 차곡차곡
for($i = 1; $i <= 10; $i++){
	//값들
	if(${"variable_name_".$i}){
		${"variable_".$i} = ${"variable_name_".$i} ."|" . ${"variable_type_".$i} ."|" .${"required_".$i} ."|" . ${"variable_state_".$i} ."|" .${"variable_id_".$i}."|" .${"variable_view_".$i}."|".${"variable_search_".$i}."|".${"variable_type_option_".$i};
	} else {
		${"variable_".$i} = "";
	}

	//솔트

}



if($mode == "modify_proc"){

	//============================== 수정 ============================== //

	//if (isblank($online_id)) error("비정상적 접근");
	/* if (isblank($name)) error("이름을 입력해 주세요.");
	if (isblank($password)) error("비밀번호를 입력해 주세요.");
	if (isblank($title)) error("제목을 입력해 주세요.");

	//파일업로드
	for($i = 1; $i <= 3; $i++){
		if($_FILES["file_".$i][size] > 0){
			${'file_'.$i} = upload_file($dir, $_FILES["file_".$i][tmp_name], $_FILES["file_".$i][name]);
		}
		$add_query .= "file_".$i." =  '". ${'file_'.$i} . "',";
	}

	//파일 업로드
	if($_FILES[title_img][size] > 0){
		$title_img =  upload_file($dir, $_FILES[title_img][tmp_name], $_FILES[title_img][name]);
		$add_query .= "title_img='$title_img',";
	}

	foreach($category as $value){
		$category_tmp .= $value ."|";
	}
	$category = substr($category_tmp, 0, -1);
	*/

		/*
	for($i = 0; $i < count($variable_name); $i++){
		$function = mysql_query("UPDATE koweb_online_variable SET ref_no = '$ref_no', variable_name = '$variable_name[$i]', variable_type='$variable_type[$i]', required='$required', state='Y', order='$order', view_order='$view_order', order='$order' WHERE no='$no'");
	}

*/


	//업데이트
	$update_query = "UPDATE koweb_online_config SET id = '$id',
											title = '$title',
											variable_count = '$variable_count',
											use_phone = '$use_phone',
											use_addr = '$use_addr',
											use_certi = '$use_certi',
											use_file = '$use_file',
											use_file_count = '$use_file_count',
											use_email = '$use_email',
											use_access = '$use_access',
											use_member = '$use_member',
											use_private_agree = '$use_private_agree',
											use_view = '$use_view',
											use_password='$use_password',
											spam_auth ='$spam_auth',
											private_text = '$private_text',
											variable_1 = '$variable_1',
											variable_2 = '$variable_2',
											variable_3 = '$variable_3',
											variable_4 = '$variable_4',
											variable_5 = '$variable_5',
											variable_6 = '$variable_6',
											variable_7 = '$variable_7',
											variable_8 = '$variable_8',
											variable_9 = '$variable_9',
											variable_10 = '$variable_10',
											dept_auth = '$dept_query',
											sort = '$sort'
										WHERE no='$no' LIMIT 1";


	$result = mysql_query($update_query);

	alert("수정되었습니다.");
	//url($return_url[0]."?online_id=$online_id&amp;mode=list&amp;no=$no&amp;start=$start");

	//============================== 수정 끝 ============================== //

} else if($mode == "delete"){

	//============================== 삭제 ============================== //

	$delete_query = "DELETE FROM koweb_online_config WHERE no='$no'";
	$result = mysql_query($delete_query);
	alert("삭제되었습니다.");
	url($return_url[0]."?online_id=$online_id");

	//============================== 삭제 끝 ============================== //

} else {

	//============================== 등록 ============================== //
	//if (isblank($online_id)) error("비정상적 접근");
	//if (isblank($title)) error("사이트 제목을 입력해 주세요.");
	//if (isblank($category)) error("구분을 하나이상 작성해주세요.");
	//if (isblank($link_url)) error("사이트 주소를 입력해 주세요.");
	//if (isblank($_FILES[title_img][size] <= 0)) error("대표이미지를 등록해 주세요.");
	/*
	//파일 업로드
	for($i = 1; $i <= 12; $i++){
		if($_FILES["file_".$i][size] > 0){
			${'file_'.$i} = upload_file($dir, $_FILES["file_".$i][tmp_name], $_FILES["file_".$i][name]);
		}
	}

	//파일 업로드
	$title_img =  upload_file($dir, $_FILES[title_img][tmp_name], $_FILES[title_img][name]);

	foreach($category as $value){
		$category_tmp .= $value ."|";
	}

	$category = substr($category_tmp, 0, -1);
	*/

	$id = "koweb_online_".$id;

	// 프로그램 ID 중복 체크
	$check = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM koweb_online_config WHERE id='$id'"));
	if ($check[0])	error("중복된 프로그램 ID 입니다.");

	// 관리자 폴더 생성
	mkdir($_SERVER[DOCUMENT_ROOT] . "/ko_admin/online/" . $id);
	chmod($_SERVER[DOCUMENT_ROOT] . "/ko_admin/online/" . $id, 0711);
	mkdir($_SERVER[DOCUMENT_ROOT] . "/upload/online/" . $id);
	chmod($_SERVER[DOCUMENT_ROOT] . "/upload/online/" . $id, 0711);

	mysql_query("INSERT INTO koweb_online_config VALUES ('', '$id', '$title', '$variable_count', '$use_phone', '$use_addr','$use_certi','$use_file', '$use_file_count', '$use_email', '$use_access', '$use_member', '$use_private_agree','$use_view', '$use_password', '$spam_auth', '$private_text', '$variable_1', '$variable_2', '$variable_3', '$variable_4', '$variable_5', '$variable_6', '$variable_7', '$variable_8', '$variable_9', '$variable_10', '$dept_query', '$sort')");

	$ref_no = mysql_insert_id();

	 //스키마
	$schema = "CREATE TABLE `$id` (
	  `no` int(11) NOT NULL AUTO_INCREMENT,
	  `id` varchar(50) DEFAULT '',
	  `CI` varchar(255) DEFAULT '',
	  `DI` varchar(255) DEFAULT '',
	  `name` varchar(50) NOT NULL,
	  `password` varchar(255) DEFAULT '',
	  `phone` varchar(50) DEFAULT '',
	  `email` varchar(50) DEFAULT '',
	  `zip` varchar(10) DEFAULT '',
	  `address1` varchar(255) DEFAULT '',
	  `address2` varchar(255) DEFAULT '',
	  `variable_1` text(0) DEFAULT '',
	  `variable_2` text(0) DEFAULT '',
	  `variable_3` text(0) DEFAULT '',
	  `variable_4` text(0) DEFAULT '',
	  `variable_5` text(0) DEFAULT '',
	  `variable_6` text(0) DEFAULT '',
	  `variable_7` text(0) DEFAULT '',
	  `variable_8` text(0) DEFAULT '',
	  `variable_9` text(0) DEFAULT '',
	  `variable_10` text(0) DEFAULT '',
	  `secret` char(1) DEFAULT '',
	  `reg_date` varchar(20) NOT NULL,
	  `ip` varchar(20) NOT NULL,
	  PRIMARY KEY (`no`),
	  KEY `name` (`name`)
	);";

	/*----------------------------------------------------------------------------*/
	// 작업
	/*----------------------------------------------------------------------------*/
	// 생성
	@mysql_query($schema);


	copy($_SERVER['DOCUMENT_ROOT']."/ko_admin/online/form.html", $_SERVER[DOCUMENT_ROOT] . "/ko_admin/online/" . $id . "/form.html");
	copy($_SERVER['DOCUMENT_ROOT']."/ko_admin/online/list.html", $_SERVER[DOCUMENT_ROOT] . "/ko_admin/online/" . $id . "/list.html");
	copy($_SERVER['DOCUMENT_ROOT']."/ko_admin/online/view.html", $_SERVER[DOCUMENT_ROOT] . "/ko_admin/online/" . $id . "/view.html");
	copy($_SERVER['DOCUMENT_ROOT']."/ko_admin/online/proc.php", $_SERVER[DOCUMENT_ROOT] . "/ko_admin/online/" . $id . "/proc.php");
	copy($_SERVER['DOCUMENT_ROOT']."/ko_admin/online/user_list.html", $_SERVER[DOCUMENT_ROOT] . "/ko_admin/online/" . $id . "/user_list.html");
	copy($_SERVER['DOCUMENT_ROOT']."/ko_admin/online/user_view.html", $_SERVER[DOCUMENT_ROOT] . "/ko_admin/online/" . $id . "/user_view.html");

	alert("등록되었습니다.");
	//url($return_url[0]."?online_id=$online_id");

	//============================== 등록 끝 ============================== //
}

?>

<script type="text/javascript">
location.href = "<?=$PHP_SELF?>?type=<?=$type?>&core=<?=$core?>&manager_type=<?=$manager_type?>";
</script>
