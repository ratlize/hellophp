
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

    <script type="text/javascript">
      $(function () {
        $('form').submit(function () {
          $(this).find('#double').prop('disabled', true);
          setTimeout(function(){$('#double').prop('disabled', false)},10000);
        });
      });
    </script>
</head>
<body>
    <header >
      <div class='title'>
        <h1><a href='./search.php' id='link'> スポット市場・時間前市場取引結果</a> </h1>
        <img src="./img/logo.png">
      </div>
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
    <!-- <?php 
          $X = "0.1";
          $Y = "0";
          for($i=1;$i<=1000;$i++){
            $Y = bcadd($Y,$X,1);
          }
          print($Y);
    ?> -->
    <main class="container">
      <form action="index_copy.php" method="post"  class='form1' target="_blank">
        <div class="date_selecter">
        <h2>  取得したいデータを選択してください</h2>
          <div class="cp_ipselect cp_sl01">
            <select required name="data_value">
              <option value="" hidden>Choose</option>
              <option value='spot'>スポット市場結果</option>
              <option value='pre_time'>時間前市場結果</option>
            </select>
          </div>
          <div class="cp_ipselect cp_sl01">
            <select required name="data_dates">
              <option value="" hidden>Choose</option>
              <?php for($i=2005; $i<=$theyear; ++$i):?>
                <option value="<?php echo $i ?>"><?php echo $i."年度" ?></option>
                <?php endfor?>
            </select>
          </div>
        </div>
        <div class="subbtn">
            <button type="submit" id="double"> 
                CSVを取得する
            </button>
        </div>
      </form>

        <form action='makingcsv.php' method="get" class="form2">
            <div>
                <h2>作成するCSVの年度と</h2>
                <h2>時刻コードを選択してください</h2>
                <div class="cp_ipselect cp_sl01" id="data_value">
                  <select required name="data_value">
                    <option value="" hidden>Choose</option>
                    <option value='spot'>スポット市場結果</option>
                    <option value='pre-time'>時間前市場結果</option>
                  </select>
                </div>
              <div class='datebox'>
                <dd id="fx1">
                  <div class="selectBox">
                    <select id="year" name="trade_date_1">
                      <?php for($i=2005; $i<=$theyear; ++$i):?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                      <?php endfor?>
                    </select>
                  </div>
                  <span>年</span>
                  <div class="selectBox">
                    <select id="month" name='trade_date_2'>
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
                    <select id="day" name='trade_date_3'>
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
                <p　id='ccc'>から</p>
                <dd id="fx1">
                  <div class="selectBox">
                    <select id="year" name="trade_date_4">
                      <?php for($i=2005; $i<=$theyear; ++$i):?>
                        <option value="<?php echo $i ?>"><?php echo $i ?></option>
                      <?php endfor?>
                    </select>
                  </div>
                  <span>年</span>
                  <div class="selectBox">
                    <select id="month" name='trade_date_5'>
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
                    <select id="day" name='trade_date_6'>
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
                <br>
              <dd id="timecodebox">
                <div class="selectBox">
                  <select id="hour" name='time_code_from' >
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
                  <select id="hour" name='time_code_to' >
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
            <div class="subbtn">
                <button type="submit" id="double"> 
                    CSVを作成する
                </button>
            </div>
        </form>

    </main>
    </main>
    <footer>
      <p>copyright</p>
    </footer>

    </body>
<html>