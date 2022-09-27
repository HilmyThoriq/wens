		<link rel="stylesheet" type="text/css" href="/css/content.css"/>

<?
		include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";
		include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

		if($core_id){
			$etc_id = $core_id;
		}

		//etc 기본정보 불러오기
		$etc_query = "SELECT * FROM koweb_etc_config WHERE id = '$etc_id'";
		$etc_result = mysql_query($etc_query);
		$etc = mysql_fetch_array($etc_result);
		$etc_table = $etc[table];
		$etc_file = $etc[id];
		$etc_title = $etc[title];

		//etc 기본 변수
		$http_host = $_SERVER['HTTP_HOST'];
		$request_uri = $_SERVER['REQUEST_URI'];
		$url = "http://" . $http_host . $request_uri;

		//include_once $_SERVER['DOCUMENT_ROOT'] . "/js/program.js";
		echo "<script type=\"text/javascript\" src=\"/js/program.js\"></script>";
	//	include_once $_SERVER['DOCUMENT_ROOT'] . "/etc/etc_auth.php";

		if(!$etc_id || $etc[table] == ""){
			error("정상적인 경로로 접근하여 주세요.");
			exit;
		}

	switch ($mode) {
		case "write" :
		case "modify" :
			//에디터 js 로 따로 
			include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $etc[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/etc/$etc_id/form.html";
			break;

		case "write_proc" :
		case "modify_proc" :
			//에디터 js 로 따로 
			include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $etc[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/etc/$etc_id/proc.php";
			break;

		case "view" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(상세보기) | $etc[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/etc/$etc_id/view.html";
			break;

		case "delete" : // 삭제
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(삭제) | $etc[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/etc/$etc_id/proc.php";
			break;

		case "check" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(인증) | $etc[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/etc/$etc_id/auth_check.html";
			break;

		case "comment_proc" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(코멘트작성) | $etc[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/etc/$etc_id/proc.php";
			break;

		default:
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(목록) | $etc[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/etc/$etc_id/list.html";
			break;
		}
	?>
