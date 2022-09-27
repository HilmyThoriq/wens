<?
	//reservation 기본정보 불러오기
	$reservation_query = "SELECT * FROM koweb_reservation_config limit 0,1";
	$reservation_result = mysql_query($reservation_query);
	$reservation = mysql_fetch_array($reservation_result);
	$reservation_title = $reservation['title'];
	//reservation 기본 변수
	$http_host = $_SERVER['HTTP_HOST'];
	$request_uri = $_SERVER['REQUEST_URI'];
	$url = "http://" . $http_host . $request_uri;
//	include_once $_SERVER['DOCUMENT_ROOT'] . "/js/program.js";


	if($_POST['mode'] != "calendar"){
		$js_admin = $is_admin ? "var auth=true;" : "var auth=false;";
		echo "<script>{$js_admin}</script>";
		echo "<script type=\"text/javascript\" src=\"/js/reservation.js\"></script>";
	}

//	include_once $_SERVER['DOCUMENT_ROOT'] . "/program/program_auth.php";
switch ($mode) {
	case "write" :
	case "modify" :
		//에디터 js 로 따로
		// include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $reservation_title'; </script>";
		include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/reservation/form.html";
		break;

	case "write_proc" :
	case "modify_proc" :
		//에디터 js 로 따로
		// include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $reservation_title'; </script>";
		include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/reservation/proc.php";
		break;

	case "view" :
		$table_name = "koweb_reservation_item";
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(상세보기) | $reservation_title'; </script>";
		include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/reservation/view.html";
		break;

	case "result" :
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(코멘트작성) | $reservation_title'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/reservation/result.html";
		break;

	case "calendar" :
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/reservation/calendar.html";
		break;

	case "user_list" :
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/reservation/user_list.html";
		break;

	case "user_view" :
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/reservation/user_view.html";
		break;

	default:
		$table_name = "koweb_reservation_item";
		echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(목록) | $reservation_title'; </script>";
		@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/reservation/list.html";
		break;
	}
?>
