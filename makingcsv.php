<?php
date_default_timezone_set('Asia/Tokyo');
$today = new DateTime('now');
$created_date = $today->format('YmdHis');
$data_value = $_GET['data_value'];

// 1.作成するCSVの年度を指定

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
$trade_date_4 = $_GET['trade_date_4'];
if($_GET['trade_date_5'] < 10){
	$trade_date_5 = '0'.$_GET['trade_date_5'];
}else{
	$trade_date_5 = $_GET['trade_date_5'];
}
if($_GET['trade_date_6'] < 10){
	$trade_date_6 = '0'.$_GET['trade_date_6'];
}else{
	$trade_date_6 = $_GET['trade_date_6'];
}

$trade_date_from = $trade_date_1.'-'.$trade_date_2.'-'.$trade_date_3;
$trade_date_to = $trade_date_4.'-'.$trade_date_5.'-'.$trade_date_6;

// ---検索日付の始点のほうが終点よりも大きい場合、日付の入れ替えを行う---
if(strtotime($trade_date_from) > strtotime($trade_date_to)){
	$trade_date_to = $trade_date_1.'-'.$trade_date_2.'-'.$trade_date_3;
	$trade_date_from = $trade_date_4.'-'.$trade_date_5.'-'.$trade_date_6;
}

// print_r(strtotime($trade_date_to));
// exit();

// ---時刻コードの設定---
$time_code_from = $_GET['time_code_from'];
$time_code_to = $_GET['time_code_to'];

// ---検索する時刻コードの始点のほうが終点よりも大きい場合、時刻コードの入れ替えを行う---
if($time_code_from > $time_code_to){
	$time_code_from =  $_GET['time_code_to'];
	$time_code_to = $_GET['time_code_from'];
}

// 2.データベースに接続

$dsn = 'mysql:host=mysql;dbname=JEPX datebase';
$user = 'root';
$password = 'root';

try {

	$pdo = new PDO($dsn, $user, $password);
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
  // print(success);
  // exit();

} catch (PDOException $Exception) {

	die('エラー :'.$Exception->getMessage());

}

if($data_value == 'spot'){

  try {
    if($time_code_from==='0' and $time_code_to==='0'){
      $sql ="SELECT * FROM spot_market_result
           WHERE trade_date BETWEEN :trade_date_from AND :trade_date_to
           AND del_flag = 0";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':trade_date_from', $trade_date_from,PDO::PARAM_STR);
      $stmt->bindValue(':trade_date_to', $trade_date_to,PDO::PARAM_STR);
      $stmt->execute();
    }else{
    $sql ="SELECT * FROM  spot_market_result
           WHERE trade_date BETWEEN :trade_date_from AND :trade_date_to
           AND time_code BETWEEN :time_code_from AND :time_code_to
           AND del_flag = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':trade_date_from', $trade_date_from,PDO::PARAM_STR);
    $stmt->bindValue(':trade_date_to', $trade_date_to,PDO::PARAM_STR);
    $stmt->bindValue(':time_code_from', $time_code_from,PDO::PARAM_INT);
    $stmt->bindValue(':time_code_to', $time_code_to,PDO::PARAM_INT);
    $stmt->execute();
    // print(success);
    // exit();
    }
  }catch(PDOException $Exception){
    print "エラー :".$Exception->getMessage();
  }
  
  // 4.該当データからCSVを作成する
  $header =  array("年月日","時刻コード","売り入札量(kWh)","買い入札量(kWh)","約定総量(kWh)","システムプライス(円/kWh)","エリアプライス北海道(円/kWh)","エリアプライス東北(円/kWh)","エリアプライス東京(円/kWh)","エリアプライス中部(円/kWh)","エリアプライス北陸(円/kWh)","エリアプライス関西(円/kWh)","エリアプライス中国(円/kWh)","エリアプライス四国(円/kWh)","エリアプライス九州(円/kWh)","スポット・時間前平均価格(円/kWh)","α上限値×スポット・時間前平均価格(円/kWh)","α下限値×スポット・時間前平均価格(円/kWh)","α速報値×スポット・時間前平均価格(円/kWh)","回避可能原価全国値(円/kWh)","回避可能原価北海道(円/kWh)","回避可能原価東北(円/kWh)","回避可能原価東京(円/kWh)","回避可能原価中部(円/kWh)","回避可能原価北陸(円/kWh)","回避可能原価関西(円/kWh)","回避可能原価中国(円/kWh)","回避可能原価四国(円/kWh)","回避可能原価九州(円/kWh)");
  $header_2 = array();
  for($i=0; $i < count($header); $i++){
    $convert_val = mb_convert_encoding($header[$i],"SJIS","UTF-8");
    array_push($header_2,$convert_val);
  }
  
  $filepath = "./jpex_{$created_date}.csv";
  $file = new SplFileObject($filepath,"w");
  $file->fputcsv($header_2);
  
  $ct = 0;
  while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
    $row['trade_date'] = mb_convert_encoding($row['trade_date'],"SJIS","UTF-8");
    $file->fwrite($row['trade_date'].",");
    $row['time_code'] = mb_convert_encoding($row['time_code'],"SJIS","UTF-8");
    $file->fwrite($row['time_code'].",");
    $row['trade_sale'] = mb_convert_encoding($row['trade_sale'],"SJIS","UTF-8");
    $file->fwrite($row['trade_sale'].",");
    $row['trade_buy'] = mb_convert_encoding($row['trade_buy'],"SJIS","UTF-8");
    $file->fwrite($row['trade_buy'].",");
    $row['trade_total'] = mb_convert_encoding($row['trade_total'],"SJIS","UTF-8");
    $file->fwrite($row['trade_total'].",");
    $row['system_price'] = mb_convert_encoding($row['system_price'],"SJIS","UTF-8");
    $file->fwrite($row['system_price'].",");
    $row['area_price_hokkaido'] = mb_convert_encoding($row['area_price_hokkaido'],"SJIS","UTF-8");
    $file->fwrite($row['area_price_hokkaido'].",");
    $row['area_price_tohoku'] = mb_convert_encoding($row['area_price_tohoku'],"SJIS","UTF-8");
    $file->fwrite($row['area_price_tohoku'].",");
    $row['area_price_tokyo'] = mb_convert_encoding($row['area_price_tokyo'],"SJIS","UTF-8");
    $file->fwrite($row['area_price_tokyo'].",");
    $row['area_price_tyubu'] = mb_convert_encoding($row['area_price_tyubu'],"SJIS","UTF-8");
    $file->fwrite($row['area_price_tyubu'].",");
    $row['area_price_hokuriku'] = mb_convert_encoding($row['area_price_hokuriku'],"SJIS","UTF-8");
    $file->fwrite($row['area_price_hokuriku'].",");
    $row['area_price_kansai'] = mb_convert_encoding($row['area_price_kansai'],"SJIS","UTF-8");
    $file->fwrite($row['area_price_kansai'].",");
    $row['area_price_tyugoku'] = mb_convert_encoding($row['area_price_tyugoku'],"SJIS","UTF-8");
    $file->fwrite($row['area_price_tyugoku'].",");
    $row['area_price_sikoku'] = mb_convert_encoding($row['area_price_sikoku'],"SJIS","UTF-8");
    $file->fwrite($row['area_price_sikoku'].",");
    $row['area_price_kyusyu'] = mb_convert_encoding($row['area_price_kyusyu'],"SJIS","UTF-8");
    $file->fwrite($row['area_price_kyusyu'].",");
    $row['avg_price_for_spot'] = mb_convert_encoding($row['avg_price_for_spot'],"SJIS","UTF-8");
    $file->fwrite($row['avg_price_for_spot'].",");
    $row['avg_price_for_spot_and_a_max'] = mb_convert_encoding($row['avg_price_for_spot_and_a_max'],"SJIS","UTF-8");
    $file->fwrite($row['avg_price_for_spot_and_a_max'].",");
    $row['avg_price_for_spot_and_a_min'] = mb_convert_encoding($row['avg_price_for_spot_and_a_min'],"SJIS","UTF-8");
    $file->fwrite($row['avg_price_for_spot_and_a_min'].",");
    $row['avg_price_for_spot_and_a_pre'] = mb_convert_encoding($row['avg_price_for_spot_and_a_pre'],"SJIS","UTF-8");
    $file->fwrite($row['avg_price_for_spot_and_a_pre'].",");
    $row['aboidable_cost_Nationwide'] = mb_convert_encoding($row['aboidable_cost_Nationwide'],"SJIS","UTF-8");
    // $file->fwrite($row['avg_price_for_spot_and_a_conf'].",");
    $file->fwrite($row['aboidable_cost_Nationwide'].",");
    $row['aboidable_cost_hokkaido'] = mb_convert_encoding($row['aboidable_cost_hokkaido'],"SJIS","UTF-8");
    $file->fwrite($row['aboidable_cost_hokkaido'].",");
    $row['aboidable_cost_tohoku'] = mb_convert_encoding($row['aboidable_cost_tohoku'],"SJIS","UTF-8");
    $file->fwrite($row['aboidable_cost_tohoku'].",");
    $row['aboidable_cost_tokyo'] = mb_convert_encoding($row['aboidable_cost_tokyo'],"SJIS","UTF-8");
    $file->fwrite($row['aboidable_cost_tokyo'].",");
    $row['aboidable_cost_tyubu'] = mb_convert_encoding($row['aboidable_cost_tyubu'],"SJIS","UTF-8");
    $file->fwrite($row['aboidable_cost_tyubu'].",");
    $row['aboidable_cost_hokuriku'] = mb_convert_encoding($row['aboidable_cost_hokuriku'],"SJIS","UTF-8");
    $file->fwrite($row['aboidable_cost_hokuriku'].",");
    $row['aboidable_cost_kansai'] = mb_convert_encoding($row['aboidable_cost_kansai'],"SJIS","UTF-8");
    $file->fwrite($row['aboidable_cost_kansai'].",");
    $row['aboidable_cost_tyugoku'] = mb_convert_encoding($row['aboidable_cost_tyugoku'],"SJIS","UTF-8");
    $file->fwrite($row['aboidable_cost_tyugoku'].",");
    $row['aboidable_cost_sikoku'] = mb_convert_encoding($row['aboidable_cost_sikoku'],"SJIS","UTF-8");
    $file->fwrite($row['aboidable_cost_sikoku'].",");
    $row['aboidable_cost_kyusyu'] = mb_convert_encoding($row['aboidable_cost_kyusyu'],"SJIS","UTF-8");
    $file->fwrite($row['aboidable_cost_kyusyu']."\n");
    $ct++;
  }
  if($ct==0){
    echo ("作成できるCSVデータが存在しません。"."\n");
    echo ("      ");
    echo ("  ");
    echo ("\n");
    echo ("\n");
    echo "<a href = './downloadcsv.php'>前のページに戻る</a>";
    unlink("./jpex_{$trade_date_1}.csv");
    exit();
  }
  
  
  // 5.CSVをダウンロード
  
  header('Content-Type: application/octet-stream');
  header('Content-Length:'.filesize($filepath));
  header('Content-Disposition: attachment; filename="jpex_'.$trade_date_1.'.csv"');
  readfile($filepath);
  unlink($filepath);
  // error_log("{$trade_date_from}から{$trade_date_to}までのCSVを作成しました。",3,"./logfile/data_{$created_date}.log");




}else{
  try {
    if($time_code_from==='0' and $time_code_to==='0'){
      $sql ="SELECT * FROM  pretime_market_result
           WHERE trade_date BETWEEN :trade_date_from AND :trade_date_to
           AND del_flag = 0";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':trade_date_from', $trade_date_from,PDO::PARAM_STR);
      $stmt->bindValue(':trade_date_to', $trade_date_to,PDO::PARAM_STR);
      $stmt->execute();
    }else{
    $sql ="SELECT * FROM  spot_market_result
           WHERE trade_date BETWEEN :trade_date_from AND :trade_date_to
           AND time_code BETWEEN :time_code_from AND :time_code_to
           AND del_flag = 0";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':trade_date_from', $trade_date_from,PDO::PARAM_STR);
    $stmt->bindValue(':trade_date_to', $trade_date_to,PDO::PARAM_STR);
    $stmt->bindValue(':time_code_from', $time_code_from,PDO::PARAM_INT);
    $stmt->bindValue(':time_code_to', $time_code_to,PDO::PARAM_INT);
    $stmt->execute();
    // print(success);
    // exit();
    }
  }catch(PDOException $Exception){
    print "エラー :".$Exception->getMessage();
  }
  
  // 4.該当データからCSVを作成する
  $header =  array("年月日","時刻コード","始値(円/kWh)","高値（円/kWh）","安値（円/kWh）","終値（円/kWh）","平均（円/kWh）","約定量合計（MWh/h）","約定件数");
  $header_2 = array();
  for($i=0; $i < count($header); $i++){
    $convert_val = mb_convert_encoding($header[$i],"SJIS","UTF-8");
    array_push($header_2,$convert_val);
  }
  
  $filepath = "./im_trade_summary_{$created_date}.csv";
  $file = new SplFileObject($filepath,"w");
  $file->fputcsv($header_2);
  
  $ct = 0;
  while($row =$stmt->fetch(PDO::FETCH_ASSOC)){
    $row['trade_date'] = mb_convert_encoding($row['trade_date'],"SJIS","UTF-8");
    $file->fwrite($row['trade_date'].",");
    $row['time_code'] = mb_convert_encoding($row['time_code'],"SJIS","UTF-8");
    $file->fwrite($row['time_code'].",");
    $row['open_price'] = mb_convert_encoding($row['open_price'],"SJIS","UTF-8");
    $file->fwrite($row['open_price'].",");
    $row['high_price'] = mb_convert_encoding($row['high_price'],"SJIS","UTF-8");
    $file->fwrite($row['high_price'].",");
    $row['low_price'] = mb_convert_encoding($row['low_price'],"SJIS","UTF-8");
    $file->fwrite($row['low_price'].",");
    $row['closing_price'] = mb_convert_encoding($row['closing_price'],"SJIS","UTF-8");
    $file->fwrite($row['closing_price'].",");
    $row['avg_price'] = mb_convert_encoding($row['avg_price'],"SJIS","UTF-8");
    $file->fwrite($row['avg_price'].",");
    $row['total_execution'] = mb_convert_encoding($row['total_execution'],"SJIS","UTF-8");
    $file->fwrite($row['total_execution'].",");
    $row['execution_count'] = mb_convert_encoding($row['execution_count'],"SJIS","UTF-8");
    $file->fwrite($row['execution_count']."\n");
    $ct++;
  }
  if($ct==0){
    echo ("作成できるCSVデータが存在しません。"."\n");
    echo ("      ");
    echo ("  ");
    echo ("\n");
    echo ("\n");
    echo "<a href = './downloadcsv.php'>前のページに戻る</a>";
    unlink("./im_trade_summary_{$created_date}.csv");
    exit();
  }
  
  
  // 5.CSVをダウンロード
  
  header('Content-Type: application/octet-stream');
  header('Content-Length:'.filesize($filepath));
  header('Content-Disposition: attachment; filename="im_trade_summary_'.$created_date.'.csv"');
  readfile($filepath);
  unlink($filepath);
}

// 3.データベースから該当データを取得

