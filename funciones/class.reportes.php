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

	public function donaciones($fechas,$sel,$ext,$tipos)
	{
		$data = (object) array();
		$data->table = $arguments = $params = "";
		$data->link  = array();
		$data->total = $data->f = $data->m = $x = $i = 0;

		if($fechas[0] == "" && $fechas[1] == "" && $sel == 0 && $ext == 0 && $tipos == NULL){
			$where = "";
		}else{
			$where = "WHERE ";
		}

		if($ext != 0){
			$arguments .= $ext;
			$params .= "i";
			$where .= " dn.dn_extractor = ? ";
			$data->link["extractor"] = $ext;
			$x++;
		}

		if($sel != 0){
			$pre = ($x > 0)? ",":" ";
			$arguments .= $pre.$sel;
			$params .= "i";
			$where .= ($x > 0)? " AND dn.dn_seleccionador = ?" : " dn.dn_seleccionador = ? ";
			$data->link["selector"] = $sel;
			$x++;
		}

		if($fechas[0] != "" || $fechas[1] != ""){
			$pre = ($x > 0) ? " AND " : " ";
			$fecha = $pre." (dn.dn_fecha_reg BETWEEN ? AND ?) ";
			$params .="ss";
			$where .= $fecha;
			$data->link["fechas"] = $fechas;

			foreach ($fechas as $k => $v) {
				if($fechas[0] != ""){
					$inicio = Base::Convert($fechas[0]);
				}else{
					$inicio = Base::Fecha();
				}

				if($fechas[1] != ""){
					$fin = Base::Convert($fechas[1]);
				}else{
					$fin = Base::Fecha();
				}
			}
			$arguments  .= ($x > 0)? "," : "";
			$arguments  .= $inicio.",".$fin;
			$x++;
		}

		$ctipo = count($tipos); $i = 0;
		if($ctipo > 0){
			$pre = ($x > 0) ? " AND " : " ";
			$data->link["tipos"] = $tipos;
			$tipo = $pre." ( ";

			foreach($tipos as $k => $v){
				if($i>0){
					$tipo .= " OR dn.dn_tipo = ? ";
				}else{
					$tipo .= " dn.dn_tipo = ? ";
				}

				$arguments .= ($x > 0) ? ",".$v : $v;
				$params .= "s";
				$x++;
				$i++;
			}

			$tipo .= ") ";
			$where .= $tipo;
		}

		if($params == "" && $arguments == ""){
			$cadena = NULL;
			$data->link = "#";
		}else{
			$data->link = http_build_query($data->link);
			$params .= ",".$arguments;
			$cadena = explode(",",$params);
		}

		$query = Query::prun("SELECT dn.*,don.id_donante,don.don_sexo AS sexo,don.don_nombres,don.don_apellidos FROM donaciones AS dn
																		INNER JOIN donantes AS don ON don.id_donante = dn.id_donante
																		$where",$cadena);

		if($query->response){
			if($query->result->num_rows > 0){
				$i = 1;
				while ($don = $query->result->fetch_array(MYSQLI_ASSOC)) {
					$data->total ++;

					if($don["sexo"] == "M"){
						$data->m++;
					}else{
						$data->f++;
					}

					$data->table .="<tr>
								            <td class=\"text-center\">".$i."</td>
								            <td>".$don["dn_historia"]."</td>
								            <td><a href=\"?ver=donantes&opc=ver&id=".$don["id_donante"]."\">".$don["don_nombres"]." ".$don["don_apellidos"]."</a></td>
								            <td>".$don["dn_tipo"]."</td>
								            <td>".$don["dn_segmento"]."</td>
								            <td class=\"text-center\">".Base::Convert($don["dn_fecha_reg"])."</td>
								            <td class=\"text-center\">
								              <a class=\"btn btn-primary btn-sm\" href=\"?ver=donaciones&opc=ver&id=".$don["id_donacion"]."\"><i class=\"fa fa-search\"></i></a>
								              <a class=\"btn btn-danger btn-sm\" href=\"?ver=reportes&opc=donaciones&id=".$don["id_donacion"]."\"><i class=\"fa fa-print\"></i></a>
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
	}//Donaciones

}//Class Reportes

$modelReportes = new Reportes();

if(Base::IsAjax()):
	if(isset($_POST['action'])):
	  switch ($_POST['action']):
			case 'donaciones':
				$fechas = $_POST['fechas'];
				$sel    = $_POST['selector'];
				$ext    = $_POST['extractor'];
				
				if(isset($_POST['tipos'])){
					$tipos  = $_POST['tipos'];
				}else{
					$tipos = NULL;
				}

				$modelReportes->donaciones($fechas,$sel,$ext,$tipos);
			break;
				
			case 'edit_don':
				$modelReportes->edit($id,$cedula,$nombres,$apellidos,$sexo,$nacimiento,$fecha_nac,$edad,$dh,$dh_pob,$dh_estado,$dh_tlf,$dt,$dt_pob,$dt_estado,$dt_tlf,$profesion,$ocupacion,$preguntas);
			break;

			case 'estado':
				$id     = $_POST["id"];
				$estado = $_POST["estado"];

				$modelReportes->estado($id,$estado);
			break;
		endswitch;
	endif;
endif;
?>