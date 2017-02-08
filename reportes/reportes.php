<?php
ob_start();

require_once '../config/config.php';

class Pdf_donaciones{
  private $rh;
  private $pdf;

  public function __CONSTRUCT()
  {
    $this->rh = new ResponseHelper();
    $this->pdf = new Pdf;
  }

  public function busqueda($fechas,$sel,$ext,$tipos)
  {
    $user = new Usuarios();

    $data = (object) array();
    $error = false;
    $tbody = $arguments = $params = "";
    $data->total = $data->f = $data->m = $x = $i = 0;

    if($fechas[0] == "" && $fechas[1] == "" && $sel == 0 && $ext == 0 && $tipos == NULL){
      $where = "";
    }else{
      $where = "WHERE ";
    }

    if($ext != 0){
      $extractor = $user->obtener($ext);
      $arguments .= $ext;
      $params .= "i";
      $where .= " dn.dn_extractor = ? ";
      $x++;
    }

    if($sel != 0){
      $selector = $user->obtener($ext);
      $pre = ($x > 0)? ",":" ";
      $arguments .= $pre.$sel;
      $params .= "i";
      $where .= ($x > 0)? " AND dn.dn_seleccionador = ?" : " dn.dn_seleccionador = ? ";
      $x++;
    }

    if($fechas[0] != "" || $fechas[1] != ""){
      $pre = ($x > 0) ? " AND " : " ";
      $fecha = $pre." (dn.dn_fecha_reg BETWEEN ? AND ?) ";
      $params .="ss";
      $where .= $fecha;

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
    }else{
      $params .= ",".$arguments;
      $cadena = explode(",",$params);
    }

    $query = Query::prun("SELECT dn.*,don.id_donante,don.don_sexo AS sexo,don.don_nombres,don.don_apellidos
                                  FROM donaciones AS dn
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

          $tbody .="<tr>
                    <td class=\"center\">".$i."</td>
                    <td class=\"center\">".$don["id_donacion"]."</td>
                    <td>".$don["don_nombres"]." ".$don["don_apellidos"]."</td>
                    <td class=\"center\">".$don["dn_tipo"]."</td>
                    <td class=\"center\">".$don["dn_segmento"]."</td>
                    <td class=\"center\">".Base::Convert($don["dn_fecha_reg"])."</td>
                  </tr>";
          $i++;
        }
      }else{
        $tbody = "<tr><td colspan=\"6\">No se encontraron resultados</td></tr>";
      }
    }else{
      $error = true;
    }

    if(!$error){
      $body ='
          <p>Fecha: '.Base::Convert(Base::Fecha()).'</p>
          <hr>
          <div class="col12">';

    if($sel != 0){
      $body .=
      '<p><b>Seleccionador: </b>'.$selector->user_nombres.' '.$selector->user_apellidos.'</p>';
    }
    if($ext != 0){
      $body .=
      '<p><b>Seleccionador: </b>'.$extractor->user_nombres.' '.$extractor->user_apellidos.'</p>';
    }
    if($fechas[0] != "" || $fechas[1] != ""){
      $body .=
      '<p><b>Rango de fechas: </b> '.$fechas[0].' - '.$fechas[1].'</p>';
    }

    if($ctipo > 0){
     $body .=
      '<p><b>Tipos de donacion: </b>';
      $i = 0;

      foreach($tipos as $k => $v){
        if($i>0){
          $body .= ", ".$v;
        }else{
          $body .= $v;
        }
        $i++;
      }
      $body .= "</p>";
    }

    $body.=
      '</div>
      <h3 class="center">Listado de donaciones</h3>
      <table class="table">
        <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Historia</th>
            <th class="text-center">Donante</th>
            <th class="text-center">Tipo</th>
            <th class="text-center">Segmento</th>
            <th class="text-center">Fecha</th>
          </tr>
        </thead>
        <tbody>
          '.$tbody.'
        </tbody>
      </table>';

    }else{
      $body = "<center> <h1>Ha ocurrido un error.</h1></cener>";
    }//Si existe un error

    $header = "<div class=\"col3\"><h4>H. Ranuarez<br>Balza</h4></div>";
    $header .= "<div class=\"col6\"><p class=\"center\"><b>RIF. J-30818309-1 - Telf: 0246-431.84.53</b><br>San Juan de los Morros - Estado Guárico<br><h3 class=\"center\" style=\"margin-top:0\">BANCO DE SANGRE</h3></p></div>";
    $footer = "<div class=\"center\">Cagua - Estado Aragua</div>";

    $output = $this->pdf->build($body,$header,$footer);
    $this->pdf->out($output.".pdf");
  }//Busqueda

  public function elector($id){

    $electores = new Electores();
    $elector = $electores->obtener($id);

    if($elector){
      $nombre = "Donacion_".$elector->id_elector;
      $fecha = Base::Convert($elector->elec_fecha_reg);
      $sexo = ($elector->elec_sexo=="M")?'Masculino':'Femenino';
      $instagram = ($elector->elec_instagram == NULL)?'N/A': $elector->elec_instagram;
      $facebook = ($elector->elec_facebook == NULL)?'N/A': $elector->elec_facebook;
      $twitter = ($elector->elec_twitter == NULL)?'N/A': $elector->elec_twitter;
      $body="
      <div class=\"row\">
        <h3 class=\"center\">Datos Personales Elector</h3><hr>

        <p><b>Nombres:</b> {$elector->elec_nombres}</p>
        <p><b>Apellidos:</b> {$elector->elec_apellidos}</p>
        <p><b>Sexo:</b> {$sexo}</p>
        <p><b>Telefono:</b> {$elector->elec_telefono}
        <p><b>Direccion:</b> {$elector->elec_direccion}
        <p><b>Correo Electronico:</b> {$elector->elec_email}
        <p><b>Direccion:</b> {$elector->elec_direccion}
        <p><b>Profesion:</b> {$elector->elec_profesion}
        <p><b>Instagram:</b> {$instagram}
        <p><b>Twitter:</b> {$twitter}
        <p><b>Facebook:</b> {$facebook}
        <h3 class=\"center\">Informacion de centro de votacion</h3><hr>

        <p><b>Sector:</b> {$elector->sect_nombre} </p>
        <p><b>Ubicacion: </b>{$elector->sh_nombre}</p>
        <p><b>Ubicacion: </b>{$elector->cent_nombre}</p>

      </div>

      <
      ";
    }else{
      $nombre = "Elector";
      $body = "<center> <h1>Ha ocurrido un error.</h1></cener>";
    }

    $header = "<div class=\"col3\"><h4>J.Manuel<br>DaCamara</h4></div>";
    $header .= "<div class=\"col6\"><p class=\"center\"><b>RIF. J-30818309-1 - Telf: 0246-431.84.53</b><br>Cagua - Estado Aragua<br><h3 class=\"center\" style=\"margin-top:0\">SIPRE</h3></p></div>";
    $header .= "<div class=\"col3\" style=\"border:1px solid black;\"><h2 class=\"center\">Cedula<br>{$elector->elec_cedula}</h2></div>";
    $footer = "<div class=\"center\">SIPRE - Sistema Privado de Registro de Electores</div>";

    $output = $this->pdf->build($body,$header,$footer);
    ob_end_clean();
    ob_end_flush();
    $this->pdf->out($output,$nombre.".pdf");
  }


}//Pdf_donaciones

$pdf = new Pdf_donaciones();

if(isset($_GET['action'])):
  switch ($_GET['action']):
    case 'busqueda':
        if(isset($_POST['fechas'])){
          $fecha = explode("-",$_POST['fechas']);
          $inicio = $fecha[0];
          $fin    = $fecha[1];
        }else{
          $inicio = NULL;
          $fin    = NULL;
        }
        $registrado  = isset($_POST['registrado'])?$_POST['registrado']:0;
        $centro      = isset($_POST['centro'])?$_POST['centro']:0;
        $sector      = isset($_POST['sector'])?$_POST['sector']:0;
        $ubicacion   = isset($_POST['ubicacion'])?$_POST['ubicacion']:0;
        $profesion   = isset($_POST['profesion'])?$_POST['profesion']:0;
        
        /*if(isset($_POST['tipos'])){
          $tipos  = $_POST['tipos'];
        }else{
          $tipos = NULL;
        }*/

        $pdf->consultaElectores($registrado,$centro,$sector,$ubicacion,$profesion);
      break;
    case 'elector':
      $id = $_GET['id'];
      $pdf->elector($id);
    break;

    case 'estado':

      $pdf->estado($id,$estado);
    break;
  endswitch;
endif;
?>
