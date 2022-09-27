<script src="https://d3js.org/d3.v4.min.js"></script>
<link rel="stylesheet" type="text/css" href="/ko_admin/js/billboard.css"/>
<script type="text/javascript" src="/ko_admin/js/billboard.js"></script>



<div class="log_area">
	<div>
		<h2>이번주 접속자수 로그분석 </h2>
		<!-- chart -->
		<div class="chart" id="user_refer">
			<img src="/ko_admin/images/main/sample01.gif" style="max-width:100%;"/>
		</div>		
		<!-- chart -->
		<!--<a href="/ko_admin/index.html?type=site&core_id=statistics&core=manager_site" class="btn_more">더보기</a>-->
		<? 
			//오늘 날짜 출력 
			$today_date = date('Y-m-d'); 

			//오늘의 요일 출력 ex) 수요일 = 3 
			$day_of_the_week = date('w'); 

			//이번주 시작 날짜 출력
			$a_week_ago = date('Y-m-d', strtotime($date." -".$day_of_the_week."days"));

			//이번주 마지막 날짜 출력
			$a_week_last = date('Y-m-d', strtotime($a_week_ago." +6 days"));

			//시작날짜 ~ 마지막날짜 array로
			$date_ = getDatesFromRange( $a_week_ago, $a_week_last );

			$total = array();
			$date_format = array();

			foreach($date_ as $value){
				$statistic = mysql_fetch_array(mysql_query("SELECT * FROM koweb_statistics WHERE c_date='$value'"));
				array_push($total, $statistic[day_total]);
			}

			foreach($total as $value){
				$total_ .= "'".$value."',";
			}

			foreach($date_ as $value){
				$date_format_ .= "'".$value."',";
			}

			$date_format = substr($date_format_, 0, -1);
			$total = substr($total_, 0, -1);
		?>

		<!-- Markup -->
		<script type="text/javascript">
		// Script OS별 접근 기록
		var chart2 = bb.generate({
			data: {
				x : "x",
				columns: [
					["x",<?=$date_format?> ],
					["방문자", <?=$total?>],
				],
				type: "bar"
			},
			color: {
				pattern: [
				  "#FF7E5A",
				  "#FFAC35",
				  "#A377FE",
				  "#8AAEC7",
				  "#65CFC2",
				  "#D0A45F",
				  "#64A4F5",
				  "#EF65A2",
				  "#70B0FF",				  
				  "#9edae5"
				]
			  },
			axis: {
				x: {
				  type: "category"
				},
				y: {
				  max: 80
				}
			},
			bindto: "#user_refer"
		});

		setTimeout(function() {
			chart2.load({
				columns: [
					["x",<?=$date_format?> ],
					["방문자", <?=$total?>],
				]
			});
		}, 1500);
		</script>
	</div>
	
	<div>
		<h2>접속경로 로그분석</h2>
		<!-- chart -->
		<div class="chart" id="path_refer">
			<img src="/ko_admin/images/main/sample02.gif" style="max-width:100%;"/>
		</div>		
		<!-- chart -->
		<a href="#" class="btn_more">더보기</a>
		<?
		$date_ = date("Y-m-d");
		$site_domain = "http://koweb.daeguweb.gethompy.com";

		$site_fullurl = array();
		$site_url = array();

		//전체 쿼리
		$query = "SELECT * FROM koweb_statistics_refer WHERE r_date LIKE '$date_%'";
		$result = mysql_query($query);
		while($row = mysql_fetch_array($result)){

		}

		//사이트별 쿼리 - 도메인
		$site_query = "SELECT DISTINCT(r_url_domain) FROM koweb_statistics_refer WHERE r_date LIKE '$date_%'";
		$site_result = mysql_query($site_query);

		$site_count = 0;
		while($site_row = mysql_fetch_array($site_result)){
			$array_push = 1;
			$site_url[$site_count][0] = $site_row[r_url_domain]; 
			
			array_push($site_url[$site_count][0], $site_row[r_url_domain]);
			
			$draw_query = "SELECT r_idx FROM koweb_statistics_refer WHERE r_url LIKE '%$site_row[r_url_domain]%'";
			$draw_result = mysql_query($draw_query);

			while($draw_row = mysql_fetch_array($draw_result)){
				$site_url[$site_count][$array_push] = "1"; 
				$array_push++;
			}
			$site_count++;
		}

		//2차 배열로 출력됨
		//$site[0][내용];

		//2차 배열로 출력출력.

		$chart_array = array();
		$chart_count = 0;

		//echo $site_url[0][0];

		foreach($site_url as $value){
			$chart_tmp = "";
			$chart_tmp_ = "";
			for($i = 0 ; $i < count($value); $i++){
				$chart_tmp_ .= "'".$value[$i]."',";
				$chart_tmp = substr($chart_tmp_, 0, -1);
			}

			$chart_array[$chart_count] = $chart_tmp;
			$chart_count++;
		}
		?>

		<script type="text/javascript">
		// Script
		var chart = bb.generate({
		  data: {
			columns: [
			],
			type: "donut",
			onclick: function(d, i) {
			console.log("onclick", d, i);

				//	alert(i.name);	

		   }
		  },
		  color: {
			pattern: [
			  "#64A4F5",
			  "#FF7E5A",
			  "#FFAC35",
			  "#A377FE",
			  "#8AAEC7",
			  "#65CFC2",
			  "#D0A45F",
			  "#EF65A2",
			  "#70B0FF",				  
			  "#9edae5"
			]
		  },
		  donut: {
			title: "referer"
		  },
		  bindto: "#path_refer"
		});

		setTimeout(function() {
			chart.load({
				columns: [
					<? foreach($chart_array as $value) { echo "[".$value."],"; } ?>
				]
			});
		}, 1500);

		</script>
	</div>
</div>

<div class="board_area">
	<div class="banner">
		<div class="box01">
			<h2>TODAY</h2>
			<!-- 년도 -->
			<span><?=date("Y")?></span>
			<!-- 월.일 -->
			<em><?=date("m")?>.<?=date("d")?></em>			
			<p>HAVE A <br/>NICE DAY!</p>
		</div>

		<div class="box02">
			<p>지금 문의하세요!</p>
			<p>명함, 카달로그, 사진촬영, 홈페이지, 온라인마케팅, 호스팅</p>
			<i>KOWEB에서</i>
			<em>한번에!</em>			
		</div>
	</div>
	
	<div class="notice">
		<h2>공지사항</h2>
		<table>
			<colgroup>
				<col style="width:70%"/>
				<col/>
			</colgroup>
			<thead>
				<tr>
					<th scope="col">제목</th>
					<th scope="col">작성일</th>
				</tr>
			</thead>
			<tbody>
				<!-- 글5건 -->
				<? 
					$notice_query = "SELECT * FROM board_notice ORDER BY notice DESC, no DESC LIMIT 5";;
					$notice_result = mysql_query($notice_query);
					while($notice = mysql_fetch_array($notice_result)){
				?>
				<tr>
					<td><a href="<?=$PHP_SELF?>?mode=view&no=<?=$notice[no]?>&board_id=board_notice&start=0"><?=$notice[title]?></a></td>
					<td><?=reset(explode(" ",$notice[reg_date]))?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
		<a href="/ko_admin/index.html?type=board&core_id=board_notice&core=manager_board" class="btn_more">더보기</a>
	</div>
	
	<div class="notice">
		<h2>자유게시판</h2>
		<table>
			<colgroup>
				<col style="width:70%"/>
				<col/>
			</colgroup>
			<thead>
				<tr>
					<th scope="col">제목</th>
					<th scope="col">작성일</th>
				</tr>
			</thead>
			<tbody>
				<!-- 글5건 -->
				<? 
					$free_query = "SELECT * FROM board_free ORDER BY notice DESC, no DESC LIMIT 5";;
					$free_result = mysql_query($free_query);
					while($free = mysql_fetch_array($free_result)){
				?>
				<tr>
					<td><a href="<?=$PHP_SELF?>?mode=view&no=<?=$free[no]?>&board_id=board_free&start=0"><?=$free[title]?></a></td>
					<td><?=reset(explode(" ",$free[reg_date]))?></td>
				</tr>
				<? } ?>
			</tbody>
		</table>
		<a href="/ko_admin/index.html?type=board&core_id=board_free&core=manager_board" class="btn_more">더보기</a>
	</div>
</div>
