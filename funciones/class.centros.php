<?

if(is_readable('../config/config.php')){
  require_once '../config/config.php';
}

class Centros{
	private $rh;

	public function __CONSTRUCT()
	{
		$this->rh = new ResponseHelper();
		$this->fecha = Base::Fecha();
		$this->user  = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
	}

	public function consulta()
	{
		$query = Query::run("SELECT * FROM centros");
		$data = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object) $row;
		}

		return $data;
	}

	public function obtener($id)
	{

	$query = Query::prun("SELECT * FROM centros WHERE id_centro = ?",array("i",$id));
    
    if($query->result->num_rows >0){
	    	$data = (object)$query->result->fetch_array(MYSQLI_ASSOC);
		}else{
			$data = NULL;
		}

	    return $data;

	}//obtener

	public function electoresByCentro($id){
		$query = Query::prun("SELECT e.* FROM electores AS e INNER JOIN centros AS c ON c.id_centro = e.id_centro WHERE c.id_centro = ?",array("i",$id));
    
    $data = array();

    while($registro = $query->result->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}




	public function topCentros()
	{
		$query = Query::run("SELECT c.*,COUNT(e.id_elector) AS total FROM centros AS c
																INNER JOIN electores AS e ON e.id_centro = c.id_centro
																GROUP BY c.id_centro
																ORDER BY total DESC");
		$data = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object) $row;
		}

		return $data;


	}

	

	public function add($nombre)
	{
		$query = Query::prun("SELECT cent_nombre FROM centros WHERE cent_nombre = ? LIMIT 1",array("s",$nombre));

		if($query->result->num_rows>0){
    	$this->rh->setResponse(false,"Centro ya registrado.");
			}else{
		  	$query = Query::prun("INSERT INTO centros (cent_nombre,cent_fecha_reg)
											VALUES(?,?)",
											array("ss",$nombre,$this->fecha));
		  	if($query->response){
					$this->rh->setResponse(true,"Registro exitoso! <a href=\"?ver=centros&opc=ver&id={$query->id}\">Ver centro</a>");
		  	}else{
					$this->rh->setResponse(false,"Oops! Ah ocurrido un error!");
		  	}
		  }
		

		echo json_encode($this->rh);
		
	}//add

	public function edit($id,$nombre)
	{
		//$id = $this->user;
		
		$query = Query::prun("SELECT cent_nombre FROM centros WHERE cent_nombre = ? LIMIT 1",array("s",$nombre));

		if($query->result->num_rows>0){
    	$this->rh->setResponse(false,"Centro ya registrado.");
			}else{
		  	$query = Query::prun("UPDATE centros SET
		 	 												
															  cent_nombre = ?
														WHERE id_centro = ? LIMIT 1",
														array("si",$nombre,$id));
		  	if($query->response){
					$this->rh->setResponse(true,"Cambios guardados con exito!",true,"inicio.php?ver=centros");
			  }else{
			    $this->rh->setResponse(false,"Ha ocurrido un error inesperado.");
			  }
			}
		
		echo json_encode($this->rh);

	}//Modificar CENTROS

	public function eliminar($id){

			$query = Query::prun("SELECT * FROM centros WHERE id_centro = ? ",array("i",$id));

			if($query->result->num_rows>0){
				$query = Query::prun("DELETE FROM centros  WHERE id_centro = ?",array("i",$id));

				if($query->response){
					$this->rh->setResponse(false,"Centro eliminado!",true,"inicio.php?ver=centros");		
				}else{
					$this->rh->setResponse(false,"Ah ocurrido un error.");	
				}

			}else{
				$this->rh->setResponse(false,"Centro no encontrado");	
			}
		

		echo json_encode($this->rh);
	}//fin eliminar centros

}//Class Centros

// Logica
$modelCentros = new Centros();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):

		case 'add_centro':
				$nombre = strtoupper($_POST['cent_nombre']);
				$modelCentros->add($nombre);
			break;

			case 'edit':
				$id        = $_POST["id"];
				$nombre = strtoupper($_POST['cent_nombre']);
				$modelCentros->edit($id,$nombre);
			break;

			case 'eliminar':
				$id = $_POST['id'];
				$modelCentros->eliminar($id);
			break;
			default:
			break;
		endswitch;
	endif;
endif;

?>