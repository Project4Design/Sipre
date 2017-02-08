<?php
class Base
{
	//Mostrar error de forma mas legible
	public static function Debug($data)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
	}

	//Mostrar error en la consola del navegador
	public static function Console_log($data)
	{
	  echo '<script>';
	  echo 'console.log('. json_encode( $data ) .')';
	  echo '</script>';
	}

	//Obtener fecha, por defecto Y-m-d
	public static function Fecha($format="Y-m-d")
	{
		return date($format);
	}

	//Obtener horam por defecto H:i:s

	public static function Hora($format="H:i:s")
	{
		return date($format);
	}

	//Comprobar si la peticion es Ajax
	public static function IsAjax()
	{
		return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

	//error,clase,funcion
	public static function ELog(Exception $e){
		$fo = fopen( ROOT.APP_DIR. 'log/' . date('Ymd').'.log','a');
		fwrite($fo,"\r\n[".date("r")."] " . $e->getTraceAsString() . "\r\n" . $e->getMessage());
		fclose($fo);
	}

	//Transformar un numero a formato monetario 1.000,00
	public static function Format($monto,$num = 0,$dec = ",",$cen = ".")
	{
		return number_format($monto,$num,$dec,$cen);
	}

	//Completar un numero con ceros a la izquierda
	public static function Complete($data)
	{
		return str_pad($data,6,"0",STR_PAD_LEFT);
	}

	//Transformar fecha formato ingles a español
	public static function Convert($data)
	{
		if($data){
			$x = explode("-", $data);
			$data = $x[2]."-".$x[1]."-".$x[0];
		}
		return $data;
	}

	//Transformar timestamp ingles a español
	public static function ConvertTS($data)
	{
		$x = explode(" ", $data);
		$y = explode("-",$x[0]);
		return $y[2]."-".$y[1]."-".$y[0]." ".$x[1];
	}

	//Transformar numero a Dia
	public static function Dia($dia){
		switch ($dia) {
			case 1:
					$x = "Lunes";
				break;
			case 2:
					$x = "Martes";
				break;
			case 3:
					$x = "Miercoles";
				break;
			case 4:
					$x = "Jueves";
				break;
			case 5:
					$x = "Viernes";
				break;
			case 6:
					$x = "Sabado";
				break;
			case 7:
					$x = "Domingo";
				break;
		}

		return $x;
	}

	//Etiqueta <meta>
	public static function Meta($name,$content){
		return  "<meta name=\"".$name."\" content=\"".$content."\">";
	}

	//Etiqueta <link>
	public static function Css($data){
		return "<link rel=\"stylesheet\" type=\"text/css\" href=\"".BASE_URL.$data."\">";
	}

	//Etiqueta <javascript></script>
	public static function Js($data){
  	return "<script type=\"text/javascript\" src=\"".BASE_URL.$data."\"></script>";
	}
}