<?php
date_default_timezone_set('Asia/Tokyo');

//pdoの設定
$dsn = 'mysql:host=mysql;dbname=JEPX datebase;charset=utf8';
$user = 'root';
$password = 'root';
try {
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
  // print("success!");
} catch (PDOException $Exception) {
  die('エラー :'.$Exception->getMessage());
}

$datavalue = $_POST['data_value'];
// var_dump($datavalue);
// exit();

if($datavalue=="spot"){

  $lines = fopen("http://www.jepx.org/market/excel/spot_{$_POST[data_dates]}.csv",'r');
  $file_header = fgetcsv($lines,1000,",");
  $column_counter = count($file_header);

  $today = new DateTime('now');
  $created_date = $today->format('Y-m-d H:i:s');
  $modified_date = $today->format('Y-m-d H:i:s');
  $logday = $today->format('Y-m-d');

  try{
    $i = 0;
    $j = 0;
    $k = 0;
    
    while(!feof($lines)){
      ++$k;
      $file_handler = fgetcsv($lines);
      if($column_counter !== count($file_handler)){
        print ($k)."番目のデータにエラーがあります。"."<br>";
        error_log(($k)."番目のデータにエラーがあります。\n",3,"./logfile/data_{$created_date}.log");
        continue;
      }
      $str = $file_handler[0];
      $date = new DateTime($str);
      $day = $date->format('Y-m-d');
      $sql = "SELECT*FROM  spot_market_result WHERE trade_date='{$day}' AND time_code={$file_handler[1]} AND del_flag = 0";
      $res = $pdo->query($sql);
      $id = null;
      foreach($res as $value){
        $id = $value[id];
      }


      if($id !== null){
        $pdo->beginTransaction();
        $sql1 = "UPDATE  spot_market_result
                SET del_flag = {$id},modified = '{$modified_date}'
                WHERE id = {$id}";
        $sql2 = "INSERT INTO  spot_market_result(trade_date,time_code,created,modified,trade_sale,trade_buy,trade_total,
                system_price,area_price_hokkaido,area_price_tohoku,area_price_tokyo,area_price_tyubu,
                area_price_hokuriku,area_price_kansai,area_price_tyugoku,area_price_sikoku,area_price_kyusyu,avg_price_for_spot,
                avg_price_for_spot_and_a_max,avg_price_for_spot_and_a_min,avg_price_for_spot_and_a_pre,
                avg_price_for_spot_and_a_conf,aboidable_cost_Nationwide,aboidable_cost_hokkaido,aboidable_cost_tohoku,
                aboidable_cost_tokyo,aboidable_cost_tyubu,aboidable_cost_hokuriku,aboidable_cost_kansai,
                aboidable_cost_tyugoku,aboidable_cost_sikoku,aboidable_cost_kyusyu)
                VALUES
                (:trade_date,:time_code,'{$created_date}','{$modified_date}',:trade_sale,:trade_buy,:trade_total,
                :system_price,:area_price_hokkaido,:area_price_tohoku,:area_price_tokyo,:area_price_tyubu,
                :area_price_hokuriku,:area_price_kansai,:area_price_tyugoku,:area_price_sikoku,:area_price_kyusyu,:avg_price_for_spot,
                :avg_price_for_spot_and_a_max,:avg_price_for_spot_and_a_min,:avg_price_for_spot_and_a_pre,
                :avg_price_for_spot_and_a_conf,:aboidable_cost_Nationwide,:aboidable_cost_hokkaido,:aboidable_cost_tohoku,
                :aboidable_cost_tokyo,:aboidable_cost_tyubu,:aboidable_cost_hokuriku,:aboidable_cost_kansai,
                :aboidable_cost_tyugoku,:aboidable_cost_sikoku,:aboidable_cost_kyusyu)";
        
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute();
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->bindValue(':trade_date',$day,PDO::PARAM_STR);
        $stmt2->bindValue(':time_code',$file_handler[1],PDO::PARAM_INT);
        $stmt2->bindValue(':trade_sale',$file_handler[2],PDO::PARAM_INT);
        $stmt2->bindValue(':trade_buy',$file_handler[3],PDO::PARAM_INT);
        $stmt2->bindValue(':trade_total',$file_handler[4],PDO::PARAM_INT);
        $stmt2->bindValue(':system_price',$file_handler[5],PDO::PARAM_INT);
        $stmt2->bindValue(':area_price_hokkaido',$file_handler[6],PDO::PARAM_INT);
        $stmt2->bindValue(':area_price_tohoku',$file_handler[7],PDO::PARAM_INT);
        $stmt2->bindValue(':area_price_tokyo',$file_handler[8],PDO::PARAM_INT);
        $stmt2->bindValue(':area_price_tyubu',$file_handler[9],PDO::PARAM_INT);
        $stmt2->bindValue(':area_price_hokuriku',$file_handler[10],PDO::PARAM_INT);
        $stmt2->bindValue(':area_price_kansai',$file_handler[11],PDO::PARAM_INT);
        $stmt2->bindValue(':area_price_tyugoku',$file_handler[12],PDO::PARAM_INT);
        $stmt2->bindValue(':area_price_sikoku',$file_handler[13],PDO::PARAM_INT);
        $stmt2->bindValue(':area_price_kyusyu',$file_handler[14],PDO::PARAM_INT);
        $stmt2->bindValue(':avg_price_for_spot',$file_handler[16],PDO::PARAM_INT);
        $stmt2->bindValue(':avg_price_for_spot_and_a_max',$file_handler[17],PDO::PARAM_INT);
        $stmt2->bindValue(':avg_price_for_spot_and_a_min',$file_handler[18],PDO::PARAM_INT);
        $stmt2->bindValue(':avg_price_for_spot_and_a_pre',$file_handler[19],PDO::PARAM_INT);
        $stmt2->bindValue(':avg_price_for_spot_and_a_conf',$file_handler[20],PDO::PARAM_INT);
        $stmt2->bindValue(':aboidable_cost_Nationwide',$file_handler[22],PDO::PARAM_INT);
        $stmt2->bindValue(':aboidable_cost_hokkaido',$file_handler[23],PDO::PARAM_INT);
        $stmt2->bindValue(':aboidable_cost_tohoku',$file_handler[24],PDO::PARAM_INT);
        $stmt2->bindValue(':aboidable_cost_tokyo',$file_handler[25],PDO::PARAM_INT);
        $stmt2->bindValue(':aboidable_cost_tyubu',$file_handler[26],PDO::PARAM_INT);
        $stmt2->bindValue(':aboidable_cost_hokuriku',$file_handler[27],PDO::PARAM_INT);
        $stmt2->bindValue(':aboidable_cost_kansai',$file_handler[28],PDO::PARAM_INT);
        $stmt2->bindValue(':aboidable_cost_tyugoku',$file_handler[29],PDO::PARAM_INT);
        $stmt2->bindValue(':aboidable_cost_sikoku',$file_handler[30],PDO::PARAM_INT);
        $stmt2->bindValue(':aboidable_cost_kyusyu',$file_handler[31],PDO::PARAM_INT);
        $stmt2->execute();
        $pdo->commit();
        ++$i;
      }else{
        $pdo->beginTransaction();
        $sql3 = "INSERT INTO  spot_market_result(trade_date,time_code,created,modified,trade_sale,trade_buy,trade_total,
                system_price,area_price_hokkaido,area_price_tohoku,area_price_tokyo,area_price_tyubu,
                area_price_hokuriku,area_price_kansai,area_price_tyugoku,area_price_sikoku,area_price_kyusyu,avg_price_for_spot,
                avg_price_for_spot_and_a_max,avg_price_for_spot_and_a_min,avg_price_for_spot_and_a_pre,
                avg_price_for_spot_and_a_conf,aboidable_cost_Nationwide,aboidable_cost_hokkaido,aboidable_cost_tohoku,
                aboidable_cost_tokyo,aboidable_cost_tyubu,aboidable_cost_hokuriku,aboidable_cost_kansai,
                aboidable_cost_tyugoku,aboidable_cost_sikoku,aboidable_cost_kyusyu)
                VALUES
                (:trade_date,:time_code,'{$created_date}','{$modified_date}',:trade_sale,:trade_buy,:trade_total,
                :system_price,:area_price_hokkaido,:area_price_tohoku,:area_price_tokyo,:area_price_tyubu,
                :area_price_hokuriku,:area_price_kansai,:area_price_tyugoku,:area_price_sikoku,:area_price_kyusyu,:avg_price_for_spot,
                :avg_price_for_spot_and_a_max,:avg_price_for_spot_and_a_min,:avg_price_for_spot_and_a_pre,
                :avg_price_for_spot_and_a_conf,:aboidable_cost_Nationwide,:aboidable_cost_hokkaido,:aboidable_cost_tohoku,
                :aboidable_cost_tokyo,:aboidable_cost_tyubu,:aboidable_cost_hokuriku,:aboidable_cost_kansai,
                :aboidable_cost_tyugoku,:aboidable_cost_sikoku,:aboidable_cost_kyusyu)";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->bindValue(':trade_date',$file_handler[0],PDO::PARAM_STR);
        $stmt3->bindValue(':time_code',$file_handler[1],PDO::PARAM_INT);
        $stmt3->bindValue(':trade_sale',$file_handler[2],PDO::PARAM_INT);
        $stmt3->bindValue(':trade_buy',$file_handler[3],PDO::PARAM_INT);
        $stmt3->bindValue(':trade_total',$file_handler[4],PDO::PARAM_INT);
        $stmt3->bindValue(':system_price',$file_handler[5],PDO::PARAM_INT);
        $stmt3->bindValue(':area_price_hokkaido',$file_handler[6],PDO::PARAM_INT);
        $stmt3->bindValue(':area_price_tohoku',$file_handler[7],PDO::PARAM_INT);
        $stmt3->bindValue(':area_price_tokyo',$file_handler[8],PDO::PARAM_INT);
        $stmt3->bindValue(':area_price_tyubu',$file_handler[9],PDO::PARAM_INT);
        $stmt3->bindValue(':area_price_hokuriku',$file_handler[10],PDO::PARAM_INT);
        $stmt3->bindValue(':area_price_kansai',$file_handler[11],PDO::PARAM_INT);
        $stmt3->bindValue(':area_price_tyugoku',$file_handler[12],PDO::PARAM_INT);
        $stmt3->bindValue(':area_price_sikoku',$file_handler[13],PDO::PARAM_INT);
        $stmt3->bindValue(':area_price_kyusyu',$file_handler[14],PDO::PARAM_INT);
        $stmt3->bindValue(':avg_price_for_spot',$file_handler[16],PDO::PARAM_INT);
        $stmt3->bindValue(':avg_price_for_spot_and_a_max',$file_handler[17],PDO::PARAM_INT);
        $stmt3->bindValue(':avg_price_for_spot_and_a_min',$file_handler[18],PDO::PARAM_INT);
        $stmt3->bindValue(':avg_price_for_spot_and_a_pre',$file_handler[19],PDO::PARAM_INT);
        $stmt3->bindValue(':avg_price_for_spot_and_a_conf',$file_handler[20],PDO::PARAM_INT);
        $stmt3->bindValue(':aboidable_cost_Nationwide',$file_handler[22],PDO::PARAM_INT);
        $stmt3->bindValue(':aboidable_cost_hokkaido',$file_handler[23],PDO::PARAM_INT);
        $stmt3->bindValue(':aboidable_cost_tohoku',$file_handler[24],PDO::PARAM_INT);
        $stmt3->bindValue(':aboidable_cost_tokyo',$file_handler[25],PDO::PARAM_INT);
        $stmt3->bindValue(':aboidable_cost_tyubu',$file_handler[26],PDO::PARAM_INT);
        $stmt3->bindValue(':aboidable_cost_hokuriku',$file_handler[27],PDO::PARAM_INT);
        $stmt3->bindValue(':aboidable_cost_kansai',$file_handler[28],PDO::PARAM_INT);
        $stmt3->bindValue(':aboidable_cost_tyugoku',$file_handler[29],PDO::PARAM_INT);
        $stmt3->bindValue(':aboidable_cost_sikoku',$file_handler[30],PDO::PARAM_INT);
        $stmt3->bindValue(':aboidable_cost_kyusyu',$file_handler[31],PDO::PARAM_INT);
        $stmt3->execute();
        $pdo->commit();
        ++$j;
      }
    }



    fclose($lines);


    if($i > 0){
      print "{$_POST[data_dates]}年度のスポット市場のレコードを".$i."件更新しました。"."<br>";
      error_log("{$created_date}"."   ",3,"./logfile/data_{$logday}.log");
      error_log("{$_POST[data_dates]}年度のスポット市場のレコードを".$i."件更新しました。\n",3,"./logfile/data_{$logday}.log");
    }

    if($j > 0){
      print "{$_POST[data_dates]}年度のスポット市場のデータを".$j."件挿入しました。"."<br>";
      error_log("{$created_date}"." ",3,"./logfile/data_{$logday}.log");
      error_log("{$_POST[data_dates]}年度のスポット市場のデータを".$j."件挿入しました。\n",3,"./logfile/data_{$logday}.log");
    }

  }catch (PDOException $Exception){
    $pdo->rollBack();
    $error = $Exception->getMessage();
    print $error;
    if(strpos($error, 'Duplicate')!==false){
      print "エラー :重複するデータが存在します。"."<br>";
    }elseif(strpos($error, 'syntax')!==false){
      print "エラー :文法に誤りがあります。"."<br>";
    }else{
      print $Exception->getMessage()."<br>";
      print "不明なエラー";
    }
  }

}else if($datavalue=="pre_time"){

  $lines2 = fopen("http://www.jepx.org/market/excel/im_trade_summary_{$_POST[data_dates]}.csv",'r');
  $file_header2 = fgetcsv($lines2,1000,",");
  $column_counter2 = count($file_header2);

  $today = new DateTime('now');
  $created_date = $today->format('Y-m-d H:i:s');
  $modified_date = $today->format('Y-m-d H:i:s');
  $logday = $today->format('Y-m-d');


  try{
    $l = 0;
    $m = 0;
    $n = 0;

    while(!feof($lines2)){
      ++$n;
      $file_handler2 = fgetcsv($lines2);
      if($column_counter2 !== count($file_handler2)){
        print ($n)."番目のデータにエラーがあります。"."<br>";
        error_log(($n)."番目のデータにエラーがあります。\n",3,"./logfile/data_{$created_date}.log");
        continue;
      }
      $str = $file_handler2[0];
      $date = new DateTime($str);
      $day = $date->format('Y-m-d');
      $sql = "SELECT*FROM  pretime_market_result WHERE trade_date='{$day}' AND time_code={$file_handler2[1]} AND del_flag = 0";
      $res = $pdo->query($sql);
      $id = null;
      foreach($res as $value){
        $id = $value[id];
      }

      if($id !== null){
        $pdo->beginTransaction();
        $sql3 = "UPDATE  pretime_market_result
                SET del_flag = {$id},modified = '{$modified_date}'
                WHERE id = {$id}";
        $sql4 = "INSERT INTO  pretime_market_result(trade_date,time_code,created,modified,open_price,
                                    high_price,low_price,closing_price,avg_price,total_execution,
                                    execution_count)
                VALUES
                (:trade_date,:time_code,:created,:modified,:open_price,
                :high_price,:low_price,:closing_price,:avg_price,:total_execution,:execution_count)";
        $stmt4 = $pdo->prepare($sql3);
        $stmt4->execute();
        $stmt5 = $pdo->prepare($sql4);
        $stmt5->bindValue(':trade_date',$day,PDO::PARAM_STR);
        $stmt5->bindValue(':time_code',$file_handler2[1],PDO::PARAM_INT);
        $stmt5->bindValue(':created',$created_date,PDO::PARAM_STR);
        $stmt5->bindValue(':modified',$modified_date,PDO::PARAM_STR);
        $stmt5->bindValue(':open_price',$file_handler2[2],PDO::PARAM_INT);
        $stmt5->bindValue(':high_price',$file_handler2[3],PDO::PARAM_INT);
        $stmt5->bindValue(':low_price',$file_handler2[4],PDO::PARAM_INT);
        $stmt5->bindValue(':closing_price',$file_handler2[5],PDO::PARAM_INT);
        $stmt5->bindValue(':avg_price',$file_handler2[6],PDO::PARAM_INT);
        $stmt5->bindValue(':total_execution',$file_handler2[7],PDO::PARAM_INT);
        $stmt5->bindValue(':execution_count',$file_handler2[8],PDO::PARAM_INT);
        $stmt5->execute();
        $pdo->commit();
        ++$l;
      }else{
        
        $pdo->beginTransaction();
        $sql5 = "INSERT INTO  pretime_market_result(trade_date,time_code,created,modified,open_price,
                                    high_price,low_price,closing_price,avg_price,total_execution,
                                    execution_count)
                VALUES
                (:trade_date,:time_code,:created,:modified,:open_price,
                :high_price,:low_price,:closing_price,:avg_price,:total_execution,:execution_count)";
        $stmt6 = $pdo->prepare($sql5);
        $stmt6->bindValue(':trade_date',$day,PDO::PARAM_STR);
        $stmt6->bindValue(':time_code',$file_handler2[1],PDO::PARAM_STR);
        $stmt6->bindValue(':created',$created_date,PDO::PARAM_STR);
        $stmt6->bindValue(':modified',$modified_date,PDO::PARAM_STR);
        $stmt6->bindValue(':open_price',$file_handler2[2],PDO::PARAM_INT);
        $stmt6->bindValue(':high_price',$file_handler2[3],PDO::PARAM_INT);
        $stmt6->bindValue(':low_price',$file_handler2[4],PDO::PARAM_INT);
        $stmt6->bindValue(':closing_price',$file_handler2[5],PDO::PARAM_INT);
        $stmt6->bindValue(':avg_price',$file_handler2[6],PDO::PARAM_INT);
        $stmt6->bindValue(':total_execution',$file_handler2[7],PDO::PARAM_INT);
        $stmt6->bindValue(':execution_count',$file_handler2[8],PDO::PARAM_INT);
        $stmt6->execute();
        $pdo->commit();
        $m++;
      }
    }

    if($l > 0){
      print "{$_POST[data_dates]}年度の時間前市場のレコードを".$l."件更新しました。"."<br>";
      error_log("{$created_date}"."   ",3,"./logfile/data_{$logday}.log");
      error_log("{$_POST[data_dates]}年度の時間前市場のレコードを".$l."件更新しました。\n",3,"./logfile/data_{$logday}.log");
    }

    if($m > 0){
      print "{$_POST[data_dates]}年度の時間前市場のデータを".$m."件挿入しました。"."<br>";
      error_log("{$created_date}"." ",3,"./logfile/data_{$logday}.log");
      error_log("{$_POST[data_dates]}年度の時間前市場のデータを".$m."件挿入しました。\n",3,"./logfile/data_{$logday}.log");
    }

  }catch (PDOException $Exception){
    $pdo->rollBack();
    $error = $Exception->getMessage();
    print $error;
    if(strpos($error, 'Duplicate')!==false){
      print "エラー :重複するデータが存在します。"."<br>";
    }elseif(strpos($error, 'syntax')!==false){
      print "エラー :文法に誤りがあります。"."<br>";
    }else{
      print $Exception->getMessage()."<br>";
      print "不明なエラー";
    }
  }
}
?>