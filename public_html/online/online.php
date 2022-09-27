<?
	$online_id = trim($online_id);
	if(strpos($online_id, "(") !== false || strpos($online_id, ")")  !== false || strpos($online_id, "%")  !== false){
		error("비정상적인 접근입니다.");
		exit;
	}

	//online 기본정보 불러오기
	//프로그램 통합 관리 DB 보면됨
	$online_query = "SELECT * FROM koweb_online_config WHERE id = '$online_id'";
	$online_result = mysql_query($online_query);
	$online = mysql_fetch_array($online_result);
	$online_table = $online[id];
	$online_file = $online[id];
	$online_title = $online[title];

	//online 기본 변수
	$http_host = $_SERVER['HTTP_HOST'];
	$request_uri = $_SERVER['REQUEST_URI'];
	$url = "http://" . $http_host . $request_uri;
	//include_once $_SERVER['DOCUMENT_ROOT'] . "/js/online.js";
	echo "<script type=\"text/javascript\" src=\"/js/online.js\"></script>";
//	include_once $_SERVER['DOCUMENT_ROOT'] . "/online/online_auth.php";

	if(!$online_id || $online[id] == ""){
		error("정상적인 경로로 접근하여 주세요.");
		exit;
	}

	$member = get_member();
	if(!$member) $member['auth_level'] = 10;
	if($online['use_member']<$member['auth_level']){
		if($member['auth_level'] == 10){
			alert("로그인이 필요한 서비스입니다.");
			url("/member/member.php?mode=login");
			exit;
		}else{
			if($online[use_member] == "3") error("정회원 이상만 신청 가능합니다.");
			else if($online[use_member] == "5") error("준회원 이상만 신청 가능합니다.");
			exit;
		}
	}


switch ($mode) {
	case "write" :
	case "modify" :
		//에디터 js 로 따로
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $online[title]'; </script>";
		include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/form.html";
		break;

	case "write_proc" :
	case "modify_proc" :
		//에디터 js 로 따로
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $online[title]'; </script>";
		include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/proc.php";
		break;

	case "view" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(상세보기) | $online[title]'; </script>";
		include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/user_view.html";
		break;

	case "delete" : // 삭제
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(삭제) | $online[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/proc.php";
		break;

	case "check" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(인증) | $online[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/auth_check.html";
		break;

	case "comment_proc" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(코멘트작성) | $online[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/proc.php";
		break;

	default:
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(목록) | $online[title]'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/online/$online_id/user_list.html";
		break;
	}
?>
