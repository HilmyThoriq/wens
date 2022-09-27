<? include $_SERVER['DOCUMENT_ROOT'] . "/head.php"; ?>
<?
	$board = mysql_fetch_array(mysql_query("SELECT * FROM koweb_board_config WHERE id = '$board_id' LIMIT 1"));
	include $_SERVER['DOCUMENT_ROOT'] . "/board/board_auth.php"; 

	$query = "SELECT * FROM $board_id WHERE no='$no'";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	if($row[tag_type] != "TAG"){
		$row[comments] = nl2br($row[comments]);
	}			
	
	//관리자가 아님
	if(!$_SESSION[is_admin]){
		//비밀번호가 없음 
		if($row[secret] == "Y"){
			if($board[is_membership] != "Y"){
				if($password == "" ){
					echo "<script>do_auth('auth', '$board_id', '$keyword', '$search_key', '$start', '$cate', '$no', '', 'view');</script>";
					exit;
				} else {
					$password = hash("sha256", $password);

					if($row[password] != $password){
						alert("해당게시물은 작성자 및 관리자만 확인 가능합니다.");
						echo "<script>do_Process('list', '$board_id', '$keyword', '$search_key', '$start', '$cate', '', '');</script>";
						exit;
					}
				}
			} else {
				if($_SESSION[member_id] != $row[id]){
					alert("해당게시물은 작성자 및 관리자만 확인 가능합니다.");
					echo "<script>do_Process('list', '$board_id', '$keyword', '$search_key', '$start', '$cate', '', '');</script>";
					exit;
				}
			}
		}
	}
	mysql_query("UPDATE $board_id SET view_count = view_count +1 WHERE no='$no'");
	
	if($row[category]) $sub_title = "[$row[category]]";
	else unset($sub_title);
?>
<h3>찾아오시는 길</h3>
	<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=27ec05cfabe6c1fecdf7fd5d8d81ee33&libraries=services"></script>

	<div class="shopMap">
		<!-- 지도삽입 -->
		<div id="daumRoughmapContainer1544355564598" class="root_daum_roughmap root_daum_roughmap_landing" style="width:100%;"></div>
	<div id="map" style="width:100%;height:350px;"></div>
	
	<script>
	var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
		mapOption = {
			center: new daum.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
			level: 3 // 지도의 확대 레벨
		};  

	// 지도를 생성합니다    
	var map = new daum.maps.Map(mapContainer, mapOption); 

	// 주소-좌표 변환 객체를 생성합니다
	var geocoder = new daum.maps.services.Geocoder();

	// 주소로 좌표를 검색합니다
	geocoder.addressSearch('<?=$row[address1]?>', function(result, status) {

		// 정상적으로 검색이 완료됐으면 
		 if (status === daum.maps.services.Status.OK) {

			var coords = new daum.maps.LatLng(result[0].y, result[0].x);

			// 결과값으로 받은 위치를 마커로 표시합니다
			var marker = new daum.maps.Marker({
				map: map,
				position: coords
			});

			// 인포윈도우로 장소에 대한 설명을 표시합니다
			var infowindow = new daum.maps.InfoWindow({
				content: '<div style="width:150px;text-align:center;padding:6px 0;">'+"<?=$row[title]?>"+'</div>'
			});
			infowindow.open(map, marker);

			// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
			map.setCenter(coords);
		} 
	});    
	</script>
		<!-- //지도삽입 -->
		
		<!-- 주소 -->
		<p class="txt_map"><?=$row[address1]?> <?=$row[address2]?></p>
	</div>
<? 
	//코멘트 사용하는 게시판인지 확인
	if($board[use_comment] == "Y") {
		include "comment.html";
	}
?>

<!-- 이전/다음 -->
<?
	// 다음글, 이전글
	$temp_next = mysql_fetch_array(mysql_query("SELECT no, title FROM $board_id WHERE  no>'$no' ORDER BY  no ASC LIMIT 1"));
	if ($temp_next) $next = "?no=$temp_next[no]&amp;mode=view&amp;board_id=$board_id&amp;start=$start&amp;search_key=$search_key&amp;keyword=$keyword&amp;category=$category"; 
	else $next = "#";
	// 이전글
	$temp_prev = mysql_fetch_array(mysql_query("SELECT no, title FROM $board_id WHERE no<'$no' ORDER BY no DESC LIMIT 1"));
	if ($temp_prev) $prev = "?no=$temp_prev[no]&amp;mode=view&amp;board_id=$board_id&amp;start=$start&amp;search_key=$search_key&amp;keyword=$keyword&amp;category=$category";



	else $prev = "#";
?>
<table class="bbsView page">
	<caption>이전 다음 글보기</caption>
	<colgroup>
		<col data-view="th" style="width:15%"/>
		<col data-view="td" style="width:85%"/>
	</colgroup>
	<tbody>
		<tr>
			<th scope="row">이전글</th>
			<td>
				<a href="#" <? if($temp_prev) { ?> onclick="javascript:do_Process('view', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$temp_prev[no]?>', '')" <? } ?>><?=$temp_prev[title]?></a>
			</td>
		</tr>
		<tr>
			<th scope="row">다음글</th>
			<td><a href="#" <? if($temp_next) { ?> onclick="javascript:do_Process('view', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$temp_next[no]?>', '')" <? } ?>><?=$temp_next[title]?></a></td>
		</tr>
	</tbody>
</table>
<!-- //이전/다음 -->

<!-- 버튼 -->
<div class="btn_area">
	<? if($auth_read && $is_admin){ ?>
		<a href="javascript:do_Process('modify', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>')" class="button">수정</a>
	<? } ?>
	<? if($_SESSION[is_admin]){ ?>
		<a href="#" class="button" onclick="return confirm('삭제한 데이터는 다시 복구 할 수 없습니다. 삭제하시겠습니까?'); javascript:do_Process('delete', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '')" >삭제</a>
	<? } else if($auth_delete){ ?>
		<? if($board[is_membership] != "Y"){ ?>
			<a href="javascript:do_auth('auth', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '', 'delete')" class="button">삭제</a>
		<? } else { ?>
			<a href="javascript:do_Process('auth', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '', 'delete')" class="button">삭제</a>
		<? } ?>
	<? } ?>

	<a href="javascript:do_Process('list', '<?=$board_id?>', '<?=$keyword?>', '<?=$search_key?>', '<?=$start?>', '<?=$cate?>', '<?=$row[no]?>', '')" class="button">목록</a>


</div>
<!-- //버튼 -->
