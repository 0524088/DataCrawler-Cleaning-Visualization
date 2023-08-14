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

$form['timeList']=[];//時間輸入框
$form['timeListID']='';//時間序位
$form['subjectNameList']=[];//標的名稱輸入框
//圖表框架
if(!isset($chart['time_start'])){$chart['time_start']='';//開始時間
}if(!isset($chart['time_end'])){$chart['time_end']='';//結束時間
}if(!isset($chart['timeCount'])){$chart['timeCount']='';//圖表時間區間
}if(!isset($chart['type_en'])){$chart['type_en']='';//圖表類型(英)
}if(!isset($chart['type_zh'])){$chart['type_zh']='';//圖表類型(中)
}if(!isset($chart['y_en'])){$chart['y_en']='';//比較類別(英)
}if(!isset($chart['y_zh'])){$chart['y_zh']='';//比較類別(中)
}if(!isset($chart['labels_format'])){$chart['labels_format']="'{value}'";//軸單位及名稱
}if(!isset($chart['subjectName'])){$chart['subjectName']='';//標的名稱
}
//圖表資料
if(!isset($data['time'])){$data['time']='';//時間資料
}if(!isset($data['subjectName'])){$data['subjectName']='';//標的名稱資料(string)
}if(!isset($data['y'])){$data['y']='';//對應至subjectName的資料(data)
}if(!isset($data['series'])){$data['series']='';//資料群組(group)
}

$sql="select distinct time from websiteShow order by time asc";
$stmt=$conn->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
$timeCount=0;
foreach($row as $index => $data)
{ 
  foreach($data as $name => $value)
  {
    array_push($form['timeList'],$value);
    $timeCount++;
  }
}

$sql="select distinct subjectName from websiteShow order by subjectName asc";
$stmt=$conn->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
$subjectNameCount=0;
foreach($row as $index => $data)
{ 
  foreach($data as $name => $value)
  {
    array_push($form['subjectNameList'],$value);
  $subjectNameCount++;
  }
}



$ChartPrint='<br>';
if(isset($_POST["submit"])){
        if(in_array($_POST['time_start'],$form['timeList'])){
            $chart['time_start']=$_POST['time_start'];//時間初值
        }else{
            $_POST['time_start']='';   
        }
        if(in_array($_POST['time_end'],$form['timeList'])){
            $chart['time_end']=$_POST['time_end'];//時間終值
        }else{ 
            $_POST['time_end']='';
        }
        $subjectName[]='';
        for($i=0;$i<=4;$i++){
          if(in_array($_POST['subjectName'.$i],$form['subjectNameList'])){
              array_push($subjectName,$_POST['subjectName'.$i]);//標的名稱i
          }else{
              $_POST['subjectName'.$i]='';
          }
        }
        array_shift($subjectName);
        //驗證使用者輸入的值是否在input內

    $chart['time_start_ID']=array_search($chart['time_start'], $form['timeList']);//初值ID
    $chart['time_end_ID']= array_search($chart['time_end'], $form['timeList']);//終值ID
 
    if($chart['time_start_ID']>$chart['time_end_ID']){
        $temp=$chart['time_end'];
        $chart['time_end']=$chart['time_start'];
        $chart['time_start']=$temp;
        $chart['time_start_ID']=array_search($chart['time_start'], $form['timeList']);
        $chart['time_end_ID']= array_search($chart['time_end'], $form['timeList']);
    }//終>初則互換


 $chart['timeCount']=($chart['time_end_ID']-$chart['time_start_ID'])+1;//時間區間
 $chart['type_en']=$_POST['chartType'];//圖表類型(en)
      switch ($_POST['chartType']) {
         case 'column':
              $chart['type_zh']='長條圖';//圖表類型(zh)
             break;
         case 'spline':
              $chart['type_zh']='折線圖';
             break;
         case 'bar':
              $chart['type_zh']='橫條圖';
             break;
         default:
             # code...
             break;
     }
     $chart['y_en']=$_POST['y'];//y軸名抽(en)
      switch ($_POST['y']) {
         case 'price':
              $chart['y_zh']='總金額';//y軸名稱(zh)
              $chart['labels_format']="千元";//單位
              $chart['title_text']="'金額'";//
             break;
         case 'marketShare':
              $chart['y_zh']='市占率';
              $chart['labels_format']=" %";
              $chart['title_text']="'市占率'";
             break;
         case 'growthRate':
              $chart['y_zh']='成長率';
              $chart['labels_format']=" %";
              $chart['title_text']="'成長率'";
             break;
         default:
             # code...
             break;
     }
              $chart['labels_format']="'{value}".$chart['labels_format']."'";

    




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

    for($i=0;$i<$chart['timeCount'];$i++){
        for($j=0;$j<count($subjectName);$j++){
           $chart['time'][$i]=$form['timeList'][$chart['time_start_ID']+$i];//透過初值ID搜尋所有時間區間內的日期 
           $sql="select time,subjectName,price,marketShare,growthRate from websiteShow where time = :time and 
           subjectName = :subjectName  
           order by time desc";
        
           $stmt=$conn->prepare($sql);    
           $parameter[':time']=$chart['time'][$i];
           $parameter[':subjectName']=$subjectName[$j];
           $stmt->execute($parameter);
           $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
           foreach($rows as $row ){
              if(!isset( $data['y'][$j])){ $data['y'][$j]='';}
              $data['y'][$j].=$row[$chart['y_en']].",";
              $data['time'][$i]=$row['time'];
           }  
        }
    }//i年度月份的第j支股票


    for($k=0;$k<$j;$k++){
      @$data['series'].="{type: '".$chart['type_en']."',
                        name: '".$subjectName[$k]."',
                        data: [".$data['y'][$k]."]},";         
}


 
        $ChartPrint= '<div id="container" style="min-width: 310px; height: 800px; margin: 0 auto"></div>';
}
 if(@$data['time']==null){
      @$data['time'][0]="該期間無資料";
      @$data['y'].=0;
  }



?>

<!--查詢資料庫-->
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>透過投信投資組合之動態分析進行投資決策之研究</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap-4.0.0.css" rel="stylesheet">
    <link href="css/css.css" rel="stylesheet" type="text/css">

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

    <center>
        <table>
            <tr>
                <td style="height: 1px"></td>
            </tr>
            <table cellpadding="5" cellspacing="0" border="0" width="100%">
                <tr>
                    <td style="height: 5px" colspan="2" width="100%" align="center">
                        <table cellpadding="2" cellspacing="5" border=0 width="60%" style="background-color:#E8E8E8">


                            <form action="compareChart.php" method="post">
                                <div>
                                    <tr>
                                        <td align="right">
                                            時間：
                                        </td>
                                        <td align="left">
                                            <input type=text list=categories_time name="time_start" 
                                            value=<?php echo isset($_POST['time_start']) ? $_POST['time_start'] : '' ?>>
                                            <datalist id=categories_time>
                                                <?php for($i=0;$i<$timeCount;$i++){
                                                  echo "<option value=".$form['timeList'][$i].">";}?>
                                            </datalist>
                                            至
                                             <input type=text list=categories_time name="time_end" 
                                            value=<?php echo isset($_POST['time_end']) ? $_POST['time_end'] : '' ?>>
                                            <datalist id=categories_time>
                                                <?php for($i=0;$i<$timeCount;$i++){
                                                  echo "<option value=".$form['timeList'][$i].">";}?>
                                            </datalist>
                                        </td>
                                    </tr>
                                </div>
                                <!-- 標的名稱-->
                                
                                 <div id="subjectName0" style="visibility: hidden;">
                                    <tr>
                                        <td align="right">
                                            標的名稱：
                                        </td>
                                        <td align="left">
                                            <input type=text list=categories_subjectName name="subjectName0" 
                                            value="<?php echo isset($_POST['subjectName0']) ? $_POST['subjectName0'] : '' ?>">
                                            <datalist id=categories_subjectName>
                                                <?php for($i=0;$i<$subjectNameCount;$i++){
                                                  echo "<option value=".$form['subjectNameList'][$i].">";}?>
                                            </datalist>
                                        </td>
                                    </tr>
                                </div>
                                <div id="subjectName1" style="visibility: hidden;">
                                    <tr>
                                        <td align="right">
                                            標的名稱：
                                        </td>
                                        <td align="left">
                                            <input type=text list=categories_subjectName name="subjectName1" 
                                            value="<?php echo isset($_POST['subjectName1']) ? $_POST['subjectName1'] : '' ?>">
                                            <datalist id=categories_subjectName>
                                                <?php for($i=0;$i<$subjectNameCount;$i++){
                                                  echo "<option value=".$form['subjectNameList'][$i].">";}?>
                                            </datalist>
                                        </td>
                                    </tr>
                                </div>
                              
                                <div id="subjectName2" style="visibility: hidden;">
                                    <tr>
                                        <td align="right">
                                            標的名稱：
                                        </td>
                                        <td align="left">
                                            <input type=text list=categories_subjectName name="subjectName2" 
                                            value="<?php echo isset($_POST['subjectName2']) ? $_POST['subjectName2'] : '' ?>">
                                            <datalist id=categories_subjectName>
                                                <?php for($i=0;$i<$subjectNameCount;$i++){
                                                  echo "<option value=".$form['subjectNameList'][$i].">";}?>
                                            </datalist>
                                        </td>
                                    </tr>
                                </div>
                                
                                 <div id="subjectName3" style="visibility: hidden;">
                                    <tr>
                                        <td align="right">
                                            標的名稱：
                                        </td>
                                        <td align="left">
                                            <input type=text list=categories_subjectName name="subjectName3" 
                                            value="<?php echo isset($_POST['subjectName3']) ? $_POST['subjectName3'] : '' ?>">
                                            <datalist id=categories_subjectName>
                                                <?php for($i=0;$i<$subjectNameCount;$i++){
                                                  echo "<option value=".$form['subjectNameList'][$i].">";}?>
                                            </datalist>
                                        </td>
                                    </tr>
                                </div>
                                 
                                <div id="subjectName4" style="visibility: hidden;">
                                    <tr>
                                        <td align="right">
                                            標的名稱：
                                        </td>
                                        <td align="left">
                                            <input type=text list=categories_subjectName name="subjectName4" 
                                            value="<?php echo isset($_POST['subjectName4']) ? $_POST['subjectName4'] : '' ?>">
                                            <datalist id=categories_subjectName>
                                                <?php for($i=0;$i<$subjectNameCount;$i++){
                                                  echo "<option value=".$form['subjectNameList'][$i].">";}?>
                                            </datalist>
                                        </td>
                                    </tr>
                                </div>
                                <!--比較類別-->
                                 <div>
                                    <tr>
                                        <td align="right">
                                            查詢類別：
                                        </td>
                                        <td align="left">
                                            <select name="y" value="<?php echo isset($_POST['y']) ? $_POST['y'] : '' ?>">
                                              <option value="price" <?php if(isset($_POST['y'])&&$_POST['y']=='price'){echo "selected='selected'";}?>>金額</option>
                                              <option value="marketShare" <?php if(isset($_POST['y'])&&$_POST['y']=='marketShare'){echo "selected='selected'";}?>>市占率</option>
                                              <option value="growthRate" <?php if(isset($_POST['y'])&&$_POST['y']=='growthRate'){echo "selected='selected'";}?>>成長率</option>
                                            </select>
                                        </td>
                                    </tr>
                                </div>

                              
                                <div>
                                    <tr>
                                        <td align="right">
                                            圖表樣式：
                                        </td>
                                        <td align="left">
                                            <input type="radio" name="chartType" value="column" 
                                            <?php if(isset($_POST['chartType'])!=true||
                                              $_POST['chartType']=="column")echo "checked";?>> 長條圖
                                            <input type="radio" name="chartType" value="spline" 
                                            <?php if(isset($_POST['chartType'])&&$_POST['chartType']=="spline")
                                             echo "checked";?>> 折線圖
                                            <input type="radio" name="chartType" value="bar" 
                                            <?php if(isset($_POST['chartType'])&&$_POST['chartType']=="bar")
                                             echo "checked";?>> 橫條圖 </td>
                                        </input>
                                        </td>
                                        <td align="left">
                                        <!--按鈕-->

                                        <div>
                                            <td align="center">
                                                <input type="submit" value="查詢" name="submit" />
                                            </td>
                                        </div>
                                    </tr>
                                    <tr>
                                        <td align="right"></td>
                                        <td align="left"></td>
                                    </tr>
                                </div>
                            </form>
                    </td>
                </tr>
                </table>
                </td>
                </tr>
            </table>
            <table width="60%">
                <tr>
            <td align="left">
            * 數值小於0.01%(0.0001)亦顯示0<br>
            * 2015-06為基期，成長率皆為0；前一期無資料者，成長率亦為0<br>
          </td>
        </tr>
        </table>
            <br>


<?=$ChartPrint?><br><br><br><br><br><br>




    </center>
</body>
</html>

<script type="text/javascript">
            Highcharts.chart('container', {
                chart: {
        zoomType: 'xy'
    },
                title: {
                    text: <?="'近".$chart['timeCount']."月".$chart['y_zh'].$chart['type_zh']."'";?>,
                    style : {
                        fontSize : '30px'
                   }
                },
                xAxis: {
                    categories: [<?php for($i=0;$i<count($data['time']);$i++){echo "'".$data['time'][$i]."',";}?>],
                    labels : {
                      style : {
                          fontSize : '18px'
                      }  
                    }
                }, 

                yAxis: [{ // Primary yAxis
        labels: {
            format: <?=$chart['labels_format'];?>,
            style: {
                color: Highcharts.getOptions().colors[1],
                fontSize : '14px'
            }
        },
        title: {
            text: <?="'".$chart['y_zh']."'";?>,
            style: {
                color: Highcharts.getOptions().colors[1],
                fontSize : '14px'
            }
        },
        

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
                
                series: [<?php if(empty($data['series'])){
                  echo"{type:'".$chart['type_en']."',
                        name:'".$chart['subjectName']."',
                        data:[".$data['y']."]}";
                }else{echo $data['series'];}?>]
            });
        </script>
