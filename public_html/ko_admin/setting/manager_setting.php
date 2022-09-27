<?
		include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";
		include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

		//기본정보 불러오기
		$board_query = "SELECT * FROM koweb_".$manager_type."_config";
		$board_result = mysql_query($board_query);
		$board = mysql_fetch_array($board_result);

		//board 기본 변수
		$http_host = $_SERVER['HTTP_HOST'];
		$request_uri = $_SERVER['REQUEST_URI'];
		$url = "http://" . $http_host . $request_uri;

		if(!$core || !$manager_type || !$type ){
			error("정상적인 경로로 접근하여 주세요.");
			exit;
		}
		//include_once $_SERVER['DOCUMENT_ROOT'] . "/js/program.js";
		include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
		echo "<script type=\"text/javascript\" src=\"/js/program.js\"></script>";
		echo "<script type=\"text/javascript\" src=\"/js/setting.js\"></script>";


	switch ($mode) {
		case "write" :
		case "modify" :
			//에디터 js 로 따로
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $board[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/setting/".$manager_type."/".$manager_type."_form.html";
			break;

		case "write_proc" :
		case "modify_proc" :
		case "state_proc" :
		case "sort" :
			//에디터 js 로 따로
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/setting/".$manager_type."/".$manager_type."_proc.php";
			break;

		case "view" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(상세보기) | $board[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/setting/".$manager_type."/".$manager_type."_view.html";
			break;

		case "apply_proc" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(상세보기) | $board[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/setting/".$manager_type."/".$manager_type."_proc.php";
			break;

		case "delete" : // 삭제
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(삭제) | $board[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/setting/".$manager_type."/".$manager_type."_proc.php";
			break;

		default:
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(목록) | $board[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/setting/".$manager_type."/".$manager_type."_list.html";
			break;
		}
	?>
