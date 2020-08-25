<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Mincho&display=swap" rel="stylesheet">
    <script type="text/javascript" src="jquery-3.5.1.js"></script>
    <script type="text/javascript">
        var values = [];
        var param = location.search.substring(1);
        var element = param.split('=')
        values.push(element);
        console.log(values);
        $(function(){
            var selecteddate = values[0][1];
            var now_select = $("calender").find(value=selecteddate);
            $(now_select).prop('selected',true);
        })
    </script>
    <?php
      date_default_timezone_set('Asia/Tokyo');
      $today = new DateTime('now');
      $theyear = $today->format('Y');
    ?>
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
          <a href="./search.php" >スポット市場結果へ</a>
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
    <form method='get' action='chart.php' id='chartform'>
      <input type="date" name="selecteddate1" id="calendar" required value=<?php if(isset($_GET['selecteddate1'])){echo $_GET['selecteddate1'];}else{echo " ";}?> >
      <input type="date" name="selecteddate2" id="calendar" value=<?php if(isset($_GET['selecteddate2'])){echo $_GET['selecteddate2'];}else{echo " ";}?> >
      <input type='submit' value='検索'>
      
    </form>
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

    $syprice1 = array();
    $syprice2 = array();
    $tradebuy1 = array();
    $tradebuy2 = array();
    $tradesale1 = array();
    $tradesale2 = array();
    $trade_totals1 = array();
    $trade_totals2 = array();
    $selecteddate1 = $_GET['selecteddate1'];
    $selecteddate2 = $_GET['selecteddate2'];
    $openprice1 = array();
    $highprice1 = array();
    $openprice2 = array();
    $highprice2 = array();


    try {
        $sql = "SELECT*FROM  spot_market_result
                WHERE trade_date = :trade_date";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':trade_date', $selecteddate1);
        $stmt->execute();
    }catch(PDOException $Exception){
        print "エラー :".$Exception->getMessage();
    }

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $syprice1[] = $row['system_price'];
        $tradebuy1[] = $row['trade_buy'];
        $tradesale1[] = $row['trade_sale'];
        if($_GET['selecteddate1']===$row['trade_date']){
            $trade_totals1[] = $row['trade_total']*2;
          }
    }

    if($selecteddate2 != null){

        try {
            $sql = "SELECT*FROM  spot_market_result
                    WHERE trade_date = :trade_date";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':trade_date', $selecteddate2);
            $stmt->execute();
        }catch(PDOException $Exception){
            print "エラー :".$Exception->getMessage();
        }

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $syprice2[] = $row['system_price'];
            $tradebuy2[] = $row['trade_buy'];
            $tradesale2[] = $row['trade_sale'];
            if($_GET['selecteddate2']===$row['trade_date']){
                $trade_totals2[] = $row['trade_total']*2;
            }
        }
    }

    try {
      $sql = "SELECT*FROM  pretime_market_result
              WHERE trade_date = :trade_date";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':trade_date', $selecteddate1);
      $stmt->execute();
    }catch(PDOException $Exception){
        print "エラー :".$Exception->getMessage();
    }

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $openprice1[] = $row['open_price'];
      $closingprice1[] = $row['closing_price'];
  }

  if($selecteddate2 != null){
    try {
      $sql = "SELECT*FROM  pretime_market_result
              WHERE trade_date = :trade_date";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':trade_date', $selecteddate2);
      $stmt->execute();
    }catch(PDOException $Exception){
        print "エラー :".$Exception->getMessage();
    }

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $openprice2[] = $row['open_price'];
      $closingprice2[] = $row['closing_price'];
    }
  }


    $php_json1 = json_encode($tradebuy1);
    $php_json2 = json_encode($tradebuy2);
    $php_json3 = json_encode($tradesale1);
    $php_json4 = json_encode($tradesale2);
    $php_json5 = json_encode($_GET['selecteddate1']);
    $php_json6 = json_encode($trade_totals1);
    $php_json7 = json_encode($syprice1);
    $php_json8 = json_encode($_GET['selecteddate2']);
    $php_json9 = json_encode($trade_totals2);
    $php_json10 = json_encode($syprice2);
    $php_json11 = json_encode($openprice1);
    $php_json12 = json_encode($closingprice1);
    $php_json13 = json_encode($openprice2);
    $php_json14 = json_encode($closingprice2);
    ?>
    <div class="charts">
      <canvas id="myChart"></canvas>
      <canvas id="myLineChart"></canvas>
      <canvas id="chart3"></canvas>
      <canvas id="chart4"></canvas>
    </div>
    <footer>
      <p>copyright</p>
    </footer>
  </body>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
  <script src ="./moment.js"></script>
  <script src ="chartjs-plugin-streaming.js"></script>
  <script>
    var cnt = <?php echo json_encode($count);?> ;
    var trade_totals1 = <?php echo ($php_json6);?>;
    var selecteddate1 = <?php echo ($php_json5);?>;
    var syprice1 = <?php echo($php_json7);?>;
    var trade_totals2 = <?php echo ($php_json9);?>;
    var selecteddate2 = <?php echo ($php_json8);?>;
    var syprice2 = <?php echo($php_json10);?>;
    var tradebuy1 = <?php echo($php_json1);?>;
    var tradebuy2 = <?php echo($php_json2);?>;
    var tradesale1 = <?php echo($php_json3);?>;
    var tradesale2 = <?php echo($php_json4);?>;
    var openprice1 = <?php echo($php_json11);?>;
    var closingprice1 = <?php echo($php_json12);?>;
    var openprice2 = <?php echo($php_json13);?>;
    var closingprice2 = <?php echo($php_json14);?>;
    var hour = ["0:00","0:30","1:00","1:30","2:00","2:30","3:00","3:30","4:00","4:30","5:00","5:30","6:00",
                "6:30","7:00","7:30","8:00","8:30","9:00","9:30","10:00","10:30","11:00","11:30","12:00",
                "12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00",
                "18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"];
    // console.log($("#calendar"));
    // $(function(){
    //     var now_select =$("#calendar").find("value=" +selecteddate);
    //     $(now_select).prop('selected',true);
    //   })
    var ctx = document.getElementById("myLineChart");
    var myLineChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: hour,
      datasets: [
        {
          label: '約定総量'+" ("+ selecteddate1 + ")",
          data: trade_totals1,
          // borderColor: "rgba(255,0,0,1)",
          backgroundColor: "#66CCFF"
        },
        {
          label: '約定総量'+" ("+ selecteddate2 + ")",
          data: trade_totals2,
          // borderColor: "rgba(255,0,0,1)",
          backgroundColor: "FF5192"
        }
      ],
    },
    options: {
      title: {
        display: true,
        text: '30分ごとの約定総量'
      },
      scales: {
        yAxes: [{
          ticks: {
            suggestedMax: 4000000,
            suggestedMin: 0,
            stepSize: 5000000,
            callback: function(value, index, values){
              return  value +  'kWh';
            }
          }
        }]
      },
    }
   });

   var ctx = document.getElementById('myChart').getContext('2d');
   var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels:["0:00","0:30","1:00","1:30","2:00","2:30","3:00","3:30","4:00","4:30","5:00","5:30","6:00",
                "6:30","7:00","7:30","8:00","8:30","9:00","9:30","10:00","10:30","11:00","11:30","12:00",
                "12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00",
                "18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30"] ,
        datasets: [
            {
                label: 'システムプライス'+" ("+ selecteddate1 + ")",
                backgroundColor: "rgba(0,0,0,0)",
                borderColor: '#FF82B2',
                data: syprice1,
                lineTension: 0
            },
            {
                label: 'システムプライス'+" ("+ selecteddate2 + ")",
                backgroundColor: "rgba(0,0,0,0)",
                borderColor: 'rgba(0,0,55,1)',
                data: syprice2,
                lineTension: 0,
                hidden: false
            }
        ]
    },

    // Configuration options go here
    options: {
      responsive: true,
      scales: {
        yAxes: [{
          display: true,
          scaleLabel: {
            display: true,
            labelString: '(円/kWh)'
          },
          ticks: {
            suggestedMax: 10,
              suggestedMin: 0,
              stepSize: 2,
          }
        }]
      }
    }
});

    var ctx = document.getElementById('chart3').getContext('2d');
    var chart = new Chart(ctx, {
        type:'line',
        data:{
            labels: hour,
            datasets:[
                {
                  label: '買い入札量'+"("+ selecteddate1 + ")",
                  data: tradebuy1,
                  borderColor: '#FF6666',
                  backgroundColor: "rgba(0,0,0,0)",
                  lineTension: 0
                },
                {
                  label: '売り入札量'+"("+ selecteddate1 + ")",
                  data: tradesale1,
                  borderColor: '#3333FF',
                  backgroundColor: "rgba(0,0,0,0)",
                  lineTension: 0

                },
                {
                  label: '買い入札量'+"("+ selecteddate2 + ")",
                  data: tradebuy2,
                  borderColor: '#FFFF00',
                  backgroundColor: "rgba(0,0,0,0)",
                  lineTension: 0
                },
                {
                  label: '売り入札量'+"("+ selecteddate2 + ")",
                  data: tradesale2,
                  borderColor: '#00AA00',
                  backgroundColor: "rgba(0,0,0,0)",
                  lineTension: 0

                }
            ],
          },
            options:{
                title:{
                    display: true,
                    text: '買い入札量・売り入札量'
                },
                scales: {
                  yAxes:[{
                    ticks: {

                    }
                  }]
                }
            }

    });
    var ctx = document.getElementById('chart4').getContext('2d');
    var chart = new Chart(ctx, {
        type:'line',
        data:{
            labels: hour,
            datasets:[
                {
                  label: '始値'+"("+ selecteddate1 + ")",
                  data: openprice1,
                  borderColor: '#FF6666',
                  backgroundColor: "rgba(0,0,0,0)",
                  lineTension: 0
                },
                {
                  label: '終値'+"("+ selecteddate1 + ")",
                  data:　closingprice1,
                  borderColor: '#3333FF',
                  backgroundColor: "rgba(0,0,0,0)",
                  lineTension: 0

                },
                {
                  label: '始値'+"("+ selecteddate2 + ")",
                  data: openprice2,
                  borderColor: '#FFFF00',
                  backgroundColor: "rgba(0,0,0,0)",
                  lineTension: 0
                },
                {
                  label: '終値'+"("+ selecteddate2 + ")",
                  data: closingprice2,
                  borderColor: '#00AA00',
                  backgroundColor: "rgba(0,0,0,0)",
                  lineTension: 0

                }
            ],
          },
            options:{
                title:{
                    display: true,
                    text: '時間前市場取引結果'
                },
                scales: {
                  yAxes:[{
                    ticks: {

                    }
                  }]
                }
            }

    });
  </script>
</html>