<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Graficos{

	public function __CONSTRUCT()
	{

	}

	public function donaciones(){
		$data       = (object) array();
		$hoy        = Base::Fecha();
		$donaciones = $dias = array();

		for($i = 6; $i >= 0; $i--){
			$dia = strtotime ('-' . $i . ' day', strtotime($hoy));
			$fecha = date('Y-m-d',$dia);

			$d_x_dia = Query::run("SELECT COUNT(id_donacion) AS cuenta FROM donaciones WHERE dn_fecha_reg = '$fecha'");
			$row = $d_x_dia->fetch_array(MYSQLI_ASSOC);
			$donaciones[] = $row["cuenta"];
			if($i>0){
				$dias[] = "'".Base::Dia(date ('N',$dia))."'";
			}
		}//For

		$dias[] = "'".Base::Dia(date('N',strtotime($hoy)))."'";

		$data->dias       = $dias;
		$data->donaciones = $donaciones;

		return $data;
	}

	public function sexo(){
		$data = (object) array();
		$hoy = Base::Fecha();
		$sf = $sm = $dias = array();

		for($i = 6; $i >= 0; $i--){
			$dia = strtotime ('-' . $i . ' day', strtotime($hoy));
			$fecha = date('Y-m-d',$dia);

			$d_x_dia = Query::run("SELECT COUNT(dn.id_donacion) AS cuenta, don.don_sexo FROM donaciones AS dn
																		INNER JOIN donantes AS don ON don.id_donante = dn.id_donante
																		WHERE dn.dn_fecha_reg = '$fecha' GROUP BY don.don_sexo");

			if($d_x_dia->num_rows > 0){

				if($d_x_dia->num_rows > 1){

					while($don = $d_x_dia->fetch_array(MYSQLI_ASSOC)){

						if($don["don_sexo"] == "M"){
							$sm[] = $don["cuenta"];
						}else{
							$sf[] = $don["cuenta"];
						}
					}
				}else{
					$don = (object) $d_x_dia->fetch_array(MYSQLI_ASSOC);

					if($don->don_sexo == "M"){
						$sm[] = $don->cuenta;
						$sf[] = 0;
					}else{
						$sm[] = 0;
						$sf[] = $don->cuenta;
					}
				}
			}else{
				$sf[] = 0;
				$sm[] = 0;
			}

			if($i>0){
				$dias[] = "'".Base::Dia(date ('N',$dia))."'";
			}
		}//For

		$dias[] = "'".Base::Dia(date('N',strtotime($hoy)))."'";

		$data->dias = $dias;
		$data->f = $sf;
		$data->m = $sm;

		return $data;
	}//Sexo

	public function tipo(){
		$data = (object) array();

		$data->plaq = $data->plasma = $data->otro = 0;

		$query = Query::run("SELECT COUNT(id_donacion) AS cuenta,dn_tipo FROM donaciones GROUP BY dn_tipo");

		while ($don = $query->fetch_array(MYSQLI_ASSOC)) {
			if($don["dn_tipo"] == "OTRO"){
				$data->otro = $don["cuenta"];
			}elseif($don["dn_tipo"] == "PLASMA"){
				$data->plasma = $don["cuenta"];
			}else{
				$data->plaq = $don["cuenta"];
			}
		}

		return $data;
	}//Tipo

	public function edades(){
		$grupos = array(0=>"{name: '18-19', y: %cuenta% }",1=>"{name: '20-29', y: %cuenta% }",
										2=>"{name: '30-39', y: %cuenta% }",3=>"{name: '40-49', y: %cuenta% }",
										4=>"{name: '50-59', y: %cuenta% }",5=>"{name: '60-69', y: %cuenta% }",
										6=>"{name: '70-79', y: %cuenta% }",7=>"{name: '80-89', y: %cuenta% }",
										8=>"{name: '90-99', y: %cuenta% }",9=>"{name: '100-109', y: %cuenta% }");
		$cantidad = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0);
		$data   = array();

		$query = Query::run("SELECT COUNT(don.don_edad) AS cuenta,don.don_edad AS edad
																FROM donaciones AS dn
																INNER JOIN donantes AS don ON don.id_donante = dn.id_donante
																GROUP BY don.don_edad");

	
		while ($vars = $query->fetch_array(MYSQLI_ASSOC)){
			$edad   = $vars['edad'];
			$cuenta = $vars['cuenta'];
			switch ($edad) {
				case (($edad>17 && $edad<20) === true):
					$cantidad[0] += $cuenta;
				break;
				case (($edad>19 && $edad<30) === true):
					$cantidad[1] += $cuenta;
				break;
				case (($edad>29 && $edad<40) === true):
					$cantidad[2] += $cuenta;
				break;
				case (($edad>39 && $edad<50) === true):
					$cantidad[3] += $cuenta;
				break;
				case (($edad>49 && $edad<60) === true):
					$cantidad[4] += $cuenta;
				break;
				case (($edad>59 && $edad<70) === true):
					$cantidad[5] += $cuenta;
				break;
				case (($edad>69 && $edad<80) === true):
					$cantidad[6] += $cuenta;
				break;
				case (($edad>79 && $edad<90) === true):
					$cantidad[7] += $cuenta;
				break;
				case (($edad>89 && $edad<100) === true):
					$cantidad[8] += $cuenta;
				break;
				case (($edad>99 && $edad<110) === true):
					$cantidad[9] += $cuenta;
				break;
			}
		}

		foreach ($cantidad as $key => $value) {
			if($value > 0){
				$grupos[$key] = str_replace('%cuenta%', $cantidad[$key], $grupos[$key]);
				$data[] = $grupos[$key];
			}
		}

		return $data;
	}

	public function Peso(){
		$data = (object) array();
		$pf = $pm = array();

		$query = Query::run("SELECT COUNT(dn.id_donacion) AS cuenta,dn.dn_peso AS peso,don.don_sexo AS sexo
																FROM donaciones AS dn
																INNER JOIN donantes AS don ON don.id_donante = dn.id_donante
																GROUP BY dn.dn_peso,don.don_sexo");

		while($don = $query->fetch_array(MYSQLI_ASSOC)){

			if($don["sexo"] == "M"){
				$pm[] = "[".$don["peso"].",".$don["cuenta"]."]";
			}else{
				$pf[] = "[".$don["peso"].",".$don["cuenta"]."]";
			}
		}

		$data->f = $pf;
		$data->m = $pm;

		return $data;
	}
}//Class Graficos

$modelGraficos = new Graficos();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'donaciones':
				$modelGraficos->donaciones();
			break;
				
			case 'sexo':
				$modelGraficos->sexo();
			break;

			case 'tipo':
				$modelGraficos->tipo();
			break;

			case 'edades':
				$modelGraficos->edades();
			break;

			case 'peso':
				$modelGraficos->peso();
			break;
		endswitch;
	endif;
endif;

?>