<?
require_once 'config/config.php';
$required = [0,1,2,3,4,5,7,8,9,11,12];
Base::Debug($required);
$data = ["a1","","c3","d4","e5","f6","g7","","i9","j10","k11","l12","m13",""];
$x=0;
foreach ($data as $key => $value) {
	if(in_array($x,$required)&&($value==NULL||$value=="")){
		echo $key."=>Mal : ".$value."<br>";
	}else{
		echo $key."=>Bien : ".$value."<br>";
	}
$x++;	
}
?>

<!DOCTYPE html>
<html>
<head>
<?=Base::Css("includes/css/bootstrap.css")?>
</head>
<body>

</body>
</html>