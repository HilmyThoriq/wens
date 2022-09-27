<?
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/config_setup.php";
include_once  $_SERVER['DOCUMENT_ROOT'] . "/ko_admin/auth_manager.php";
?>

<script src="https://d3js.org/d3.v4.min.js"></script>
<link rel="stylesheet" type="text/css" href="/ko_admin/js/billboard.css"/>
<script type="text/javascript" src="/ko_admin/js/billboard.js"></script>

<? 
	$c_date = date("Y-m-d");
	if(!$sel_year) $sel_year = date("Y");
	if(!$sel_month) $sel_year = date("m");

	$query = "SELECT * FROM koweb_statistics WHERE c_date ORDER BY c_date ASC"; 
	$result = mysql_query($query);

	//ie
	$ie = array();

	//date
	$date = array();

	//크롬
	$chrome =array();

	//오페라
	$opera =  array();

	//안드로이드
	$android = array();

	//사파리
	$safari = array();

	//기타
	$etc =  array();
	while($row = mysql_fetch_array($result)){

		//ie
		$ie_count = $row[bs_ie110] + $row[bs_ie100] + $row[bs_ie90] + $row[bs_ie80] + $row[bs_ie70] + $row[bs_ie60] + $row[bs_ie55] + $row[bs_ie50];

		//크롬
		$chrome_count = $row[bs_chrome];

		//오페라
		$opera_count = $row[bs_opera];

		//안드로이드
		$android_count = $row[bs_android];

		//사파리
		$safari_count = $row[safari];

		//기타
		$etc_count = $row[bs_samsung] + $row[bs_netscape] + $row[bs_firefox]  + $row[bs_iphone]  + $row[bs_etc] ;

		array_push($date, $row[c_date]);
		array_push($ie, $ie_count);
		array_push($chrome, $chrome_count);
		array_push($opera, $opera_count);
		array_push($android, $android_count);
		array_push($safari, $safari_count);
		array_push($etc, $etc_count);
	}

	foreach($date as $value){
		$date_format .= "'".$value."',";
	}
	foreach($ie as $value){
		$ie_ .= "'".$value."',";
	}
	foreach($chrome as $value){
		$chrome_ .= "'".$value."',";
	}
	foreach($opera as $value){
		$opera_ .= "'".$value."',";
	}
	
	foreach($android as $value){
		$android_ .= "'".$value."',";
	}
	
	foreach($safari as $value){
		$safari_ .= "'".$value."',";
	}

	foreach($etc as $value){
		$etc_ .= "'".$value."',";
	}

	$date_format = substr($date_format, 0, -1);
	$ie_ = substr($ie_, 0, -1);
	$chrome_ = substr($chrome_, 0, -1);
	$opera_ = substr($opera_, 0, -1);
	$android_ = substr($android_, 0, -1);
	$safari_ = substr($safari_, 0, -1);
	$etc_ = substr($etc_, 0, -1);
?>



<!-- Markup -->
<div id="BubbleChart2" style="height:450px; width:70%; padding:55px;;"></div>
<script type="text/javascript">
// Script OS별 접근 기록
var chart2 = bb.generate({
  data: {
	x : "x",
    columns: [
		["x",<?=$date_format?> ],
		["IE", <?=$ie_?>],
		["Chrome", <?=$chrome_?>],
		["Opera", <?=$opera_?>],
		["Android", <?=$android_?>],
		["Safari", <?=$safari_?>],
		["Etc", <?=$etc_?>],
    ]
  },
  axis: {
    x: {
      type: "timeseries",
	   tick: {
        format: "%m-%d"
      }
    },
    y: {
      max: 110
    }
  },
  bindto: "#BubbleChart2"
});

setTimeout(function() {
	chart2.load({
		columns: [
		["x",<?=$date_format?> ],
		["IE", <?=$ie_?>],
		["Chrome", <?=$chrome_?>],
		["Opera", <?=$opera_?>],
		["Android", <?=$android_?>],
		["Safari", <?=$safari_?>],
		["Etc", <?=$etc_?>],
    ]
	});
}, 1500);

</script>
