<?php 


$server="163.18.42.30";
$dbname="fund";
$user="root";
$passwd="1234";

try{

    $conn = new PDO(
    "sqlsrv:server=$server;Database=$dbname",
    $user,
    $passwd
);
$conn->exec("SET CHARACTER SET utf8");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    echo "Connection failed: ".$e->getMessage();
}







$form['thisTime']='';//最新年度
$form['rank_subjecName']='';//投資金額前5高標的名稱
$form['rank_data']='';//投資金額前5高資料
$form['Chart7Time']='';//長條圖和比較圖7個年度
$form['barChart7totalPrice']='';//長條圖總金額
$form['avgMarketShare']='';//比較圖平均相對市占率
$form['avgGrowthRate']='';//比較圖平均相對成長率

//最新時間
$sql="select top 1 time
from websiteShow
order by time desc";
$stmt=$conn->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($row as $index => $data)
{ 
  foreach($data as $name => $value)
  {
    $form['thisTime']=$value;
  }
}

//投資金額前5高
$sql="select subjectName,price/100000 as price,rn from
(select time,subjectName,price,row_number() over(partition by time order by price desc) 'rn'
from websiteShow
where time= '".$form['thisTime']."')a where a.rn<=5 ";
$stmt=$conn->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
$color=5;
foreach($row as $index => $data)
{ 
  
    $form['rank_subjecName'].="'".$data['subjectName']."',";
    $form['rank_data'].="{y:".$data['price'].",color: Highcharts.getOptions().colors[$color]},";
    $color--;
  
}


//近7月時間
$sql="select time
from websiteShow
where time in
(select distinct top 7 time
from websiteShow
order by time desc
)
group by time
order by time asc";
$stmt=$conn->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($row as $index => $data) 
{
    foreach ($data as $name => $value) {
        $form['Chart7Time'].="'".$value."',";
    }
}

//查詢近7月總金額
$sql="select time,SUM(price)/100000 as price
from websiteShow
where time in
(select distinct top 7 time
from websiteShow
order by time desc
)
group by time
order by time asc";
$stmt=$conn->query($sql);
$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($row as $index => $data) {
    $form['barChart7totalPrice'].=$data['price'].",";
}


$sqlQuadrant[0]='I(明星)';//sql查詢第一象限
$sqlQuadrant[1]='II(問號)';
$sqlQuadrant[2]='III(瘦狗)';
$sqlQuadrant[3]='IV(金牛)';
$form['totalQuadrant_I']='';//比較圖總公司間數第一象限
$form['totalQuadrant_II']='';
$form['totalQuadrant_III']='';
$form['totalQuadrant_IV']='';

$form['7totalQuadrant_I']=0;//比較圖進近年總公司間數第一象限
$form['7totalQuadrant_II']=0;
$form['7totalQuadrant_III']=0;
$form['7totalQuadrant_IV']=0;

//查詢各個象限共有幾間公司
for($j=0;$j<=3;$j++){
  $sql="select time,COUNT(Quadrant)as Quadrant
  from websiteShow
  where time in
  (select distinct top 7 time
  from websiteShow
  order by time desc
  )and Quadrant = '".$sqlQuadrant[$j].
  "' group by time
  order by time asc";
  $stmt=$conn->query($sql);
  $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($row as $index => $data) 
  {
    if($j==0){
    $form['totalQuadrant_I'].=$data['Quadrant'].",";
    $form['7totalQuadrant_I']+=$data['Quadrant'];
    }  
    else if($j==1){
    $form['totalQuadrant_II'].=$data['Quadrant'].",";
    $form['7totalQuadrant_II']+=$data['Quadrant'];

    }  
    else if($j==2){
    $form['totalQuadrant_III'].=$data['Quadrant'].",";
    $form['7totalQuadrant_III']+=$data['Quadrant'];

    }  
    else if($j==3){
    $form['totalQuadrant_IV'].=$data['Quadrant'].",";
    $form['7totalQuadrant_IV']+=$data['Quadrant'];
    }
  }
}
//查詢當年度平均相對市占率及平均相對成長率
for($j=0;$j<=1;$j++){
$sql="select time,ROUND(marketShare*100,3) as marketShare,ROUND(growthRate*100,3) as growthRate 
from BCG_BCG
where time in
(select distinct top 7 time
from BCG_BCG
order by time desc
) 
order by time asc";
$stmt=$conn->query($sql);
$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach ($row as $index => $data) {
    if($j==0){
      $form['avgMarketShare'].=$data['marketShare'].",";
    }
    else if($j==1){
      $form['avgGrowthRate'].=$data['growthRate'].",";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="zh">

<head>
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>透過投信投資組合之動態分析進行投資決策之研究</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap-4.0.0.css" rel="stylesheet">
    <link href="css/css.css" rel="stylesheet" type="text/css">

    <!--highchart圖表-->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>


</head>

<body>
    <div id="bg">
        <h3 id="header">透過投信投資組合之動態分析進行投資決策之研究</h3>
    </div>
    <nav class="dropdown navbar navbar-expand-lg col-xl-12 bg-light">
        <ul class="drop-down-menu navbar-nav mr-auto  rdiv">
            <li class="nav-item active"> <a class="nav-link" href="./index.php">首頁 <span class="sr-only">(current)</span></a> </li>
            <li class="nav-item">
                <a class="nav-link" href="#"></a>
            </li>
            <li class="nav-item active"> <a class="nav-link">查詢</a> 
              <ul>
                <li> <a class=" layer" href="form.php">清單式查詢</a></li>
                <li> <a class=" layer" href="chartForm.php">個股圖表查詢</a></li>
                <li> <a class=" layer" href="compareChart.php">比較圖表查詢</a></li>
              </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"></a>
            </li>
            <li class="nav-item active"> <a class="nav-link">動態圖</a>
                <ul>
                    <li class="nav-item"> <a class="nav-link">全部標的 </a>
                        <ul>
                            <li> <a class=" layer" href="./bubble.html">氣泡圖</a> </li>
                            <li> <a class=" layer" href="./bar.html">階層圖</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#"></a>
                    </li>
                    <li class="nav-item dropdown"> <a class="nav-link">熱門標的</a>
                        <ul>
                            <li> <a class=" layer" href="./hot_line/line.html">折線圖</a> </li>
                            <li> <a class=" layer" href="./hot_rank/rank.html">階層圖</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"></a>
            </li>
            <li class="nav-item active"> <a class="nav-link active" href="./about.html">關於</a> </li>
        </ul>
    </nav>
    &nbsp;

<center>
<h1>最新統計資訊</h1>
</center>
<br><br><br>
    <div id="container" style="min-width: 300px; height: 730px; margin: 0 auto"></div>
    <br>
    <br>
    <div id="container_barAndline" style="min-width: 310px; height: 700px; margin: 0 auto"></div>
    <br>
    <br>
    <div id="container_barAndlineAndPie" style="min-width: 310px; height: 700px; margin: 0 auto"></div>
    <br>
    <br>
</body>
</html>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery-3.2.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap-4.0.0.js"></script>
    <!-- <script type="text/javascript" src="js/test.js"></script>-->
    <script type="text/javascript">
        $(function() {
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) { /* 要滑動到選單的距離 */
                    $('.dropdown').addClass('navFixed'); /* 幫選單加上固定效果 */
                } else {
                    $('.dropdown').removeClass('navFixed'); /* 移除選單固定效果 */
                }
            });
        });
    </script>
       <script type="text/javascript">
            Highcharts.chart('container', {
                chart: {
        zoomType: 'xy'
    },
                title: {
                    text: <?="'".$form['thisTime']." 投資金額前5名'";?>,
                    style : {
                        fontSize : '30px'
                   }
                },
                xAxis: {
                    categories: [<?=$form['rank_subjecName'];?>],
                    labels : {
                      style : {
                          fontSize : '18px'
                      }  
                    }
                }, 

                yAxis: [{ // Primary yAxis
        labels: {
            format: '{value}億元',
            style: {
                color: Highcharts.getOptions().colors[1],
                fontSize : '14px'
            }
        },
        title: {
            text: '金額',
            style: {
                color: Highcharts.getOptions().colors[1],
                fontSize : '14px'
            }
        },
    }],
    legend : {
    enabled:false
},
                    tooltip : {
                      style : {
                        fontSize : '14pt'
                      }
                    },
                
                series: [{
                   type:'bar',
                   name: '金額',
                   data:[<?=$form['rank_data'];?>]
                 }
                   ]
                 });
        </script>
        <!--總金額-->
        <script type="text/javascript">
            Highcharts.chart('container_barAndline', {
                title: {
                    text: '近7月基金投資總額',
                    style : {
                        fontSize : '30px'
                   }
                },
                xAxis: {
                    categories: [<?=$form['Chart7Time'];?>],
                   labels : {
                      style : {
                          fontSize : '18px'
                      }  
                    }
                },
                labels: {
                    items: [{
                        html: '',
                        style: {
                            left: '50px',
                            top: '18px',
                            color: (Highcharts.theme && Highcharts.theme.textColor) || 'black',
                            fontSize : '14px'
                        }
                    }]
                },
                yAxis: [{ // Primary yAxis
                    labels: {
                        format: '{value}億元',
                        style: {
                            color: Highcharts.getOptions().colors[1],
                            fontSize : '14px'
                        }
                    },
                    title: {
                        text: '金額',
                        style: {
                            color: Highcharts.getOptions().colors[1],
                             fontSize : '14px'
                        }
                    }
                }],
  
                legend : {
                  enabled:false
                },
                tooltip : {
                      style : {
                        fontSize : '14pt'
                      }
                    },
                series: [{

                    type: 'column',
                    name: '金額',
                    data: [<?=$form['barChart7totalPrice'];?>
                    ]
                }]
            });
        </script>
        <!--BCG-->
        <script type="text/javascript">
            Highcharts.chart('container_barAndlineAndPie', {
                chart: {
        zoomType: 'xy'
    },
                title: {
                    text: '近7月BCG矩陣相關',
                    style : {
                        fontSize : '30px'
                   }
                },
                xAxis: {
                    categories: [<?=$form['Chart7Time'];?>],
                    labels : {
                      style : {
                          fontSize : '18px'
                      }  
                    }
                }, 

                yAxis: [{ // Primary yAxis
        labels: {
            format: '{value}間',
            style: {
                color: Highcharts.getOptions().colors[1],
                fontSize : '14px'
            }
        },
        title: {
            text: '數量',
            style: {
                color: Highcharts.getOptions().colors[1],
                fontSize : '14px'
            }
        },
        

    }, { // Secondary yAxis
        gridLineWidth: 0,
        title: {
            text: '平均相對市占率',
            style: {
                color: Highcharts.getOptions().colors[4],
                fontSize : '14px'
            }
        },
        labels: {
            format: '{value} %',
            style: {
                color: Highcharts.getOptions().colors[4],
                fontSize : '14px'
            }
        },
opposite: true
    }, { // Tertiary yAxis
        gridLineWidth: 0,
        title: {
            text: '平均相對成長率',
            style: {
                color: Highcharts.getOptions().colors[5],
                fontSize : '14px'
            }
        },
        labels: {
            format: '{value} %',
            style: {
                color: Highcharts.getOptions().colors[5],
                fontSize : '14px'
            }
        },
        opposite: true
    }],
    
    legend : {
    itemStyle : {
        'fontSize' : '20px'
    }
},
                    tooltip : {
                      style : {
                        fontSize : '14pt'
                      }
                    },
                
                series: [{
                    type: 'column',
                    name: 'I(明星)',
                    data: [   
                        <?=$form['totalQuadrant_I'];?>
                    ]
                }, {
                    type: 'column',
                    name: 'II(問號)',
                    data: [
                        <?=$form['totalQuadrant_II'];?>
                    ]
                }, {
                    type: 'column',
                    name: 'III(瘦狗)',
                    data: [
                        <?=$form['totalQuadrant_III'];?>
                    ]
                }, {
                    type: 'column',
                    name: 'IV(金牛)',
                    data: [
                        <?=$form['totalQuadrant_IV'];?>
                    ]
                }, {
                    type: 'spline',
                    name: '市佔率',
                    yAxis: 1,
                    data: [<?=$form['avgMarketShare'];?>],
                    marker: {
                        lineWidth: 2,
                        lineColor: Highcharts.getOptions().colors[4],
                        fillColor: 'white'
                    }
                }, {
                    type: 'spline',
                    name: '成長率',
                    yAxis: 2,
                    data: [<?=$form['avgGrowthRate'];?>],
                    marker: {
                        lineWidth: 2,
                        lineColor: Highcharts.getOptions().colors[5],
                        fillColor: 'white'
                    }
                }, {
                    type: 'pie',
                    name: 'Total consumption',
                    data: [{
                        name: 'I(明星)',
                        y: <?=$form['7totalQuadrant_I'];?>,
                        color: Highcharts.getOptions().colors[0] 
                    }, {
                        name: 'II(問號)',
                        y: <?=$form['7totalQuadrant_II'];?>,
                        color: Highcharts.getOptions().colors[1] 
                    }, {
                        name: 'III(瘦狗)',
                        y: <?=$form['7totalQuadrant_III'];?>,
                        color: Highcharts.getOptions().colors[2] 
                    }, {
                        name: 'IV(金牛)',
                        y: <?=$form['7totalQuadrant_IV'];?>,
                        color: Highcharts.getOptions().colors[3] 
                    }],
                    center: [80, 0],
                    name:'總數量',
                    size: 75,
                    showInLegend: false,
                    tooltip : {
                      style : {
                        fontSize : '14pt'
                      }
                    },
                    dataLabels: {
                        enabled: false
                    }
                }]
            });
        </script>


