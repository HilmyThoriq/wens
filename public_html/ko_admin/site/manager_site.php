		<link rel="stylesheet" type="text/css" href="/css/content.css"/>

<?
		include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";
		include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

		if($core_id){
			$site_id = $core_id;
		}

		//site 기본정보 불러오기
		$site_query = "SELECT * FROM koweb_site_config WHERE id = '$site_id'";
		$site_result = mysql_query($site_query);
		$site = mysql_fsiteh_array($site_result);
		$site_table = $site[table];
		$site_file = $site[id];
		$site_title = $site[title];

		//site 기본 변수
		$http_host = $_SERVER['HTTP_HOST'];
		$request_uri = $_SERVER['REQUEST_URI'];
		$url = "http://" . $http_host . $request_uri;
		
		echo "<script type=\"text/javascript\" src=\"/js/program.js\"></script>";
	//	include_once $_SERVER['DOCUMENT_ROOT'] . "/js/program.js";
	//	include_once $_SERVER['DOCUMENT_ROOT'] . "/site/site_auth.php";

		if(!$site_id || $site[table] == ""){
			error("정상적인 경로로 접근하여 주세요.");
			exit;
		}

	switch ($mode) {
		case "write" :
		case "modify" :
			//에디터 js 로 따로 
			include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $site[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/site/$site_id/form.html";
			break;

		case "write_proc" :
		case "modify_proc" :
			//에디터 js 로 따로 
			include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $site[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/site/$site_id/proc.php";
			break;

		case "view" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(상세보기) | $site[title]'; </script>";
			include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/site/$site_id/view.html";
			break;

		case "delete" : // 삭제
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(삭제) | $site[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/site/$site_id/proc.php";
			break;

		case "check" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(인증) | $site[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/site/$site_id/auth_check.html";
			break;

		case "comment_proc" :
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(코멘트작성) | $site[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/site/$site_id/proc.php";
			break;

		default:
			echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(목록) | $site[title]'; </script>";
			@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/site/$site_id/list.html";
			break;
		}
	?>
