<?
if(is_readable('../config/config.php')){
  require_once '../config/config.php';
}

class Sectores{
	private $rh;
	private $nivel;

	public function __CONSTRUCT()
	{
		$this->rh = new ResponseHelper();
		$this->nivel = isset($_SESSION['nivel'])? $_SESSION['nivel'] : "x";
	}

	//Consultar todos los sectores
	public function sector_consulta()
	{
		$query = Query::run("SELECT * FROM sectores");
		$data  = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object) $row;
		}

		return $data;
	}

	//Consultar todos los SH [Sectores hijos] + Total de electores por SH
	public function sh_consulta()
	{
		$query = Query::run("SELECT sh.id_sh,sh.sh_nombre,COUNT(e.id_elector) AS total,s.sect_nombre FROM sectores_hijos AS sh
																INNER JOIN sectores AS s ON s.id_sector = sh.id_sector
																LEFT JOIN electores AS e ON e.id_sh = sh.id_sh
																GROUP BY sh.id_sh
																ORDER BY total DESC");
		$data = (object) array();
		$data->total = 0;
		$data2 = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data->total += $row["total"];
			$data2[] = (object) $row;
		}

		$data->data = $data2;

		return $data;
	}

	//Obtener un sector especifico
	public function sector_obtener($sector)
	{
		$query = Query::prun("SELECT * FROM sectores WHERE id_sector = ? LIMIT 1",array("i",$sector));
		$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);

		return $data;
	}

	//Obtener un sh especifico
	public function sh_obtener($sh)
	{
		$query = Query::prun("SELECT * FROM sectores_hijos WHERE id_sh = ?",array("i",$sh));
		$data = (object) $query->result->fetch_array(MYSQLI_ASSOC);

		return $data;
	}

	//Si se esta editando un elector, se busca que SH tiene registrado para cargarlo automaticamente en el Select
	public function shByElector($sector,$elector)
	{
		$query = Query::prun("SELECT * FROM sectores_hijos WHERE id_sector = ?",array("i",$sector));
		$data = "";

		if($query->result->num_rows>0){

			$query2 = Query::prun("SELECT id_sh FROM electores WHERE id_elector = ? LIMIT 1",array("i",$elector));

			if($query2->result->num_rows>0){
				$x = (object) $query2->result->fetch_array(MYSQLI_ASSOC);
				$elector_sh = $x->id_sh;
			}else{
				$elector_sh = 0;
			}

			$data .= "<option value=\"\">Seleccione...</option>";
			while ($row = $query->result->fetch_array(MYSQLI_ASSOC)){
				$selected = ($elector_sh == $row['id_sh']) ? "selected":"";
				$data .= "<option value=\"{$row['id_sh']}\" {$selected}>{$row['sh_nombre']}</option>";
			}

			$this->rh->setResponse(true);
			$this->rh->data = $data;
		}else{
			$this->rh->setResponse(false,"No se encontraon resultados");
		}

		echo json_encode($this->rh);
	}

	//Consulta de todos los sectores + Total de electores por Sector
	public function sector_electores()
	{
		$query = Query::run("SELECT s.id_sector,s.sect_nombre,COUNT(e.id_elector) AS total FROM sectores AS s
															LEFT JOIN electores AS e ON e.id_sector = s.id_sector
															GROUP BY s.id_sector
															ORDER BY total DESC");
		$data = (object) array();
		$data->total = 0;
		$data2 = array();

		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data->total += $row["total"];
			$data2[] = (object) $row;
		}

		$data->data = $data2;

		return $data;
	}

	//Consulta de SH + total de electores por Sector. SOLO se traen los sectores que tengan electores registrados
	public function topSh(){
		$query = Query::run("SELECT sh.*,COUNT(e.id_elector) AS total FROM sectores_hijos AS sh
															INNER JOIN electores AS e ON e.id_sh = sh.id_sh
															GROUP BY sh.id_sh
															ORDER BY total DESC");
		$data = array();
		
		while ($row = $query->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object) $row;
		}

		return $data;
	}

	//obtener los SH de un Sector especifico
	public function shBySector($sector){
		$query = Query::prun("SELECT sh.*,COUNT(e.id_elector) AS total FROM sectores_hijos AS sh
																INNER JOIN sectores AS s ON s.id_sector = sh.id_sector
																LEFT JOIN electores AS e ON e.id_sh = sh.id_sh
																WHERE s.id_sector = ?
																GROUP BY sh.id_sh
																ORDER BY total DESC",array("i",$sector));
		$data = array();
		
		while ($row = $query->result->fetch_array(MYSQLI_ASSOC)){
			$data[] = (object) $row;
		}

		return $data;
	}

	//Agregar un nuevo Sector
	public function sector_add($sector){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT id_sector FROM sectores WHERE sect_nombre = ?",array("s",$sector));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"Sector ya registrado");
			}else{
				$query = Query::prun("INSERT INTO sectores (sect_nombre) VALUES (?)",array("s",$sector));

				$this->rh->setResponse(true,"Sector agregado correctamente.",true,"inicio.php?ver=sectores");
			}
		}else{
			$this->rh->setResponse(false,"No tienes permisos para realizar esta accion.");
		}

		echo json_encode($this->rh);
	}

	public function sh_add($sector,$sh){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT id_sh FROM sectores_hijos WHERE sh_nombre = ? AND id_sector = ?",array("si",$sh,$sector));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"Ubicación ya registrada");
			}else{
				$query = Query::prun("INSERT INTO sectores_hijos (id_sector,sh_nombre) VALUES (?,?)",array("is",$sector,$sh));

				$this->rh->setResponse(true,"Ubicación agregada correctamente.",true,"inicio.php?ver=sectores");
			}
		}else{
			$this->rh->setResponse(false,"No tienes permisos para realizar esta accion.");
		}

		echo json_encode($this->rh);
	}//sh_add

	public function sector_get($sector){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT * FROM sectores WHERE id_sector = ?",array("i",$sector));

			if($query->result->num_rows > 0){
				$this->rh->data = (object) $query->result->fetch_array(MYSQLI_ASSOC);
				$this->rh->setResponse(true);
			}else{
				$this->rh->data = NULL;
				$this->rh->setResponse(false,"Sector no encontrado.");
			}
		}else{
			$this->rh->setResponse(false,"No tienes permisos para realizar esta accion.");
		}

		echo json_encode($this->rh);
	}//sector_get

	public function sh_get($ubicacion){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT * FROM sectores_hijos WHERE id_sh = ?",array("i",$ubicacion));

			if($query->result->num_rows > 0){
				$this->rh->data = (object) $query->result->fetch_array(MYSQLI_ASSOC);
				$this->rh->setResponse(true);
			}else{
				$this->rh->data = NULL;
				$this->rh->setResponse(false,"Ubicación no encontrada.");
			}
		}else{
			$this->rh->setResponse(false,"No tienes permisos para realizar esta accion.");
		}

		echo json_encode($this->rh);
	}//sh_get

	public function sector_edit($sector,$nombre){
		if($this->nivel=="A"){
			$query = Query::prun("UPDATE sectores SET
																		sect_nombre = ?
																	WHERE id_sector = ?"
														,array("si",$nombre,$sector));

			if($query->response){
				$this->rh->setResponse(true,"Cambios guardados correctamente.",true,"inicio.php?ver=sectores");
			}else{
				$this->rh->setResponse(false,"Ha ocurrido un error.");
			}
		}else{
			$this->rh->setResponse(false,"No tienes permisos para realizar esta accion.");
		}

		echo json_encode($this->rh);
	}//Sector_edit

	public function sh_edit($ubicacion,$sector,$nombre){
		if($this->nivel=="A"){
			$query = Query::prun("UPDATE sectores_hijos SET
																		id_sector = ?,
																		sh_nombre = ?
																	WHERE id_sh = ?"
														,array("isi",$sector,$nombre,$ubicacion));

			if($query->response){
				$this->rh->setResponse(true,"Cambios guardados correctamente.",true,"inicio.php?ver=sectores");
			}else{
				$this->rh->setResponse(false,"Ha ocurrido un error.");
			}
		}else{
			$this->rh->setResponse(false,"No tienes permisos para realizar esta accion.");
		}

		echo json_encode($this->rh);
	}//Sh_edit

	//Eliminar Sector
	public function del_sector($sector){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT id_sector FROM sectores WHERE id_sector = ? LIMIT 1",array("i",$sector));

			if($query->result->num_rows>0){
				$query = Query::prun("SELECT id_sector FROM sectores_hijos WHERE id_sector = ? LIMIT 1",array("i",$sector));

				if($query->result->num_rows>0){
					$this->rh->setResponse(false,"Este sector tiene ubicaciones agregadas. No se puede eliminar.");
				}else{
					$query = Query::run("DELETE FROM sectores WHERE id_sector = $sector LIMIT 1");

					if($query){
						$this->rh->setResponse(true,"Sector eliminado.",true,"inicio.php?ver=sectores");
					}else{
						$this->rh->setResponse(false,"Ha ocurido un error.");
					}
				}
			}else{
				$this->rh->setResponse(false,"Sector no encontrado.");
			}
		}else{
			$this->rh->setResponse(false,"No tienes permisos para realizar esta accion.");
		}

		echo json_encode($this->rh);
	}//del_sector

	//Eliminar Sector_hijo (ubicacion)
	public function del_sh($sh){
		if($this->nivel=="A"){
			$query = Query::prun("SELECT id_sh FROM sectores_hijos WHERE id_sh = ? LIMIT 1",array("i",$sh));

			if($query->result->num_rows>0){
				$query = Query::prun("SELECT id_sh FROM electores WHERE id_sh = ? LIMIT 1",array("i",$sh));

				if($query->result->num_rows>0){
					$this->rh->setResponse(false,"Esta ubicación tiene electores registrados. No se puede eliminar.");
				}else{
					$query = Query::run("DELETE FROM sectores_hijos WHERE id_sh = $sh LIMIT 1");

					if($query){
						$this->rh->setResponse(true,"Ubicación eliminada.",true,"inicio.php?ver=sectores");
					}else{
						$this->rh->setResponse(false,"Ha ocurido un error.");
					}
				}
			}else{
				$this->rh->setResponse(false,"Ubicación no encontrada.");
			}
		}else{
			$this->rh->setResponse(false,"No tienes permisos para realizar esta accion.");
		}

		echo json_encode($this->rh);
	}//del_sh

}//Class Sectores

// Logica
$modelSectores = new Sectores();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'obtener_sh':
				$sector  = $_POST['sector'];
				$elector = $_POST['elect'];

				$modelSectores->shByElector($sector,$elector);
			break;
			case 'sector_add':
				$sector = strtoupper($_POST['sector']);

				$modelSectores->sector_add($sector);
			break;
			case 'sh_add':
				$sector = $_POST['sector'];
				$sh     = strtoupper($_POST['ubicacion']);

				$modelSectores->sh_add($sector,$sh);
			break;
			case 'sector_get':
				$sector = $_POST['id'];

				$modelSectores->sector_get($sector);
			break;
			case 'sh_get':
				$ubicacion = $_POST['id'];

				$modelSectores->sh_get($ubicacion);
			break;
			case 'sector_edit':
				$sector = $_POST['id'];
				$nombre = strtoupper($_POST['sector']);

				$modelSectores->sector_edit($sector,$nombre);
			break;
			case 'sh_edit':
				$ubicacion = $_POST['id'];
				$sector    = $_POST['sector'];
				$nombre    = strtoupper($_POST['ubicacion']);

				$modelSectores->sh_edit($ubicacion,$sector,$nombre);
			break;
			case 'del_sector':
				$sector = $_POST['id'];

				$modelSectores->del_sector($sector);
			break;
			case 'del_sh':
				$sh = $_POST['id'];

				$modelSectores->del_sh($sh);
			break;
		endswitch;
	endif;
endif;
?>