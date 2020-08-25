<!DOCTYPE html>
<HTML lang='ja'>
<HEAD>
	<link rel="stylesheet" type="text/css" href="style.css">
	<TITLE>検索結果</TITLE>
	<META charset='utf8'>

	<!-- javascript -->
	<script src="./jquery-3.5.1.js"></script>
	<script src="https://d3js.org/d3.v5.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.1/js/jquery.tablesorter.min.js"></script>
	<script src='./tablesorter.js'></script>
	<script type='text/javascript'>
		$(function() {
			$(".thead, .tbody").on('scroll', function() {
				if ( $(this).attr('id') === 'thead' ) {
					$('.tbody').scrollLeft($(this).scrollLeft());
				} else {
					$(".thead").scrollLeft($(this).scrollLeft());
				}
			});
		});
	</script>
	<script>
		$(function(){
			$('.js-modal-close').on('click',function(){
				$('.js-modal').fadeOut();
				return false;
			});
		});
	</script>
</HEAD>
<BODY>
	<header>
		<h1><a href='./landing_page.php' id='link'>スポット市場取引結果</a></h1>
		<img src="./img/logo.png">
	</header>
<?php

// 1.データベースに接続
$dsn = 'mysql:host=mysql;dbname=JEPX datebase';
$user = 'root';
$password = 'root';

try {

	$pdo = new PDO($dsn, $user, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

} catch (PDOException $Exception) {

	die('エラー :'.$Exception->getMessage());

}




// 2.PDOの変数を設定

// ---日付の設定---
date_default_timezone_set('Asia/Tokyo');

$trade_date_1 = $_GET['trade_date_1'];

if($_GET['trade_date_2'] < 10){
	$trade_date_2 = '0'.$_GET['trade_date_2'];
}else{
	$trade_date_2 = $_GET['trade_date_2'];
}

if($_GET['trade_date_3'] < 10){
	$trade_date_3 = '0'.$_GET['trade_date_3'];
}else{
	$trade_date_3 = $_GET['trade_date_3'];
}

$trade_date_from = $trade_date_1.'-'.$trade_date_2.'-'.$trade_date_3;


$trade_date_4 = $_GET['trade_date_4'];

if($_GET['trade_date_5'] < 10){
	$trade_date_5 = '0'.$_GET['trade_date_5'];
}else{
	$trade_date_5 = $_GET['trade_date_5'];

}if($_GET['trade_date_6'] < 10){
	$trade_date_6 = '0'.$_GET['trade_date_6'];
}else{
	$trade_date_6 = $_GET['trade_date_6'];
}

$trade_date_to = $trade_date_4.'-'.$trade_date_5.'-'.$trade_date_6;

// ---検索日付の始点のほうが終点よりも大きい場合、日付の入れ替えを行う---
if(strtotime($trade_date_from) > strtotime($trade_date_to)){
	$trade_date_to = $trade_date_1.'-'.$trade_date_2.'-'.$trade_date_3;
	$trade_date_from = $trade_date_4.'-'.$trade_date_5.'-'.$trade_date_6;
}


// ---時刻コードの設定---

$time_code_from = $_GET['time_code_from'];
$time_code_to = $_GET['time_code_to'];

// ---検索する時刻コードの始点のほうが終点よりも大きい場合、時刻コードの入れ替えを行う---
if($time_code_from > $time_code_to){
	$time_code_from =  $_GET['time_code_to'];
	$time_code_to = $_GET['time_code_from'];
}




// 3.mysqlを検索

try {
	if($time_code_from==='0' and $time_code_to==='0'){
		$sql = "SELECT * FROM spot_market_result
				WHERE trade_date BETWEEN :trade_date_from AND :trade_date_to
				AND del_flag = 0
				ORDER BY trade_date ASC, time_code ASC";	 
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':trade_date_from', $trade_date_from,PDO::PARAM_STR);
		$stmt->bindValue(':trade_date_to', $trade_date_to,PDO::PARAM_STR);
		$stmt->execute();
		$count = $stmt->rowCount();
		print "<p id='sea_stmt'>検索結果は<span>{$count}</span>件です</p><BR>";
    }else{
	    $sql = "SELECT * FROM spot_market_result 
			WHERE trade_date BETWEEN :trade_date_from AND :trade_date_to
			AND time_code BETWEEN :time_code_from AND :time_code_to
			AND del_flag = 0";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':trade_date_from', $trade_date_from,PDO::PARAM_STR);
		$stmt->bindValue(':trade_date_to', $trade_date_to,PDO::PARAM_STR);
		$stmt->bindValue(':time_code_from', $time_code_from,PDO::PARAM_INT);
		$stmt->bindValue(':time_code_to', $time_code_to,PDO::PARAM_INT);
		$stmt->execute();
		$count = $stmt->rowCount();
		print "<p id='sea_stmt'>検索結果は<span>{$count}</span>件です</p><BR>";
		}

} catch(PDOException $Exception){
	print "エラー :".$Exception->getMessage();
}


if($count < 1){
		print "検索結果がありません。<BR>";
}else{
?>	
	<table class="tablesorter" id="myTable">
		<thead class="thead" id="thead">
			<tr>
				<th>年月日</th>
				<th>時刻コード</th>
				<th>売り入札量(kWh)</th>
				<th>買い入札量(kWh)</th>
				<th>約定総量(kWh)</th>
				<th>システムプライス(円/kWh)</th>
				<th>エリアプライス北海道(円/kWh)</th>
				<th>エリアプライス東北(円/kWh)</th>
				<th>エリアプライス東京(円/kWh)</th>
				<th>エリアプライス中部(円/kWh)</th>
				<th>エリアプライス北陸(円/kWh)</th>
				<th>エリアプライス関西(円/kWh)</th>
				<th>エリアプライス中国(円/kWh)</th>
				<th>エリアプライス四国(円/kWh)</th>
				<th>エリアプライス九州(円/kWh)</th>
			</tr>
		</thead>

		<tbody class="tbody" id="tbody">
<?php
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
?>
			<tr id="record">
				<td><?php echo htmlspecialchars($row['trade_date']); ?> </td>
				<td><?php echo htmlspecialchars($row['time_code']); ?> </td>
				<td><?php echo htmlspecialchars($row['trade_sale']); ?> </td>
				<td><?php echo htmlspecialchars($row['trade_buy']); ?> </td>	
				<td><?php echo htmlspecialchars($row['trade_total']); ?> </td>
				<td><?php echo htmlspecialchars($row['system_price']); ?> </td>
				<td><?php echo htmlspecialchars($row['area_price_hokkaido']); ?> </td>
				<td><?php echo htmlspecialchars($row['area_price_tohoku']); ?> </td>
				<td><?php echo htmlspecialchars($row['area_price_tokyo']); ?> </td>
				<td><?php echo htmlspecialchars($row['area_price_tyubu']); ?> </td>
				<td><?php echo htmlspecialchars($row['area_price_hokuriku']); ?> </td>
				<td><?php echo htmlspecialchars($row['area_price_kansai']); ?> </td>
				<td><?php echo htmlspecialchars($row['area_price_tyugoku']); ?> </td>
				<td><?php echo htmlspecialchars($row['area_price_sikoku']); ?> </td>
				<td><?php echo htmlspecialchars($row['area_price_kyusyu']); ?> </td>
			</tr>
<?php	
	}
?>
		</tbody>
	</table>
<?php
}
?>
<p id='blank'></p>
</BODY>
</HTML>