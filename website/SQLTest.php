<?php

$server = '163.18.42.30';
// PORT

// 資料庫名稱
$dbname = 'fund';
// 帳號
$user = 'root';
// 密碼
$passwd = '1234';
 
$time="1' OR '1'='1";
$subjectName='%台%';

$parameter[':time'] = '2019-02';
$parameter[':subjectName']='%台%';


try{
$conn = new PDO(
    "sqlsrv:server=$server;Database=$dbname",
    $user,
    $passwd
);
$conn->exec("SET CHARACTER SET utf8");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected Successfully";
}
catch(PDOException $e)
{
    echo "Connection failed: ".$e->getMessage();
}


$sql = "select * from fund_gapminder_new WHERE time = :time AND subjectName LIKE :subjectName";
//echo"<br>";
//$stmt = $conn->query($sql);

$stmt=$conn->prepare($sql);
//$stmt->bindParam(":time",$time);

$stmt->execute($parameter);

$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
/*
echo '<pre>';
print_r($row);
echo '</pre>';
*/
foreach($row as $index => $data)
{
		echo $index.':<br>';
	foreach($data as $name => $value){
	    echo $name." : ".$value."<br />";
	}
	

	echo '<br><br>';
}
 

$test='.225';
 if(substr($test,0,0)=='.' ){
$test="0".$test;
     }
$T=substr($test,0,1);
echo $T;


?>