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

    



$form['timeList']=[];
$form['subjectNameList']=[];
$form['QuadrantList']=[];



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


$sql="select distinct Quadrant from websiteShow order by Quadrant asc";
$stmt=$conn->query($sql);
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
$QuadrantCount=0;
foreach($row as $index => $data)
{ 
  foreach($data as $name => $value)
  {
  	array_push($form['QuadrantList'],$value);
	$QuadrantCount++;
  }
}





$FormPrint='';
if(isset($_POST["submit"])){
    if(in_array($_POST['time'],$form['timeList'])){
            $chart['time']=$_POST['time'];//時間
        }else{
            $_POST['time']='';   
        }
        if(in_array($_POST['subjectName'],$form['subjectNameList'])){
            $chart['subjectName']=$_POST['subjectName'];//標的名稱
        }else{ 
            $_POST['subjectName']='';
        }
        if(in_array($_POST['Quadrant'],$form['QuadrantList'])){
            $chart['Quadrant']=$_POST['Quadrant'];//象限
        }else{
            $_POST['Quadrant']='';
        }
        //驗證使用者輸入的值是否在input內


  $time="%".$_POST['time']."%";
  $subjectName="%".$_POST['subjectName']."%";
  $Quadrant="%".$_POST['Quadrant']."%";

  $price=$_POST['price'];
  $price=(float)$price;

  $marketShare=$_POST['marketShare'];
  $marketShare=(float)$marketShare;

  $growthRate=$_POST['growthRate'];
  $growthRate=(float)$growthRate;

  $price_radio=$_POST['Comparison_price'];
  $marketShare_radio=$_POST['Comparison_marketShare'];
  $growthRate_radio=$_POST['Comparison_growthRate'];

  if($price_radio=='greater'){
    $price_sql=" and price >= $price ";
  }else if($price_radio=='less'){
    $price_sql=" and price < $price ";
}else{
    $price_sql='';
}
     if($marketShare_radio=='greater'){
    $marketShare_sql=" and marketShare >= $marketShare ";
  }else if($marketShare_radio=='less'){
    $marketShare_sql=" and marketShare < $marketShare ";
}else{
    $marketShare_sql='';
}
     if($growthRate_radio=='greater'){
    $growthRate_sql=" and growthRate >= $growthRate ";
  }else if($growthRate_radio=='less'){
    $growthRate_sql=" and growthRate < $growthRate ";
}else{
    $growthRate_sql='';
}

$FormPrint="<table border=1><tr>
<th align='center'>時間
<th align='center'>標的名稱
<th align='center'>金額(千元)
<th align='center'>市佔率(%)
<th align='center'>成長率(%)
<th align='center'>BCG象限
</tr>";   



     $sql="select * from websiteShow where time like :time and subjectName like :subjectName and 
     Quadrant like :Quadrant ".$price_sql.$marketShare_sql.$growthRate_sql." order by time asc";

     $stmt=$conn->prepare($sql);
     $parameter[':time'] = $time;
     $parameter[':subjectName']=$subjectName;
     $parameter[':Quadrant']=$Quadrant;
     $stmt->execute($parameter);
     $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach($row as $index => $data)
{  $FormPrint.="<tr>";
   $i=0;
  foreach($data as $name => $value)
  {
     
    //數值部分轉float
    $output=(float)$value;

      if($i==0){
        $FormPrint.= "<td align=left>".$value."</td>";
      }else if($i==1){
        $FormPrint.= "<td align=left>".$value."</td>";
      }else if($i==2){
        $FormPrint.= "<td align=left>".$output."</td>";
      }else if($i==3){
        $FormPrint.= "<td align=left>".$output."</td>";
      }else if($i==4){
        $FormPrint.= "<td align=left>".$output."</td>";
      }else if($i==5){
        $FormPrint.= "<td align=left>".$value."</td>";
      }
          $i++;
  }
 $FormPrint.= "</tr>";
}
$FormPrint.="</table>";
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

                            <!--時間-->
                            <form action="form.php" method="post">
                                <div>
                                    <tr>
                                        <td align="right">
                                            時間：
                                        </td>
                                        <td align="left">
                                            <input type=text list=categories_time name="time" 
                                            value=<?php echo isset($_POST['time']) ? $_POST['time'] : '' ?>>
                                            <datalist id=categories_time>
                                                <?php for($i=0;$i<$timeCount;$i++){
                                                  echo "<option value=".$form['timeList'][$i].">";}?>
                                            </datalist>
                                        </td>
                                    </tr>
                                </div>
                                <!--標的名稱-->
                                <div>
                                    <tr>
                                        <td align="right">
                                            標的名稱：
                                        </td>
                                        <td align="left">
                                            <input type=text list=categories_subjectName name="subjectName" 
                                            value=<?php echo isset($_POST['subjectName']) ? $_POST['subjectName'] : '' ?>>
                                            <datalist id=categories_subjectName>
                                                <?php for($i=0;$i<$subjectNameCount;$i++){
                                                  echo "<option value=".$form['subjectNameList'][$i].">";}?>
                                            </datalist>
                                        </td>
                                    </tr>
                                </div>
                                <!--BCG象限-->
                                <div>
                                    <tr>
                                        <td align="right">
                                            BCG象限：
                                        </td>
                                        <td align="left">
                                            <input type=text list=categories_Quadrant name="Quadrant" 
                                            value=<?php echo isset($_POST['Quadrant']) ? $_POST['Quadrant'] : '' ?>>
                                            <datalist id=categories_Quadrant>
                                                <?php for($i=0;$i<$QuadrantCount;$i++){
                                                  echo "<option value=".$form['QuadrantList'][$i].">";}?>
                                            </datalist>
                                        </td>
                                    </tr>
                                </div>

                                <!--金額-->
                                <div>
                                    <tr>
                                        <td align="right">
                                            金額(千元)：
                                        </td>
                                        <td align="left">
                                            <input type="number" min="-9999999999.999" max="99999999999.999" step="0.001" id='categories_price' name='price' 
                                            value=<?php echo isset($_POST['price']) ? $_POST['price'] : '' ?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right"></td>

                                        <td align="left">
                                            <input type="radio" name="Comparison_price" value="less" <?php if(isset($_POST[ 'Comparison_price'])&&$_POST[ 'Comparison_price']=="less" )echo "checked";?>> 小於
                                            <input type="radio" name="Comparison_price" value="greater" <?php if(isset($_POST[ 'Comparison_price'])&&$_POST[ 'Comparison_price']=="greater" )echo "checked";?>> 大於
                                            <input type="radio" name="Comparison_price" value="no" <?php if(isset($_POST[ 'Comparison_price'])!=true||$_POST[ 'Comparison_price']=="no" ){echo "checked";}?>> 無 </td>
                                        </input>
                                    </tr>
                                </div>
                                <!--市佔率-->
                                <div>
                                    <tr>
                                        <td align="right">
                                            市佔率(%)：
                                        </td>
                                        <td align="left">
                                            <input type="number" min="0" max="99999999" step="0.01" id='categories_marketShare' name='marketShare' 
                                            value=<?php echo isset($_POST['marketShare']) ? $_POST['marketShare'] : '' ?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right"></td>
                                        <td align="left">
                                            <input type="radio" name="Comparison_marketShare" value="less" <?php if(isset($_POST[ 'Comparison_marketShare'])&&$_POST[ 'Comparison_marketShare']=="less" )echo "checked";?>> 小於
                                            <input type="radio" name="Comparison_marketShare" value="greater" <?php if(isset($_POST[ 'Comparison_marketShare'])&&$_POST[ 'Comparison_marketShare']=="greater" )echo "checked";?>> 大於
                                            <input type="radio" name="Comparison_marketShare" value="no" <?php if(isset($_POST[ 'Comparison_marketShare'])!=true||$_POST[ 'Comparison_marketShare']=="no" ){echo "checked";}?>> 無 </td>
                                        </input>
                                    </tr>
                                </div>
                                <!--成長率-->
                                <div>
                                    <tr>
                                        <td align="right">
                                            成長率(%)：
                                        </td>
                                        <td align="left">
                                            <input type="number" min="-99999999" max="99999999" step="0.01" id='categories_growthRate' name='growthRate' 
                                            value=<?php echo isset($_POST['growthRate']) ? $_POST['growthRate'] : '' ?>>
                                        </td>
                                        <!--按鈕-->
                                        <div>
                                            <td align="center">
                                                <input type="submit" value="查詢" name="submit" />
                                            </td>
                                        </div>
                                    </tr>
                                    <tr>
                                        <td align="right"></td>
                                        <td align="left">
                                            <input type="radio" name="Comparison_growthRate" value="less" <?php if(isset($_POST[ 'Comparison_growthRate'])&&$_POST[ 'Comparison_growthRate']=="less" )echo "checked";?>> 小於
                                            <input type="radio" name="Comparison_growthRate" value="greater" <?php if(isset($_POST[ 'Comparison_growthRate'])&&$_POST[ 'Comparison_growthRate']=="greater" )echo "checked";?>> 大於
                                            <input type="radio" name="Comparison_growthRate" value="no" <?php if(isset($_POST[ 'Comparison_growthRate'])!=true||$_POST[ 'Comparison_growthRate']=="no" ){echo "checked";}?>> 無 </td>
                                        </input>
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
<?=$FormPrint?>
        <br>
    </center>
</body>
</html>

