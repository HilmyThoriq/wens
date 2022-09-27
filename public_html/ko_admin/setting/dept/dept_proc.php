<? 
	include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";
	$reg_date = date("Y-m-d H:i:s");
	$this_count = 0;

	if($mode == "write_proc"){
		//변수정리
		
		foreach($category as $val){
			$depth_history .= $val . "|";
			$this_count++;
		}
		$depth_history = substr($depth_history , 0, -1);

		//category 값이 하나면 대분류 추가. 1차
		if($this_count == 1){
			$ref_group_tmp = mysql_fetch_array(mysql_query("SELECT * FROM koweb_dept WHERE state='Y' AND depth = '1' GROUP BY ref_group ORDER BY ref_group DESC LIMIT 1"));
			$ref_group = $ref_group_tmp[ref_group] + 1;
			$ref_no = "";
			$depth = "1";
			$depth_history = "";
		} else {
			//직전차수 정보 가져오기
			//배열 마지막 값 반환
			$prev_ = array_pop($category);
			if($prev_ == "new"){
				$prev_ = array_pop($category);
			}
			$query = "SELECT * FROM koweb_dept WHERE no='$prev_'";
			$result = mysql_query($query);
			$prev = mysql_fetch_array($result);
			//$depth = $prev[depth] + 1;
			$ref_group = $prev[ref_group];
			$ref_no = $prev[no];
			$depth = $prev[depth] + 1;
		}
		$sort_ = mysql_fetch_array(mysql_query("SELECT * FROM koweb_dept WHERE depth='$depth' ORDER BY sort DESC LIMIT 1"));

		//값 저장
		mysql_query("INSERT INTO koweb_dept VALUES('','$ref_group', '$ref_no', '$depth', '$depth_history', '$dept', '$sort', 'Y', '$reg_date')");
		$rowid = mysql_insert_id();
		$depth_history = str_replace("new", $rowid, $depth_history);

		if($this_count == 1){
			$ref_no = mysql_insert_id();
			$depth_history = $ref_no;
		 	mysql_query("UPDATE koweb_dept SET ref_no='$ref_no', depth_history='$depth_history' WHERE no='$ref_no'");
		} else {
			mysql_query("UPDATE koweb_dept SET depth_history='$depth_history' WHERE no='$rowid'");
		}
		alert("등록되었습니다.");
	} else if ($mode == "modify_proc"){
		
		//category 값이 하나면 대분류 추가. 1차
		if($change_ == "Y"){

			$is_check_ = mysql_num_rows(mysql_query("SELECT * FROM koweb_dept WHERE ref_no='$no'"));
			if($is_check_ > 0){
				error("해당 카테고리의 하위 카테고리가 존재합니다. 삭제할 수 없습니다.");
				exit;
			}

			foreach($this_count as $val){
				$depth_history .= $val . "|";
				$this_count++;
			}
			$depth_history = substr($depth_history , 0, -1);

			if($depth == 1){
				$ref_group_tmp = mysql_fetch_array(mysql_query("SELECT * FROM koweb_dept WHERE state='Y' AND depth = '1' GROUP BY ref_group ORDER BY ref_group DESC LIMIT 1"));
				$ref_group = $ref_group_tmp[ref_group] + 1;
				$ref_no = $no;
				$depth = "1";
				$depth_history = $no;
			} else {
				//직전차수 정보 가져오기
				//배열 마지막 값 반환
				$prev_ = array_pop($category);
				if($prev_ == "new"){
					$prev_ = array_pop($category);
				}
				$query = "SELECT * FROM koweb_dept WHERE no='$prev_'";
				$result = mysql_query($query);
				$prev = mysql_fetch_array($result);
				//$depth = $prev[depth] + 1;
				$ref_group = $prev[ref_group];
				$ref_no = $prev[no];
				$depth = $prev[depth] + 1;
				$depth_history = str_replace("new", $no, $depth_history);
			}
			
			$sort_ = mysql_fetch_array(mysql_query("SELECT * FROM koweb_dept WHERE depth='$depth' ORDER BY sort DESC LIMIT 1"));
			$sort = $sort_[sort]+1;

			mysql_query("UPDATE koweb_dept SET ref_group='$ref_group', ref_no='$ref_no', depth='$depth', dept='$dept', sort='$sort', depth_history='$depth_history' WHERE no='$no'");
			
		} else {
			mysql_query("UPDATE koweb_dept SET dept='$dept', sort='$sort' WHERE no='$no'");
		}

		alert("수정되었습니다.");

	} else if($mode == "sort") { 

		//기본정보
		$default = mysql_fetch_array(mysql_query("SELECT * FROM koweb_dept WHERE no='$no'"));

		if($sort_mode == "up"){
			$sort_WHERE = "AND sort < '$default[sort]' ORDER BY sort DESC";
		} else {
			$sort_WHERE = "AND sort > '$default[sort]' ORDER BY sort ASC";
		}

		$query = "SELECT * FROM koweb_dept WHERE depth = '$default[depth]' AND state='Y' $sort_WHERE  LIMIT 1";
		$result = mysql_query($query);

		if($result){
		$prev_data = mysql_fetch_array($result);
		$tmp_sort = "";
		$tmp_sort = $default[sort];
		$default[sort] = $prev_data[sort];
		$prev_data[sort] = $tmp_sort;

		if(!$prev_data[sort]) $prev_data[sort] = 1;

		$prev_update = mysql_query("UPDATE koweb_dept SET sort='$prev_data[sort]' WHERE no='$prev_data[no]'");
		$default = mysql_query("UPDATE koweb_dept SET sort='$default[sort]' WHERE no='$default[no]'");
		}
		alert("수정되었습니다.");
	} else if($mode == "delete") {

		$is_check_ = mysql_num_rows(mysql_query("SELECT * FROM koweb_dept WHERE ref_no='$no' AND depth > 1"));
		if($is_check_ > 0){
			error("해당 카테고리의 하위 카테고리가 존재합니다. 변경할 수 없습니다.");
			exit;
		}
		mysql_query("DELETE FROM koweb_dept WHERE no = '$no'"); 
		alert("삭제되었습니다.");
	}

	url("/ko_admin/index.html?type=setting&core_id=koweb_dept&core=manager_setting&manager_type=dept");

?>