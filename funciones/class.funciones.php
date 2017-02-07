<?

if(is_readable('../config/config.php')){
  require_once '../config/config.php';
}

class Funciones{
	private $rh;

	public function __CONSTRUCT()
	{
		$this->rh = new ResponseHelper();
	}

	public function busqueda($cedula){
		$query = Query::prun("SELECT id_donante,don_cedula,don_nombres,don_apellidos,don_sexo,don_nacimiento,don_fecha_nac,don_edad,don_dh,don_dh_pob,don_dh_estado,don_dh_tlf,
																	don_dt,don_dt_pob,don_dt_estado,don_dt_tlf,don_profesion,don_ocupacion FROM donantes WHERE don_cedula LIKE ?",array("s",$cedula));

		if($query->result->num_rows > 0){
			$donante = $query->result->fetch_array(MYSQL_ASSOC);

			$this->rh->setResponse(true,"Este donante ya se encuentra registrado!");
			$this->rh->data = $donante;
		}else{
			$this->rh->setResponse(false,"Nuevo donante. Debe llenar el registro completo.");
			$this->rh->data = $cedula;
		}
		echo json_encode($this->rh);
	}//busqueda

}//Class Funciones

// Logica
$modelFunciones = new Funciones();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'busqueda':
				$cedula = $_POST['cedula'];

				$modelFunciones->busqueda($cedula);
			break;
		endswitch;
	endif;
endif;

?>