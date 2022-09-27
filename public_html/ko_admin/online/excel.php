<!DOCTYPE html>
<html lang="ko">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>-</title>
	</head>
	<body>
		<?
			include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";  
			include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";  
			$list_query = "SELECT * FROM koweb_online_config WHERE id='$online_id'";
			$list_result = mysql_query($list_query);
			$list_ = mysql_fetch_array($list_result);

			$title = iconv("utf-8", "euckr", $list_[title]) .  "(" . date("Y.m.d") . ")";
			@header("Content-type: application/vnd.ms-excel; charset=utf-8"); 
			@header("Content-Disposition: attachment; filename=$title.xls");
			@header("Pragma: no-cache");
			@header("Expires: 0");

			$total_query = "SELECT * FROM $online_table";
			$total_result = mysql_query($total_query);

			for($i=1; $i <= 10; $i++){
				if($list_["variable_".$i]){
					$variable_cnt += 1;					
				}
			}

			 for($i = 1; $i <= $variable_cnt; $i++){ 
				$variable_ = $list_["variable_".$i];
				$variable_ = explode("|", $variable_);
				$tmp_name = $variable_[0];
				$tmp_type = $variable_[1];
				$tmp_state = $variable_[3];
				$tmp_id = $variable_[4];
				$tmp_view = $variable_[5];
				$view_array[] = $tmp_name; 
				$row_array[] = "variable_".$i;
			}

		?>
		<table class="bbsList" border="1";>
			<caption><?=$online[title]?></caption>
			<colgroup>
				<col style="width:7%"/>
				<? for ($j = 0; $j < count($view_array); $j++){ ?>
					<col />
				<? } ?>
			</colgroup>
			<thead>
				<tr>
					<th scope="col">No.</th>
					<? foreach ($view_array as $value){ ?>
						<th scope="col"><?=$value?></th>
					<? } ?>
				</tr>
			</thead>
			<tbody>
				<?
					$query = "SELECT * FROM $online_table";
					$result = mysql_query($query);
					$total = mysql_num_rows($result);
					 while($row = mysql_fetch_array($result)){ 
				?>
				<tr>
					<td><?=$total--?></td>
					<? foreach($row_array as $value){ ?>
						<td><?=$row[$value]?></td>
					<? } ?>
				</tr>
			<? } ?>
			</tbody>
		</table>
</body>
</html>







