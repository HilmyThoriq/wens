<?
//include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

$ip = $REMOTE_ADDR;
if (!$reg_date) $reg_date = date("Y-m-d H:i:s");
$return_url = explode("?", $return_url);
$dir = $_SERVER['DOCUMENT_ROOT'] . "/upload/online/".$online_id."/";
$phone = $phone1."-".$phone2."-".$phone3;
if($email2) $email3 = $email2;
$email = $email1."@".$email3;

/*
// 관리자 페이지 체크
if ($PHP_SELF ==  "/koweb_manager/site/manager_site.php" || $return_url[0] == "/koweb_manager/site/manager_site.php") {
	$is_admin = true;
} else {
	$is_admin = false;
}
*/
if($online['use_certi'] == "Y" && $site['use_sms']=="Y" && !empty($site['sms_id']) && !empty($site['sms_key']) ){
	if($_SESSION['auth_phone'] != $phone1."-".$phone2."-".$phone3) error("휴대폰인증을 다시 진행해주세요");
	if($_SESSION['auth_sms'] != $sms_auth) error("휴대폰인증을 다시 진행해주세요");
}

if($mode == "modify_proc"){

	//============================== 수정 ============================== //

	if (isblank($online_id)) error("비정상적 접근");
	if ($online[spam_auth] == "Y"){

		if (isblank($_SESSION[rand_auth])) error("비정상적 접근");

		if($rand_auth_ != $_SESSION[rand_auth]){			
			error("스팸방지번호가 올바르지 않습니다.");
			exit;
		}
	}

	if($online['use_password'] == "Y"){
		if(!$password) error("패스워드를 입력해주세요");
		$password = hash("sha256", $password);
	}

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
		if(${"variable_type_".$i} == "checkbox"){
			${"variable_".$i} = join("^",${"variable_".$i});
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
										WHERE no='$no' LIMIT 1";

	$result = mysql_query($update_query);

	alert("수정되었습니다.");
	url($return_url[0]."?online_id=$online_id&amp;mode=list&amp;no=$no&amp;start=$start");

	//============================== 수정 끝 ============================== //

} else if($mode == "delete"){

	//============================== 삭제 ============================== //
	//관리자가 아님

	if(!$_SESSION[is_admin]){
		$query = "SELECT * FROM $online_table WHERE no='$no'";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		
		//비회원 쓰기 가능일때
		if($online['use_member'] == "10"){
			if($online['use_password'] == "Y"){
				if($password == "" ){
					url("$PHP_SELF?online_id=$online_id&amp;start=$start&amp;mode=check&amp;return_mode=$mode&amp;no=$row[no]");
					exit;
				}
				$password = hash("sha256", $password);
				if($row[password] != $password){
					alert("입력하신 비밀번호가 다릅니다.");
					url("$PHP_SELF?online_id=$online_id&amp;start=$start&amp;mode=check&amp;return_mode=$mode&amp;no=$row[no]");
					exit;
				}
			}
		//회원부터 가능일때
		} else {
			if($row[id] != $_SESSION[member_id]){
				error("작성하신 게시글은 작성자 또는 관리자만 삭제가능 합니다.");
				exit;
			}
		}
	}


	$delete_query = "DELETE FROM $online_table WHERE no='$no'";
	$result = mysql_query($delete_query);
	alert("삭제되었습니다.");
	url($return_url[0]."?online_id=$online_id");

	//============================== 삭제 끝 ============================== //

} else {

	//============================== 등록 ============================== //
	if (isblank($online_id)) error("비정상적 접근");

		
	if($online['use_password'] == "Y"){
		if(!$password) error("패스워드를 입력해주세요");
		$password = hash("sha256", $password);
	}

	if ($online[spam_auth] == "Y"){

		if (isblank($_SESSION[rand_auth])) error("비정상적 접근");

		if($rand_auth_ != $_SESSION[rand_auth]){			
			error("스팸방지번호가 올바르지 않습니다.");
			exit;
		}
	}


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
		if(${"variable_type_".$i} == "checkbox"){
			${"variable_".$i} = join("^",${"variable_".$i});
		}
		$add_query .=  ", '".${"variable_".$i}."'";
	}

	mysql_query("INSERT INTO $online_table VALUES ('', '$_SESSION[member_id]', '$_SESSION[member_ci]', '$_SESSION[member_di]', '$_SESSION[member_name]', '$password', '$phone', '$email', '$zip', '$address1', '$address2' $add_query , '', '$reg_date', '$ip')");

	alert("등록되었습니다.");
	url($return_url[0]."?online_id=$online_id");

	//============================== 등록 끝 ============================== //
}
