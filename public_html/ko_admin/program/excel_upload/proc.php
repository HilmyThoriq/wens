<?
//include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";

// 엑셀 등록 추가
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/PHPExcel-1.8/Classes/PHPExcel.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php"; 

$ip = $REMOTE_ADDR;
if (!$reg_date) $reg_date = date("Y-m-d H:i:s");
$c_date = date("Y-m-d");
$return_url = explode("?", $return_url);
$dir = $_SERVER['DOCUMENT_ROOT'] . "/upload/program/".$program_id."/";

if($_FILES["acknowledgement_upload"][size] > 0) {
	$basename = trim(basename($_FILES["acknowledgement_upload"][name]));
	$resource = explode(".", $basename);
	$i = count($resource)-1;
	$file_extension = trim($resource[$i]);

	if($file_extension == "xlsx") {
		$file_name = upload_file($dir, $_FILES["acknowledgement_upload"][tmp_name], $_FILES["acknowledgement_upload"][name]);

		$target_excel = $dir.$file_name;
		$filename = $target_excel; 

		$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
		$objReader->setReadDataOnly(true);

		$objExcel = $objReader->load($filename);
		$objExcel->setActiveSheetIndex(0);
		$objWorksheet = $objExcel->getActiveSheet();
		$rowIterator = $objWorksheet->getRowIterator();

		

		foreach ($rowIterator as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
		}

		 $maxRow = $objWorksheet->getHighestRow();

		$category_origin = "";


		alert($maxRow);

		

		for ($i = 2 ; $i <= $maxRow ; $i++) {
			$title = strip_tags(trim($objWorksheet->getCell('A' . $i)->getValue()));
			$comments = strip_tags(trim($objWorksheet->getCell('B' . $i)->getValue()));
			$ACK_korean = strip_tags(trim($objWorksheet->getCell('C' . $i)->getValue()));
			$valid = strip_tags(trim($objWorksheet->getCell('D' . $i)->getValue()));
			$Patent = strip_tags(trim($objWorksheet->getCell('E' . $i)->getValue()));
			$Patent_korean = strip_tags(trim($objWorksheet->getCell('F' . $i)->getValue()));

			if($category != "") {
				$str_len = strlen($category);
				$str_pos = strrpos($category, "(");
				$cut = $str_len - $str_pos;
				//$category_origin = trim(substr($category, 0, -$cut));
				$category_origin = $category;
			}
			if($title){
				$query = "INSERT board_acknowledgement SET title = '$title', comments = '$comments', ACK_korean = '$ACK_korean', valid = '$valid', Patent = '$Patent', Patent_korean='$Patent_korean' ";
			

			
				$result = mysql_query($query);
			}
//			if($result) echo " ---------- <span style='color: blue;'>성공!</span>";
//			else echo " ---------- <span style='color: red;'>실패!</span>";
//			echo "<br>";
		}
		
		// 입력 후 파일 삭제
		@unlink($dir.$file_name);
	} else {
		error("xlsx파일만 업로드 가능합니다.");
	}
} 


//  Publication 업로드 구간
if($_FILES["Publication_upload"][size] > 0) {
	$basename = trim(basename($_FILES["Publication_upload"][name]));
	$resource = explode(".", $basename);
	$i = count($resource)-1;
	$file_extension = trim($resource[$i]);

	if($file_extension == "xlsx") {
		$file_name = upload_file($dir, $_FILES["Publication_upload"][tmp_name], $_FILES["Publication_upload"][name]);

		$target_excel = $dir.$file_name;
		$filename = $target_excel; 

		$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
		$objReader->setReadDataOnly(true);

		$objExcel = $objReader->load($filename);
		$objExcel->setActiveSheetIndex(0);
		$objWorksheet = $objExcel->getActiveSheet();
		$rowIterator = $objWorksheet->getRowIterator();

		

		foreach ($rowIterator as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
		}

		 $maxRow = $objWorksheet->getHighestRow();

		$category_origin = "";
		
		for ($i = 2 ; $i <= $maxRow ; $i++) {
			$category = strip_tags(trim($objWorksheet->getCell('A' . $i)->getValue()));
			$title = strip_tags(trim($objWorksheet->getCell('B' . $i)->getValue()));
			$notice = strip_tags(trim($objWorksheet->getCell('C' . $i)->getValue()));
			$secret = strip_tags(trim($objWorksheet->getCell('D' . $i)->getValue()));
			$paper_title = strip_tags(trim($objWorksheet->getCell('E' . $i)->getValue()));
			$f_author = strip_tags(trim($objWorksheet->getCell('F' . $i)->getValue()));
			$c_author = strip_tags(trim($objWorksheet->getCell('G' . $i)->getValue()));
			$volume = strip_tags(trim($objWorksheet->getCell('H' . $i)->getValue()));
			$type_publ = strip_tags(trim($objWorksheet->getCell('I' . $i)->getValue()));
			$number_publ = strip_tags(trim($objWorksheet->getCell('J' . $i)->getValue()));
			$s_page = strip_tags(trim($objWorksheet->getCell('K' . $i)->getValue()));
			$e_page = strip_tags(trim($objWorksheet->getCell('L' . $i)->getValue()));
			$impact_factor = strip_tags(trim($objWorksheet->getCell('M' . $i)->getValue()));
			$filled_ack = strip_tags(trim($objWorksheet->getCell('N' . $i)->getValue()));
			$publ_date = strip_tags(trim($objWorksheet->getCell('O' . $i)->getValue()));
			$publ_date_Y = strip_tags(trim($objWorksheet->getCell('P' . $i)->getValue()));
			$publ_date_M = strip_tags(trim($objWorksheet->getCell('Q' . $i)->getValue()));
			$publ_date_D = strip_tags(trim($objWorksheet->getCell('R' . $i)->getValue()));
			$url_link = strip_tags(trim($objWorksheet->getCell('S' . $i)->getValue()));


			//엑셀이 날짜포맷인지 아닌지 확인
			if(strpos($tmp_publ_date_array, "-") !== false) {
				$tmp_publ_date_array = explode("-", $publ_date);
				$publ_date_array = array();
				$c_date_y = substr($c_date, 2, 2);
				$month_array = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

				//월이 영문일 경우 기본 날짜포맷으로 변경
				foreach($month_array as $m_key => $m_val) {
					if($tmp_publ_date_array[1] == $m_val) {
						if($c_date_y < $tmp_publ_date_array[0]) {
							$publ_date_array[0] = "19".$tmp_publ_date_array[2];
						} else {
							$publ_date_array[0] = "20".$tmp_publ_date_array[2];
						}
						$publ_date_array[1] = $m_key + 1;
						$publ_date_array[2] = $tmp_publ_date_array[0];
						
						$publ_date = $publ_date_array[0] . "-" . $publ_date_array[1] . "-" . $publ_date_array[2];
						break;
					}
				}
			} else {
				//엑셀이 날짜포맷일 경우 기본 날짜포맷으로 변경
				if($publ_date) {
					$publ_date = ( $publ_date - 25569 ) * 86400 - 60 * 60 * 9;
					$publ_date = round( $publ_date * 10 ) / 10;
					$publ_date = date('Y-m-d', $publ_date);
				}
			}


			//if($category != "") {
			//	$str_len = strlen($category);
			//	$str_pos = strrpos($category, "(");
			//	$cut = $str_len - $str_pos;
				//$category_origin = trim(substr($category, 0, -$cut));
			//	$category_origin = $category;
			//}
			if($title){
				$query = "INSERT 
									board_publication 
								SET 
									category='$category', 
									title = '$title', 
									notice = '$notice', 
									secret='$secret', 
									paper_title = '$paper_title', 
									f_author = '$f_author', 
									c_author = '$c_author', 
									volume = '$volume', 
									type_publ = '$type_puble', 
									number_publ = '$number_putlb', 
									s_page='$s_page', 
									e_page = '$e_page', 
									impact_factor = '$impact_factor', 
									filled_ack = '$filled_ack', 
									publ_date = '$publ_date', 
									publ_date_Y = '$publ_date_Y', 
									publ_date_M = '$publ_date_M', 
									publ_date_D = '$publ_date_D', 
									url_link = '$url_link' ";
			
				$result = mysql_query($query);
			}
//			if($result) echo " ---------- <span style='color: blue;'>성공!</span>";
//			else echo " ---------- <span style='color: red;'>실패!</span>";
//			echo "<br>";
		}
		
		// 입력 후 파일 삭제
		@unlink($dir.$file_name);
	} else {
		error("xlsx파일만 업로드 가능합니다.");
	}
} 
// Publication 업로드 구간 끝

// Recruitment 업로드 구간
if($_FILES["Recruitment_upload"][size] > 0) {
	$basename = trim(basename($_FILES["Recruitment_upload"][name]));
	$resource = explode(".", $basename);
	$i = count($resource)-1;
	$file_extension = trim($resource[$i]);

	if($file_extension == "xlsx") {
		$file_name = upload_file($dir, $_FILES["Recruitment_upload"][tmp_name], $_FILES["Recruitment_upload"][name]);

		$target_excel = $dir.$file_name;
		$filename = $target_excel; 

		$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
		$objReader->setReadDataOnly(true);

		$objExcel = $objReader->load($filename);
		$objExcel->setActiveSheetIndex(0);
		$objWorksheet = $objExcel->getActiveSheet();
		$rowIterator = $objWorksheet->getRowIterator();

		

		foreach ($rowIterator as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
		}

		 $maxRow = $objWorksheet->getHighestRow();

		$category_origin = "";
		
		for ($i = 2 ; $i <= $maxRow ; $i++) {
			$first_name = strip_tags(trim($objWorksheet->getCell('A' . $i)->getValue()));
			$last_name = strip_tags(trim($objWorksheet->getCell('B' . $i)->getValue()));
			$religion = strip_tags(trim($objWorksheet->getCell('C' . $i)->getValue()));
			$gender = strip_tags(trim($objWorksheet->getCell('D' . $i)->getValue()));
			$email = strip_tags(trim($objWorksheet->getCell('E' . $i)->getValue()));
			$marital_status = strip_tags(trim($objWorksheet->getCell('F' . $i)->getValue()));
			$nationality = strip_tags(trim($objWorksheet->getCell('G' . $i)->getValue()));
			$passport_number = strip_tags(trim($objWorksheet->getCell('H' . $i)->getValue()));
			$period = strip_tags(trim($objWorksheet->getCell('I' . $i)->getValue()));
			$apply_for = strip_tags(trim($objWorksheet->getCell('J' . $i)->getValue()));
			$scholarship = strip_tags(trim($objWorksheet->getCell('K' . $i)->getValue()));
			$mdqn = strip_tags(trim($objWorksheet->getCell('L' . $i)->getValue()));
			$mdqun = strip_tags(trim($objWorksheet->getCell('M' . $i)->getValue()));
			$CGPA = strip_tags(trim($objWorksheet->getCell('N' . $i)->getValue()));
			$mdgy = strip_tags(trim($objWorksheet->getCell('O' . $i)->getValue()));
			$udqn = strip_tags(trim($objWorksheet->getCell('P' . $i)->getValue()));
			$udgun = strip_tags(trim($objWorksheet->getCell('Q' . $i)->getValue()));
			$CGPAsiud = strip_tags(trim($objWorksheet->getCell('R' . $i)->getValue()));
			$udgy = strip_tags(trim($objWorksheet->getCell('S' . $i)->getValue()));
			$eoks = strip_tags(trim($objWorksheet->getCell('T' . $i)->getValue()));

			//if($category != "") {
			//	$str_len = strlen($category);
			//	$str_pos = strrpos($category, "(");
			//	$cut = $str_len - $str_pos;
				//$category_origin = trim(substr($category, 0, -$cut));
			//	$category_origin = $category;
			//}
			if($first_name){
				$query = "INSERT 
									board_recruitment 
								SET 
									first_name='$first_name', 
									last_name = '$last_name', 
									religion = '$religion', 
									gender='$gender', 
									email = '$email', 
									marital_status = '$marital_status', 
									nationality = '$nationality', 
									passport_number = '$passport_number', 
									period = '$period', 
									apply_for = '$apply_for', 
									scholarship='$scholarship', 
									mdqn = '$mdqn', 
									mdqun = '$mdqun', 
									CGPA = '$CGPA', 
									mdgy = '$mdgy', 
									udqn = '$udqn', 
									udgun = '$udgun', 
									CGPAsiud = '$CGPAsiud', 
									udgy = '$udgy',
									eoks = '$eoks' ";
			

				$result = mysql_query($query);
			}
//			if($result) echo " ---------- <span style='color: blue;'>성공!</span>";
//			else echo " ---------- <span style='color: red;'>실패!</span>";
//			echo "<br>";
		}
		
		// 입력 후 파일 삭제
		@unlink($dir.$file_name);
	} else {
		error("xlsx파일만 업로드 가능합니다.");
	}
} 
// Recruitment 업로드 구간 끝

// Patents 업로드 구간 끝
if($_FILES["Patents_upload"][size] > 0) {
	$basename = trim(basename($_FILES["Patents_upload"][name]));
	$resource = explode(".", $basename);
	$i = count($resource)-1;
	$file_extension = trim($resource[$i]);

	if($file_extension == "xlsx") {
		$file_name = upload_file($dir, $_FILES["Patents_upload"][tmp_name], $_FILES["Patents_upload"][name]);

		$target_excel = $dir.$file_name;
		$filename = $target_excel; 

		$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
		$objReader->setReadDataOnly(true);

		$objExcel = $objReader->load($filename);
		$objExcel->setActiveSheetIndex(0);
		$objWorksheet = $objExcel->getActiveSheet();
		$rowIterator = $objWorksheet->getRowIterator();

		

		foreach ($rowIterator as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
		}

		 $maxRow = $objWorksheet->getHighestRow();

		$category_origin = "";
		
		for ($i = 2 ; $i <= $maxRow ; $i++) {
			$title = strip_tags(trim($objWorksheet->getCell('A' . $i)->getValue()));
			$category_inter_dome = strip_tags(trim($objWorksheet->getCell('B' . $i)->getValue()));
			$status_cate = strip_tags(trim($objWorksheet->getCell('C' . $i)->getValue()));
			$appli_num = strip_tags(trim($objWorksheet->getCell('D' . $i)->getValue()));
			$patent_num = strip_tags(trim($objWorksheet->getCell('E' . $i)->getValue()));
			$filing_date = strip_tags(trim($objWorksheet->getCell('F' . $i)->getValue()));
			$regi_date = strip_tags(trim($objWorksheet->getCell('G' . $i)->getValue()));
			$filled_ack = strip_tags(trim($objWorksheet->getCell('H' . $i)->getValue()));
			$link_url = strip_tags(trim($objWorksheet->getCell('I' . $i)->getValue()));

			//엑셀이 날짜포맷인지 아닌지 확인
			if(strpos($tmp_filing_date_array, "-") !== false) {
				$tmp_filing_date_array = explode("-", $filing_date);
				$filing_date_array = array();

				$tmp_regi_date_array = explode("-", $regi_date);
				$regi_date_array = array();

				$c_date_y = substr($c_date, 2, 2);
				$month_array = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

				//월이 영문일 경우 기본 날짜포맷으로 변경
				foreach($month_array as $m_key => $m_val) {
					if($tmp_filing_date_array[1] == $m_val) {
						if($c_date_y < $tmp_filing_date_array[0]) {
							$filing_date_array[0] = "19".$tmp_filing_date_array[2];
						} else {
							$filing_date_array[0] = "20".$tmp_filing_date_array[2];
						}
						$filing_date_array[1] = $m_key + 1;
						$filing_date_array[2] = $tmp_filing_date_array[0];
						
						$filing_date = $filing_date_array[0] . "-" . $filing_date_array[1] . "-" . $filing_date_array[2];
						break;
					}
				}

				foreach($month_array as $m_key => $m_val) {
					if($tmp_regi_date_array[1] == $m_val) {
						if($c_date_y < $tmp_regi_date_array[0]) {
							$regi_date_array[0] = "19".$tmp_regi_date_array[2];
						} else {
							$regi_date_array[0] = "20".$tmp_regi_date_array[2];
						}
						$regi_date_array[1] = $m_key + 1;
						$regi_date_array[2] = $tmp_regi_date_array[0];
						
						$regi_date = $regi_date_array[0] . "-" . $regi_date_array[1] . "-" . $regi_date_array[2];
						break;
					}
				}
			} else {
				//엑셀이 날짜포맷일 경우 기본 날짜포맷으로 변경
				if($filing_date) {
					$filing_date = ( $filing_date - 25569 ) * 86400 - 60 * 60 * 9;
					$filing_date = round( $filing_date * 10 ) / 10;
					$filing_date = date('Y-m-d', $filing_date);
				}
				
				if($regi_date) {
					$regi_date = ( $regi_date - 25569 ) * 86400 - 60 * 60 * 9;
					$regi_date = round( $regi_date * 10 ) / 10;
					$regi_date = date('Y-m-d', $regi_date);
				}
			}

			//if($category != "") {
			//	$str_len = strlen($category);
			//	$str_pos = strrpos($category, "(");
			//	$cut = $str_len - $str_pos;
				//$category_origin = trim(substr($category, 0, -$cut));
			//	$category_origin = $category;
			//}
			if($title){
				$query = "INSERT 
									board_patents 
								SET 
									title='$title',  
									category_inter_dome = '$category_inter_dome', 
									status_cate='$status_cate', 
									appli_num = '$appli_num', 
									patent_num = '$patent_num', 
									filing_date = '$filing_date', 
									regi_date = '$regi_date', 
									filled_ack = '$filled_ack', 
									link_url = '$link_url' ";

				$result = mysql_query($query);
			}
//			if($result) echo " ---------- <span style='color: blue;'>성공!</span>";
//			else echo " ---------- <span style='color: red;'>실패!</span>";
//			echo "<br>";
		}
		
		// 입력 후 파일 삭제
		@unlink($dir.$file_name);
	} else {
		error("xlsx파일만 업로드 가능합니다.");
	}
} 
// Patents 업로드 구간 끝

if($_FILES["video_upload"][size] > 0) {
	$basename = trim(basename($_FILES["video_upload"][name]));
	$resource = explode(".", $basename);
	$i = count($resource)-1;
	$file_extension = trim($resource[$i]);

	if($file_extension == "xlsx") {
		$file_name = upload_file($dir, $_FILES["video_upload"][tmp_name], $_FILES["video_upload"][name]);

		$target_excel = $dir.$file_name;
		$filename = $target_excel; 
		$objReader = PHPExcel_IOFactory::createReaderForFile($filename);
		$objReader->setReadDataOnly(true);

		$objExcel = $objReader->load($filename);
		$objExcel->setActiveSheetIndex(0);
		$objWorksheet = $objExcel->getActiveSheet();
		$rowIterator = $objWorksheet->getRowIterator();

		foreach ($rowIterator as $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 
		}

		$maxRow = $objWorksheet->getHighestRow();



		for ($i = 2 ; $i <= $maxRow ; $i++) {
			$category = strip_tags(trim($objWorksheet->getCell('A' . $i)->getValue()));
			$title = strip_tags(trim($objWorksheet->getCell('B' . $i)->getValue()));
			$link = strip_tags(trim($objWorksheet->getCell('C' . $i)->getValue()));
			$check_change = strip_tags(trim($objWorksheet->getCell('D' . $i)->getValue()));
			$comments = strip_tags(trim($objWorksheet->getCell('E' . $i)->getValue()));
			$check_file = strip_tags(trim($objWorksheet->getCell('F' . $i)->getValue()));

			if($comments != "") {
				$comments = str_replace("-", "<br>-", $comments); 
			} 

			if($check_change == "1") {
				$query = "UPDATE board_elearning SET link = '$link' WHERE title = '$title' ";
			} else {
				$query = "INSERT board_elearning SET 
						depth = '0', 
						ref_no = '', 
						ref_group = '', 
						id = '$_SESSION[member_id]', 
						name = '관리자', 
						password = 'a7738e2903da3ea98751965ec74b68ce65767e144d6eccea6cb318808132ca21', 
						category = '$category', 
						title = '$title', 
						tag_type = 'TAG', 
						comments = '$comments', 
						reg_date = '$reg_date',
						ip = '$ip',
						link = '$link' ";
			}
//			echo $query;
//			$result = mysql_query($query);
//			if($result) echo " ---------- <span style='color: blue;'>성공!</span>";
//			else echo " ---------- <span style='color: red;'>실패!</span>";
//			echo "<br><br>";
		}
		
		// 입력 후 파일 삭제
		@unlink($dir.$file_name);

		exit;
	} else {
		error("xlsx파일만 업로드 가능합니다.");
	}
} 

alert("등록되었습니다.");
url("/ko_admin/index.html?type=program&core=manager_program&manager_type=&core_id=excel_upload");