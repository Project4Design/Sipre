<?
if(is_readable('../config/config.php')){
  require '../config/config.php';
}

class Electores{
	private $rh;
	private $user;
	private $nivel;
	private $delete;
	private $excel;

	public function __CONSTRUCT()
	{
		$this->rh     = new ResponseHelper();
		$this->user   = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
		$this->nivel  = isset($_SESSION['nivel']) ? $_SESSION['nivel'] : "X";
		$this->delete = array("(",")","-","/","_","-"," ","+");
	}

	//Consultar todos los electores
	public function consulta()
	{
    $query = Query::run("SELECT * FROM electores");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//consulta

	//Consultar todas las profesiones de los electores registrados
	public function profesiones()
	{

    $query = Query::run("SELECT elec_profesion AS profesion FROM electores GROUP BY elec_profesion");
    $data = array();

    while($registro = $query->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//profesiones

	//Consultar el todal de electores por sexo
	public function total_sexo($sexo)
	{

    $query = Query::run("SELECT COUNT(*) AS total FROM electores WHERE elec_sexo = '$sexo'");

    $registro = (object) $query->fetch_array(MYSQLI_ASSOC);

    return $registro->total;
	}//total_sexo

	//Obtener toda la informacion de un elector especifico
	public function obtener($id)
	{

    $query = Query::prun("SELECT e.*,c.id_centro,c.cent_nombre,s.id_sector,s.sect_nombre,sh.id_sh,sh.sh_nombre,u.id_user,u.user_nombres,u.user_apellidos FROM electores AS e
    																INNER JOIN centros AS c ON c.id_centro = e.id_centro
    																INNER JOIN sectores AS s ON s.id_sector = e.id_sector
    																INNER JOIN sectores_hijos AS sh ON sh.id_sh = e.id_sh
    																INNER JOIN usuarios AS u ON u.id_user = e.id_user
     													WHERE e.id_elector = ?",array("i",$id));

    if($query->result->num_rows >0){
    	$data = (object)$query->result->fetch_array(MYSQLI_ASSOC);
		}else{
			$data = NULL;
		}

    return $data;
	}//obtener

	//Agregar elector
	public function add($modo,$cedula,$nombres,$apellidos,$email,$sexo,$telefono,$telefono2,$nacimiento,$profesion,$sector,$ubicacion,$direccion,$centro,$facebook,$twitter,$instagram)
	{
		$query = Query::prun("SELECT elec_cedula FROM electores WHERE elec_cedula = ? LIMIT 1",array("i",$cedula));

		if($query->result->num_rows>0){
			$this->rh->setResponse(false,"Cedula ya registrada.");
		}else{
			$query = Query::prun("SELECT elec_email FROM electores WHERE elec_email = ? LIMIT 1",array("s",$email));

			if($query->result->num_rows>0){
				$this->rh->setResponse(false,"Correo ya registrado.");
			}else{
				$date = date_diff(date_create($nacimiento),date_create(Base::Fecha()));
    		$edad = $date->format('%y');

				if($edad>17){
					//Eliminar caracteres no deseados del numero de telefono
					$telefono  = str_replace($this->delete,"",$telefono);
					$telefono2 = str_replace($this->delete,"",$telefono2);

			  	$query = Query::prun("INSERT INTO electores (id_user,elec_nombres,elec_apellidos,elec_cedula,elec_email,elec_sexo,elec_telefono,elec_telefono2,elec_nacimiento,elec_profesion,id_sector,id_sh,elec_direccion,id_centro,elec_facebook,elec_twitter,elec_instagram)
																	VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
																	array("isssssssssiisisss",$this->user,$nombres,$apellidos,$cedula,$email,$sexo,$telefono,$telefono2,$nacimiento,$profesion,$sector,$ubicacion,$direccion,$centro,$facebook,$twitter,$instagram));

			  	if($query->response){
						$this->rh->setResponse(true,"Registro exitoso! <a href=\"?ver=electores&opc=ver&id={$query->id}\">Ver elector</a>");
			  	}else{
						$this->rh->setResponse(false,"Ah ocurrido un error. Revise la informacion proporcionada.");
			  	}
			  }else{
			  	$this->rh->setResponse(false,"El elector no puede ser menor de 18 años. Edad:{$edad}");
			  }
		  }
		}

		if($modo){
			echo json_encode($this->rh);
		}else{
			return $this->rh;
		}
		
	}//add

	//Editar elector
	public function edit($id,$cedula,$nombres,$apellidos,$email,$sexo,$telefono,$telefono2,$nacimiento,$profesion,$sector,$ubicacion,$direccion,$centro,$facebook,$twitter,$instagram)
	{
		
		$query = Query::prun("SELECT elec_cedula FROM electores WHERE elec_cedula = ? AND id_elector != ? LIMIT 1",array("si",$cedula,$id));
		
		if($query->result->num_rows>0){
		  $this->rh->setResponse(false,"Ya existe un elector registrado con esta cedula.");
		}else{
			$query = Query::prun("SELECT elec_email FROM electores WHERE elec_email = ? AND id_elector != ? LIMIT 1",array("si",$email,$id));
		
			if($query->result->num_rows>0){
			  $this->rh->setResponse(false,"Ya existe un elector registrado con este email.");
			}else{

				$telefono  = str_replace($this->delete,"",$telefono);
				$telefono2 = str_replace($this->delete,"",$telefono2);

		  	$query = Query::prun("UPDATE electores SET
															elec_nombres    = ?,
															elec_apellidos  = ?,
		  												elec_cedula     = ?,
															elec_email      = ?,
															elec_sexo       = ?,
															elec_telefono   = ?,
															elec_telefono2  = ?,
															elec_nacimiento = ?,
															elec_profesion  = ?,
															id_sector       = ?,
															id_sh           = ?,
															elec_direccion  = ?,
															id_centro       = ?,
															elec_facebook   = ?,
															elec_twitter    = ?,
															elec_instagram  = ?
														WHERE id_elector = ? LIMIT 1",
														array("sssssssssiisisssi",$nombres,$apellidos,$cedula,$email,$sexo,$telefono,$telefono2,$nacimiento,$profesion,$sector,$ubicacion,$direccion,$centro,$facebook,$twitter,$instagram,$id));
		  	if($query->response){
					$this->rh->setResponse(true,"Cambios guardados con exito!",true,"inicio.php?ver=electores&opc=ver&id={$id}");
			  }else{
			    $this->rh->setResponse(false,"Ha ocurrido un error inesperado.".$query->error);
			  }
			}
		}
		echo json_encode($this->rh);

	}//Modificar elector


	//
	public function delete($id){

		$query = Query::prun("SELECT id_elector FROM electores WHERE id_elector = ? LIMIT 1",array("i",$id));

		if($query->result->num_rows>0){

			$query = Query::prun("DELETE FROM electores WHERE id_elector = ?",array("i",$id));

			if($query->response){
				$this->rh->setResponse(true,"Elector eliminado.",true,"inicio.php?ver=electores");
			}else{
				$this->rh->setResponse(false,"Ha ocurrido un error inesperado.");
			}
		}else{
			$this->rh->setResponse(false,"Elector no encontrado.");
		}

		echo json_encode($this->rh);
	}//Activar

	//Consultar todos los electores registrados por un usuario especifico
	public function electoresByUser($user){
		$query = Query::prun("SELECT id_elector,elec_nombres,elec_apellidos,elec_cedula,elec_telefono,elec_email FROM electores
															Where id_user = ?",array("i",$user));
    $data = array();

    while($registro = $query->result->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//electoresByUser

	//Consultar todos los electores de un sector especifico + su SH
	public function electoresBySector($sector){
		$query = Query::prun("SELECT e.id_elector,e.elec_nombres,e.elec_apellidos,e.elec_cedula,e.elec_telefono,e.elec_email,sh.sh_nombre FROM electores AS e
																	INNER JOIN sectores AS s ON s.id_sector = e.id_sector
																	INNER JOIN sectores_hijos AS sh ON sh.id_sh = e.id_sh
																	WHERE e.id_sector = ?
																	GROUP BY e.id_elector",array("i",$sector));
    
    $data = array();

    while($registro = $query->result->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//electoresBySector
	
	//Consultar todos los electores de un SH especifico + su Sector
	public function electoresBySh($sh){
		$query = Query::prun("SELECT e.id_elector,e.elec_nombres,e.elec_apellidos,e.elec_cedula,e.elec_email,e.elec_telefono,s.sect_nombre FROM electores AS e
																	INNER JOIN sectores AS s ON s.id_sector = e.id_sector
																	INNER JOIN sectores_hijos AS sh ON sh.id_sh = e.id_sh
																	WHERE sh.id_sh = ?",array("i",$sh));
    
    $data = array();

    while($registro = $query->result->fetch_array(MYSQLI_ASSOC)){
    	$data[] = (object)$registro;
    }

    return $data;
	}//electoresBySh

	//Leer informacion de un excel y devuelve el contenido en una tabla
	public function preview($file){
		$this->excel  = new Excel();

		$data = $this->excel->table($file);

		$this->rh->setResponse(true);
		$this->rh->data = $data;

		echo json_encode($this->rh);
	}//Preview

	//Agrega electores desde un archivo Excel previamente cargado
	public function cargar(){

		$this->excel = new Excel();
		/*
		* Excel->cargar() devuelve:
		* False = Si no encuentra el archivo
		*	Array = Con los valores encontrados en el excel
		*/
		$return = $this->excel->cargar();
		$error = "";

		if(!$return){
			$this->rh->setResponse(false,"Ha ocurrido un error con el archivo. Intente cargarlo nuevamente.");
		}elseif(is_array($return)){
			/*
				$i = Iteracion
				$x = Contador de errores
			*/
			$i = 0; $x = 0;
			foreach ($return as $row){

				$cedula     = $row[2];
				$nombres    = ucwords(strtolower($row[0]));
				$apellidos  = ucwords(strtolower($row[1]));
				$email      = ucfirst(strtolower($row[3]));
				$sexo       = $row[4];
				$telefono   = $row[5];
				$telefono2  = $row[6];
				$nacimiento = ($row[7]!=NULL)? gmdate("Y-m-d", ($row[7] - 25569) * 86400) : NULL;
				$profesion  = $row[8];
				$sector     = $row[9];
				$ubicacion  = $row[10];
				$direccion  = $row[11];
				$centro     = $row[12];
				$facebook   = $row[13];
				$twitter    = $row[14];
				$instagram  = $row[15];

				$ok = $this->add(0,$cedula,$nombres,$apellidos,$email,$sexo,$telefono,$telefono2,$nacimiento,$profesion,$sector,$ubicacion,$direccion,$centro,$facebook,$twitter,$instagram);
				$nacimiento = Base::Convert($nacimiento);
				if(!$this->rh->response){
					$error .="<tr class=\"danger\">
										<td>".($x+1)."</td><td>{$nombres}</td><td>{$apellidos}</td><td>{$cedula}</td>
										<td>{$email}</td><td>{$sexo}</td><td>{$telefono}</td><td>{$telefono2}</td><td>{$nacimiento}</td>
										<td>{$profesion}</td><td>{$sector}</td><td>{$ubicacion}</td><td>{$direccion}</td>
										<td>{$centro}</td><td>{$facebook}</td><td>{$twitter}</td><td>{$instagram}</td>
										</tr>
										<tr><td></td><td colspan=\"16\">Razón: {$this->rh->msj} </td></tr>";
					$x++;
				}
				$i++;
			}
			//Si $i es mayor que $x, y existen errores
			if($i>$x && $error!=""){
				$this->rh->data = $error;
				$this->rh->setResponse(2,"<b> Han ocurrido algunos errores. Ver detalles en la tabla.</b><br>Electores registrados: ".($i-$x)."<br>Errores encontrados: ".$x);
			}elseif($i==$x && $error!=""){
				//Si $i Es igual a $x. Ningun registro se realizo.
				$this->rh->data = $error;
				$this->rh->setResponse(false,"<b> No se almacenaron electores.</b><br>Electores registrados: ".($i-$x)."<br>Errores encontrados: ".$x);
			}else{
				//Si no hubieron errores
				$this->rh->setResponse(true,"Registros almacenados correctamente.<br>Electores registrados: {$i}");
			}
		}else{
			$this->rh->setResponse(false);
		}

		echo json_encode($this->rh);
	}

	public function fdefault(){
		echo json_encode($this->rh);
	}//Cargar

}//Class Electores

$modelElectores = new Electores();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'add':
				$modo       = 1;
				$cedula     = $_POST["cedula"];
				$nombres    = ucwords(strtolower($_POST["nombres"]));
				$apellidos  = ucwords(strtolower($_POST["apellidos"]));
				$email      = ucfirst(strtolower($_POST["email"]));
				$sexo       = $_POST["sexo"];
				$telefono   = $_POST["telefono"];
				$telefono2  = $_POST["telefono2"];
				$nacimiento = Base::Convert($_POST["nacimiento"]);
				$profesion  = $_POST["profesion"];
				$sector     = $_POST["sector"];
				$ubicacion  = $_POST["ubicacion"];
				$direccion  = $_POST["direccion"];
				$centro     = $_POST["centro"];
				$facebook   = $_POST["facebook"];
				$twitter    = $_POST["twitter"];
				$instagram  = $_POST["instagram"];

				$modelElectores->add($modo,$cedula,$nombres,$apellidos,$email,$sexo,$telefono,$telefono2,$nacimiento,$profesion,$sector,$ubicacion,$direccion,$centro,$facebook,$twitter,$instagram);
			break;

			case 'edit':
				$id         = $_POST["id"];
				$cedula     = $_POST["cedula"];
				$nombres    = ucwords(strtolower($_POST["nombres"]));
				$apellidos  = ucwords(strtolower($_POST["apellidos"]));
				$email      = ucfirst(strtolower($_POST["email"]));
				$sexo       = $_POST["sexo"];
				$telefono   = $_POST["telefono"];
				$telefono2  = $_POST["telefono2"];
				$nacimiento = Base::Convert($_POST["nacimiento"]);
				$profesion  = $_POST["profesion"];
				$sector     = $_POST["sector"];
				$ubicacion  = $_POST["ubicacion"];
				$direccion  = $_POST["direccion"];
				$centro     = $_POST["centro"];
				$facebook   = $_POST["facebook"];
				$twitter    = $_POST["twitter"];
				$instagram  = $_POST["instagram"];

				$modelElectores->edit($id,$cedula,$nombres,$apellidos,$email,$sexo,$telefono,$telefono2,$nacimiento,$profesion,$sector,$ubicacion,$direccion,$centro,$facebook,$twitter,$instagram);
			break;

			case 'delete':
				$id     = $_POST['id'];

				$modelElectores->delete($id);
			break;

			case 'preview':
				$file = $_FILES['file']['tmp_name'];

				$modelElectores->preview($file);
			break;

			case 'cargar':
				$modelElectores->cargar();
			break;
		endswitch;
	endif;
endif;
?>