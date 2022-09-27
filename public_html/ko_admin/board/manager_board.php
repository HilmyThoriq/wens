<?
		include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";  
		include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";






		//$PHP_SELF = "/ko_admin/board/manager_board.php";
		if($core_id){
			$board_id = $core_id;
		}

		if($lecture_id){
			$board_id = $lecture_id;
		}



		//board 기본정보 불러오
		$board_query = "SELECT * FROM koweb_board_config WHERE id = '$board_id'";


		$board_result = mysql_query($board_query);
		$board = mysql_fetch_array($board_result);
		$skin = $board[skin];

		if($board[dept_auth]){
			if(!strpos($board[dept_auth], $_SESSION[member_dept])){
				error("게시판 접근 권한이 없습니다.");
				exit;
			}
		}
		//board 기본 변수
		$http_host = $_SERVER['HTTP_HOST'];
		$request_uri = $_SERVER['REQUEST_URI'];
		$url = "http://" . $http_host . $request_uri;

		//include_once $_SERVER['DOCUMENT_ROOT'] . "/js/board.js";
		echo "<script type=\"text/javascript\" src=\"/js/board.js\"></script>";
		
		if(strpos($board[id], "ajax_") !== false) {  
			echo "<script type=\"text/javascript\" src=\"/ko_admin/board/skin/".$board_id."/ajax.js\"></script>";
		}
		include_once $_SERVER['DOCUMENT_ROOT'] . "/board/board_auth.php";

		if(!$board_id || $board[skin] == ""){
			error("정상적인 경로로 접근하여 주세요.");
			exit;
		}

		$tag_type = "TAG";




		switch ($mode) {
			case "write" :
			case "modify" :
			case "reply" :
				//에디터 js 로 따로 
				include_once $_SERVER['DOCUMENT_ROOT'] . "/js/smarteditor.js";
				echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(쓰기) | $board[title]'; </script>";
				include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/form.html";
				break;

				
			case "upload" :
				echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(상세보기) | $board[title]'; </script>";
				include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/upload.html";
				break;



			case "view" :
				echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(상세보기) | $board[title]'; </script>";
				include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/view.html";
				break;

			case "delete" : // 삭제
				echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(삭제) | $board[title]'; </script>";
				@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/proc.php";
				break;

			case "check" :
				echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(인증) | $board[title]'; </script>";
				@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/auth_check.html";
				break;

			case "comment_proc" :
				echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(코멘트작성) | $board[title]'; </script>";
				@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/proc.php";
				break;

			default:
				echo "<script type='text/javascript'> document.title = '" . end($history_title) . "(목록) | $board[title]'; </script>";
				if($board[skin] == "ajax_default" || $board[skin] == "ajax_area"){
					@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/admin_list.html";
				} else {
					@include_once $_SERVER[DOCUMENT_ROOT] .  "/ko_admin/board/skin/$skin/list.html";
				}
				break;
		}
		?>
