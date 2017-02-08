<?
if(is_readable('../config/config.php')){
  require_once '../config/config.php';
}

class Reportes{
	private $rh;
	private $pdf;

	public function __CONSTRUCT()
	{
		$this->rh = new ResponseHelper();
	}

	public function electores($registro,$nacimiento,$registrado,$centro,$sector,$ubicacion,$profesion)
	{
		$data = (object) array();
		$data->table = $arguments = $params = "";
		$data->link  = array();
		$data->total = $x = $i = 0;

		if($registro[0] == "" && $registro[1] == "" && $nacimiento[0] == "" && $nacimiento[1] == "" && $registrado == 0 && $centro == 0 && $sector == 0 && $ubicacion == 0 && $profesion == NULL){
			$where = "";
		}else{
			$where = "WHERE ";
		}

		if($registrado > 0){
			$arguments .= $registrado;
			$params .= "i";
			$where .= " e.id_user = ? ";
			$data->link["registrado"] = $registrado;
			$x++;
		}

		if($centro > 0){
			$pre = ($x > 0)? ",":" ";
			$arguments .= $pre.$centro;
			$params .= "i";
			$where .= ($x > 0)?" AND c.id_centro = ?" : " c.id_centro = ? ";
			$data->link["centro"] = $centro;
			$x++;
		}

		if($registro[0] != "" || $registro[1] != ""){
      $pre = ($x > 0) ? " AND " : " ";
      $fecha = $pre." (e.elec_fecha_reg BETWEEN ? AND ?) ";
      $params .="ss";
      $where .= $fecha;

      foreach ($registro as $k => $v) {
        if($registro[0] != ""){
          $inicio = Base::Convert($registro[0]);
        }else{
          $inicio = Base::Fecha();
        }

        if($registro[1] != ""){
          $fin = Base::Convert($registro[1]);
        }else{
          $fin = Base::Fecha();
        }
      }

      $arguments  .= ($x > 0)? "," : "";
      $arguments  .= $inicio.",".$fin;
      $data->link["registro"] = $registro;
      $x++;
    }

    if($nacimiento[0] != "" || $nacimiento[1] != ""){
      $pre = ($x > 0) ? " AND " : " ";
      $fecha = $pre." (e.elec_nacimiento BETWEEN ? AND ?) ";
      $params .="ss";
      $where .= $fecha;

      foreach ($nacimiento as $k => $v) {
        if($nacimiento[0] != ""){
          $inicio = Base::Convert($nacimiento[0]);
        }else{
          $inicio = Base::Fecha();
        }

        if($nacimiento[1] != ""){
          $fin = Base::Convert($nacimiento[1]);
        }else{
          $fin = Base::Fecha();
        }
      }

      $arguments  .= ($x > 0)? "," : "";
      $arguments  .= $inicio.",".$fin;
      $data->link["nacimiento"] = $nacimiento;
      $x++;
    }

		
		if($sector > 0){
			$pre = ($x > 0)? ",":" ";
			$arguments .= $pre.$sector;
			$params .= "i";
			$where .= ($x > 0)?" AND s.id_sector = ?" : " s.id_sector = ? ";
			$data->link["sector"] = $sector;
			$x++;
		}

		if($ubicacion > 0){
			$pre = ($x > 0)? ",":"";
			$arguments .= $pre.$ubicacion;
			$params .= "i";
			$where .= ($x > 0)?" AND sh.id_sh = ?" : " sh.id_sh = ? ";
			$data->link["ubicacion"] = $ubicacion;
			$x++;
		}

		if($profesion){
			$pre = ($x > 0) ? " AND (" : " (";
			$preQuery = ""; $j=0;

			foreach ($profesion as $k => $v) {
				$preQuery .= ($j > 0) ? " || e.elec_profesion = ?" : " e.elec_profesion = ?";
				$preArg = ($x > 0)? ",":"";
				$arguments .= $preArg.$v;

				$params .= "s";
				$x++; $j++;
			}

			$where .= $pre.$preQuery.")";
			$data->link["profesion"] = $profesion;
		}

		//Link para el boton imprimir en la vista
		if($params == "" && $arguments == ""){
			$cadena = NULL;
			$data->link = "#";
		}else{
			$data->link = http_build_query($data->link);
			$params .= ",".$arguments;
			$cadena = explode(",",$params);
		}
		//fin_link para boton

		$query = Query::prun("SELECT e.* , s.* , sh.* , c.* FROM electores AS e 
							  INNER JOIN sectores AS s ON s.id_sector = e.id_sector 
							  INNER JOIN sectores_hijos AS sh ON sh.id_sh = e.id_sh 
							  INNER JOIN centros AS c ON c.id_centro=e.id_centro $where",$cadena);

		if($query->response){
			if($query->result->num_rows > 0){
				$i = 1;
				while ($don = $query->result->fetch_array(MYSQLI_ASSOC)) {
					$data->total ++;

					$data->table .="<tr>
								            <td class=\"text-center\">".$i."</td>
								            <td>{$don['sh_nombre']}</td>
								            <td>{$don['elec_nombres']}</td>
								            <td>{$don['elec_apellidos']}</td>
								            <td>{$don['elec_email']}</td>
								            <td>{$don['elec_telefono']}</td>
								            <td class=\"text-center\">
								              <a class=\"btn btn-primary btn-flat btn-sm\" href=\"?ver=electores&opc=ver&id={$don['id_elector']}\"><i class=\"fa fa-search\"></i></a>
								              <a class=\"btn btn-danger btn-flat btn-sm\" href=\"reportes/electores.php?action=elector&id={$don["id_elector"]}\"><i class=\"fa fa-print\"></i></a>
								            </td>
													</tr>";
					$i++;
				}
			}else{
				$data->table = "";
			}
			$this->rh->setResponse(true);
		}else{
			$this->rh->setResponse(false);
		}

		$data->query = $where;
		$data->param = $cadena;

		$this->rh->data = $data;

		echo json_encode($this->rh);
	}//Electores

}//Class Reportes

$modelReportes = new Reportes();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'electores':
				if($_POST['registro']!=""){
					$registro = explode(" | ",$_POST['registro']);
				}else{
					$registro = array("","");
				}

				if($_POST['nacimiento']!=""){
					$nacimiento = explode(" | ",$_POST['nacimiento']);
				}else{
					$nacimiento = array("","");
				}
				$registrado  = isset($_POST['registrado'])?$_POST['registrado']:0;
				$centro      = isset($_POST['centro'])?$_POST['centro']:0;
				$sector      = isset($_POST['sector'])?$_POST['sector']:0;
				$ubicacion   = isset($_POST['ubicacion'])?$_POST['ubicacion']:0;
				$profesion   = isset($_POST['profesion'])?$_POST['profesion']:NULL;

				$modelReportes->electores($registro,$nacimiento,$registrado,$centro,$sector,$ubicacion,$profesion);
			break;
		endswitch;
	endif;
endif;
?>