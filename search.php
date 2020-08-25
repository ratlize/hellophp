<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Mincho&display=swap" rel="stylesheet">

    <!-- 検索時点での西暦を取得 -->
    <?php
      date_default_timezone_set('Asia/Tokyo');
      $today = new DateTime('now');
      $theyear = $today->format('Y');
    ?>

        <!-- javascript -->
    <script type="text/javascript" src="jquery-3.5.1.js"></script>
    <script type="text/javascript" src="jquery.blockUI.js"></script>
    <script src="https://d3js.org/d3.v5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.1/js/jquery.tablesorter.min.js"></script>
    <script src='./tablesorter.js'></script>
    <script type="text/javascript">
        $(function () {
          $('form').submit(function () {
            $(this).find('#double').prop('disabled', true);
            setTimeout(function(){$('#double').prop('disabled', false)},10000);
          });
        });
      </script>
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
    <script>
          var values = [];
          var param = location.search.substring(1).split('&');
          for(var i = 0; i < param.length; i++) {
            var element = param[i].split('=');
            values.push(element);
          }


      $(function(){
        var trade_date_1 = values[0][1];
        var now_select = $("#year1").find("option[value="+ trade_date_1 +"]");
        $(now_select).prop('selected',true);
      })
      $(function(){
        var trade_date_2 = values[1][1];
        var now_select = $("#month1").find("option[value="+ trade_date_2 +"]");
        $(now_select).prop('selected',true);
      })
      $(function(){
        var trade_date_3 = values[2][1];
        var now_select = $("#day1").find("option[value="+ trade_date_3 +"]");
        $(now_select).prop('selected',true);
      })
      $(function(){
        var trade_date_4 = values[3][1];
        var now_select = $("#year2").find("option[value="+ trade_date_4 +"]");
        $(now_select).prop('selected',true);
      })
      $(function(){
        var trade_date_5 = values[4][1];
        var now_select = $("#month2").find("option[value="+ trade_date_5 +"]");
        $(now_select).prop('selected',true);
      })
      $(function(){
        var trade_date_6 = values[5][1];
        var now_select = $("#day2").find("option[value="+ trade_date_6 +"]");
        $(now_select).prop('selected',true);
      })
      $(function(){
        var time_code_from = values[6][1];
        var now_select =$("#hour1").find("option[value=" + time_code_from +"]");
        $(now_select).prop('selected',true);
      })
      $(function(){
        var time_code_to = values[7][1];
        var now_select =$("#hour2").find("option[value=" + time_code_to +"]");
        $(now_select).prop('selected',true);
      })
    </script>
  </head>
  <body>
    <header class='header_alt'>
      <h1><a href='./search.php' id='link'> スポット市場取引結果</a> </h1>
      <img src="./img/logo.png">
    </header>
    <div class='subbtn headmenu' id='transfer' for='transfer'>
        <button  type='button' id='in_transfer'>
          <a href="./downloadcsv.php" >ダウンロードページへ</a>
        </button>
        <button  type='button' id='in_transfer'>
          <a href="./chart.php" >グラフページへ</a>
        </button>
        <button  type='button' id='in_transfer'>
          <a href="./search_pretime.php" >時間前市場取引結果へ</a>
        </button>
        <button  type='button' id='in_transfer'>
          <a href="./joinsearch.php" >スポット・時間前市場取引結果へ</a>
        </button>
        <button  type='button' id='in_transfer'>
          <a href="./sumdata.php" >日ごと合計値へ</a>
        </button>
      </div>
    <div class='hmenu'>
       <!-- ここから検索フォーム -->
      <form class="searchPanel" action='search.php' method='get'>
        <h2> 検索条件を指定してください</h2>
        <div class="wrapper">
            <div class="data" >
              <dt>
                <h3 id="hiduke">日付</h3>
              </dt>
              <dd id="fx1">
                <div class="selectBox">
                  <select id="year1" name="trade_date_1">
                    <?php for($i=2005; $i<=$theyear; $i++):?>
                      <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor?>
                  </select>
                </div>
                <span>年</span>
                <div class="selectBox">
                  <select id="month1" name='trade_date_2'>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4" selected>4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                </div>
                <span>月</span>
                <div class="selectBox" >
                  <select id="day1" name='trade_date_3'>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    </select>
                  </select>
                </div>
                <span>日</span>
              </dd>
              <div>
                <p>から</p>
              </div>
              <dd id="fx1">
                <div class="selectBox">
                <select id="year2" name="trade_date_4">
                    <?php for($i=2005; $i<=$theyear; $i++):?>
                      <option value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor?>
                  </select>
                </div>
                <span>年</span>
                <div class="selectBox">
                  <select id="month2" name='trade_date_5'>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4" selected>4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                </div>
                <span>月</span>
                <div class="selectBox" >
                  <select id="day2" name='trade_date_6'>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    </select>
                  </select>
                </div>
                <span>日</span>
              </dd>
            </div>
            <div class="time">
              <dt>
                <h3>時刻コード</h3>
              </dt>
              <dd id="fx2">
                <div class="selectBox">
                  <select id="hour1" name='time_code_from' >
                    <option value="0">指定なし</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    <option value="32">32</option>
                    <option value="33">33</option>
                    <option value="34">34</option>
                    <option value="35">35</option>
                    <option value="36">36</option>
                    <option value="37">37</option>
                    <option value="38">38</option>
                    <option value="39">39</option>
                    <option value="40">40</option>
                    <option value="41">41</option>
                    <option value="42">42</option>
                    <option value="43">43</option>
                    <option value="44">44</option>
                    <option value="45">45</option>
                    <option value="46">46</option>
                    <option value="47">47</option>
                    <option value="48">48</option>
                  </select>
                </div>
                <div>
                  から
                </div>
                <div class="selectBox">
                  <select id="hour2" name='time_code_to' >
                    <option value="0">指定なし</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                    <option value="29">29</option>
                    <option value="30">30</option>
                    <option value="31">31</option>
                    <option value="32">32</option>
                    <option value="33">33</option>
                    <option value="34">34</option>
                    <option value="35">35</option>
                    <option value="36">36</option>
                    <option value="37">37</option>
                    <option value="38">38</option>
                    <option value="39">39</option>
                    <option value="40">40</option>
                    <option value="41">41</option>
                    <option value="42">42</option>
                    <option value="43">43</option>
                    <option value="44">44</option>
                    <option value="45">45</option>
                    <option value="46">46</option>
                    <option value="47">47</option>
                    <option value="48">48</option>
                  </select>
                </div>
              </dd>
            </div>
            <div class="searcher">
              <input type="submit" value="検索" id="kensaku" name="kensaku">
            </div>
        </div>
      </form>
      
    </div>                  
      <?php

      // 1.データベースに接続
      $dsn = 'mysql:host=mysql;dbname=JEPX datebase;charset=utf8';
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
          $sql = "SELECT * FROM  spot_market_result
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
            $sql = "SELECT * FROM  spot_market_result 
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
        $trade_totals = array();
        $syprice = array();
        $cnt =0;
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
          </main>
          
          <footer>
            <p>copyright</p>
          </footer>
  </body>
</html>
