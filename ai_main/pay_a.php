<?php require_once('Connections/sc.php');mysql_query("set names utf8"); ?>
<?php
session_start();
header("Content-Type:text/html; charset=utf-8");
if ($_SESSION['ceo'] == "") {header(sprintf("Location: index.php"));exit;}
$ceo=$_SESSION['ceo'];
mysql_select_db($database_sc, $sc);
$query_Reclu = sprintf("SELECT * FROM admin WHERE username = '$ceo' && at=1 && level >= 7");
$Reclu = mysql_query($query_Reclu, $sc) or die(mysql_error());
$row_Reclu = mysql_fetch_assoc($Reclu);
$totalRows_Reclu = mysql_num_rows($Reclu);
if ($totalRows_Reclu == 0) {header(sprintf("Location: index.php"));exit;}
//
$currentPage = $_SERVER["PHP_SELF"];
$maxRows_Recl = 30;
$pageNum_Recl = 0;
if (isset($_GET['pageNum_Recl'])) {
  $pageNum_Recl = $_GET['pageNum_Recl'];
}
$startRow_Recl = $pageNum_Recl * $maxRows_Recl;
$fg="";
//if ($row_Reclu['level'] == 6) {$au="&& admin = '".$ceo."'";} else {$au="";}
//if ($_GET['k1'] == "") {$key="SELECT * FROM memberdata WHERE m_fuser <> '$fg'  && m_ok >= 0 ".$au." ORDER BY card DESC";} 
//if ($_GET['k1'] != "") {$ke1=$_GET['k1'];$ke2=$_GET['k2'];$key="SELECT * FROM memberdata WHERE m_fuser <> '$fg' && m_ok >= 0 ".$au." && ".$ke1." LIKE '%%".$ke2."%%' ORDER BY card DESC";}
$key="SELECT * FROM pay_a ORDER BY id DESC";

mysql_select_db($database_sc, $sc);
$query_Recl = sprintf($key);
$query_limit_Recl = sprintf("%s LIMIT %d, %d", $query_Recl, $startRow_Recl, $maxRows_Recl);
$Recl = mysql_query($query_limit_Recl, $sc) or die(mysql_error());
$row_Recl = mysql_fetch_assoc($Recl);

if (isset($_GET['totalRows_Recl'])) {
  $totalRows_Recl = $_GET['totalRows_Recl'];
} else {
  $all_Recl = mysql_query($query_Recl);
  $totalRows_Recl = mysql_num_rows($all_Recl);
}
$totalPages_Recl = ceil($totalRows_Recl/$maxRows_Recl)-1;
$queryString_Recl = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recl") == false && 
        stristr($param, "totalRows_Recl") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recl = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recl = sprintf("&totalRows_Recl=%d%s", $totalRows_Recl, $queryString_Recl);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
<link rel="stylesheet" href="include/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="include/css/jquery-ui.css">
<script src="include/js/jquery-3.1.1.min.js"></script>
<script src="include/js/jquery.dataTables.min.js"></script>
<script src="include/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>

<style type="text/css">
.style14 {font-size: 12px;
	font-family: "新細明體";
	color: #999999;
}
.style17 {font-size: 12px;
	color: #666666;
	font-weight: bold;
}
.style7 {color: #660099;
	font-weight: bold;
}
.style8 {color: #0000FF;
	font-weight: bold;
}
.whiteBox {border: 1px solid #FFFFFF;
}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.style12 {font-size: 12px;
	line-height: 20px;
	word-spacing: 1px;
	letter-spacing: 1px;
}
.style201 {color: #F78A18; }
.style171 {font-size: 22px; line-height: 20px; word-spacing: 1px; letter-spacing: 1px; }
.style181 {font-size: 22px; line-height: 20px; word-spacing: 1px; letter-spacing: 1px; color: #0000FF; }
a:link {
	color: #00F;
}
a:visited {
	color: #00F;
}
a:hover {
	color: #F90;
}
a:active {
	color: #F00;
}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
}
#custTable td{
    text-align :center;
}
</style>
<script type="text/javascript">
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}



$(document).ready(function() {

	var base = "pay_a";
	var Columns = [{"sTitle":"序"},
			  {"sTitle":"購買日期"},
			  {"sTitle":"推薦人帳號"},
			  {"sTitle":"暱稱"},
			  {"sTitle":"收入"},
			  {"sTitle":"支出"},
			  {"sTitle":"總金額"},
			  {"sTitle":"備註"}];

	$("#reservoir").change(function (){
		base = $("#reservoir").val();
		if(base =='pay_a'){
			Columns  = [{"sTitle":"序"},
			  {"sTitle":"購買日期"},
			  {"sTitle":"推薦人帳號"},
			  {"sTitle":"暱稱"},
			  {"sTitle":"收入"},
			  {"sTitle":"支出"},
			  {"sTitle":"總金額"},
			  {"sTitle":"備註"}];
		}else if(base == 'gold_m'){
			Columns = [{"sTitle":"序"},
			  {"sTitle":"發送日期"},
			  {"sTitle":"發送時間"},
			  {"sTitle":"收受人帳號"},
			  {"sTitle":"暱稱"},
			  {"sTitle":"種類"},
			  {"sTitle":"積分額"},
			  {"sTitle":"備註"}];
		}else{
			Columns = [{"sTitle":"序"},
			  {"sTitle":"購買日期"},
			  {"sTitle":"帳號"},
			  {"sTitle":"暱稱"},
			  {"sTitle":"收入"},
			  {"sTitle":"支出"},
			  {"sTitle":"總金額"},
			  {"sTitle":"備註"}];
		}
		$.ajax({
			type: "POST",
			url: "http://www.lifelinkvip.com/ai_main/get_pay.php",
			data: {base:base},
			dataType: "json",
			success: function(resultData) {//alert(resultData);
			var opt={"oLanguage":{"sUrl":"dataTables.zh-tw.txt"},
				   "bJQueryUI":true,
				   "bProcessing":true,//如需要一些時間處理時, 表格上會顯示"處理中 ..."
				   "scrollY": 450,//卷軸
				   "scrollCollapse": true,
				   "destroy":true,
				   "order":[[ 0,  "DESC"]],
				   "aoColumns":Columns,
				   "aaData": resultData
				   };
				   
			 $("#custTable").dataTable(opt);
			 }
		});
		
	});
	$("button").click(function(){
		$.ajax({
			type: "POST",
			url: "http://www.lifelinkvip.com/ai_main/get_pay.php",
			data: {
				base:base,
				date1:$("#date1").val(),
				date2:$("#date2").val()
			},
			dataType: "json",
			success: function(resultData) {//alert(resultData);
			var opt={"oLanguage":{"sUrl":"dataTables.zh-tw.txt"},
				   "bJQueryUI":true,
				   "bProcessing":true,//如需要一些時間處理時, 表格上會顯示"處理中 ..."
				   "scrollY": 450,//卷軸
				   "scrollCollapse": true,
				   "destroy":true,
				   "order":[[ 0,  "DESC"]],
				   "aoColumns":Columns,
				   "aaData": resultData
				   };         
			 $("#custTable").dataTable(opt);
			 }
		});
		$("#date1").val("");
		$("#date1").val("");
	})
	$.ajax({
			type: "POST",
			url: "http://www.lifelinkvip.com/ai_main/get_pay.php",
			data: {base:base},
			dataType: "json",
			success: function(resultData) {//alert(resultData);
			var opt={"oLanguage":{"sUrl":"dataTables.zh-tw.txt"},
				   "bJQueryUI":true,
				   "bProcessing":true,//如需要一些時間處理時, 表格上會顯示"處理中 ..."
				   "scrollY": 450,//卷軸
				   "scrollCollapse": true,
				   "destroy":true,
				   "order":[[ 0,  "DESC"]],
				   "aoColumns":Columns,
				   "aaData": resultData
				   };         
			 $("#custTable").dataTable(opt);
			 }
		});
	
	$("#date1").datepicker({dateFormat: 'yy-mm-dd' });
	$("#date2").datepicker({dateFormat: 'yy-mm-dd' });
	
});
</script>
</head>

<body>
<table width="100%">

	<tr>
		<td align="right">
			<image style="width:100%"src="images/2.png">
			<span class="style7"> <?php echo $row_Reclu['name'];?> 您好!</span> 
			<span class="style8">&nbsp;&nbsp;&nbsp;登入帳號：<?php echo $row_Reclu['username'];?></span>&nbsp;&nbsp;
			<a href="ai_in.php"><img src="images/3.png" alt="回管理" title="回管理" width="50" height="53" border="0" /></a>
		</td>
	</tr>
 
	<tr >
		<td>
			<div style="display:inline-block">
				<a href="print_1.php?p=a">列印</a>
				<select id="reservoir">
					<option value="pay_a">營收金額</option>
					<option value="gold_m">獎金發送</option>
					<option value="pay_b">福袋積分</option>
					<option value="pay_c">靜態分紅</option>
					<option value="pay_d">組織運作</option>
					<option value="pay_e">愛心基金</option>
					<option value="pay_f">經銷商</option>
					<option value="pay_g">產品成本</option>
					<option value="pay_h">促銷獎勵</option>
					<option value="pay_i">分享積分</option>
					<option value="pay_j">車屋/旅遊</option>
					<option value="pay_k">內勤福利</option>
					<option value="pay_l">核心運作</option>
					<option value="pay_m">公司運作</option>
					<option value="pay_n">社群組織</option>
					<option value="pay_o">場地積分</option>
					<option value="pay_q">講師</option>
					<option value="pay_da">各項稅務</option>
					<option value="pay_db">金流刷卡</option>
					<option value="pay_dc">系統建置</option>
					<option value="pay_dd">人事管銷</option>
				<select>
			</div>
			<div style="display:inline-block;float:right">
				<input id='date1' type="text">至<input id='date2' type="text"><button>搜尋</button>
			</div>
		</td>
	</tr>
	<tr width="100%">
		<td width="100%">
		  
		  <table id="custTable" class="display"></table>
		</td>
	</tr>


</table>
</body>
</html>