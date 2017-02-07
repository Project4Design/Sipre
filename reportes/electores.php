<?php
ob_start();
require_once '../config/config.php';

class Pdf_electores{
  private $pdf;
  private $elect;
  private $fecha;

  public function __CONSTRUCT()
  {
    $this->pdf   = new Pdf();
    $this->elect = new Electores();
    $this->fecha = Base::Fecha("d-m-Y");
  }

  public function busqueda($fechas,$sel,$ext,$tipos)
  {

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

    $query = Query::prun("SELECT dn.*,don.id_donante,don.elec_sexo AS sexo,don.elec_nombres,don.elec_apellidos
                                  FROM donantes AS dn
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
                    <td class=\"center\">".$don["id_donante"]."</td>
                    <td>".$don["elec_nombres"]." ".$don["elec_apellidos"]."</td>
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
      '<p><b>Tipos de donante: </b>';
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
      <h3 class="center">Listado de donantes</h3>
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

    $header = "<div class=\"col3\"><h1>Santa<br>Rosalía</h1></div>";
    $header .= "<div class=\"col6\"><p class=\"center\"><b>RIF. J-30818309-1 - Telf: 0246-431.84.53</b><br>San Juan de los Morros - Estado Guárico<br><h3 class=\"center\" style=\"margin-top:0\">BANCO DE SANGRE</h3></p></div>";
    $footer = "<div class=\"center\">Santa Rosalia - San Juan de los Morros - Estado Guárico</div>";

    $output = $this->pdf->build($body,$header,$footer);
    $this->pdf->out($output);
  }//Busqueda

  public function elector($id)
  {
    $elector = $this->elect->obtener($id);

    if($elector){
      $nombre = "Elector_".$elector->id_elector;
      $fecha = Base::Convert($elector->elec_nacimiento);
      $sexo  = ($elector->elec_sexo=="M")?'Masculino':'Femenino';

      $body="<hr>
      <div class=\"row\">
        <h3>Datos del elector</h3>
      </div>

      <div class=\"col6\">
        <p><b>Cedula:</b> {$elector->elec_cedula}</p>
        <p><b>Nombres:</b> {$elector->elec_nombres} {$elector->elec_apellidos}</p>
        <p><b>Profesión:</b> {$elector->elec_profesion}</p>
        <p><b>Sexo:</b> {$sexo}</p>
        <p><b>Fecha de nacimiento:</b> {$fecha}</p>
      </div>
      <hr>
      <div class=\"row\">
        <h3>Contacto</h3>
        <p><b>Correo:</b> {$elector->elec_email}</p>
        <p><b>Telefono:</b> {$elector->elec_telefono}</p>

        <p><b>Facebook:</b>";
        if($elector->elec_facebook){ $body .= $elector->elec_facebook; }else{ $body .="N/A"; }
        $body.="</p>

        <p><b>Twitter:</b>";
        if($elector->elec_twitter){ $body .= "@".$elector->elec_twitter; }else{ $body .="N/A"; }
        $body.="</p>

        <p><b>Instagram:</b>";
        if($elector->elec_instagram){ $body .= "@".$elector->elec_instagram; }else{ $body .="N/A"; }
        $body.="</p>
      </div>
      <hr>

      <div class=\"row\">
        <h3>Dirección</h3>
        <p><b>Sector:</b> {$elector->sect_nombre}</p>
        <p><b>Ubicación:</b> {$elector->sh_nombre}</p>
        <p><b>Dirección:</b> {$elector->elec_direccion}</p>
      </div>

      <div class=\"row\">
        <h3>Centro de votación</h3>
        <p>{$elector->cent_nombre}</p>
      </div>
      ";
    }else{
      $nombre = "Elector";
      $body = "<center> <h1>Ha ocurrido un error.</h1></cener>";
    }

    $header = "<div class=\"col9\">
                <p class=\"right\">Base de Datos Muicipio Sucre - Edo. Aragua
                </p>
              </div>
              <div class=\"col3\">
                <p class=\"right\" style=\"margin-top:0\">{$this->fecha}</p>
              </div>";
    $footer = "<div class=\"center\">La Información en este documento es de uso confidencial.</div>";

    $output = $this->pdf->build($body,$header,$footer);
    $this->pdf->out($output,$nombre);
  }//Donante

  public function electores()
  {
    $elector = $this->elect->consulta();

    if(count($electores)>0){
      $nombre = "electores";
      $tbody = ""; $i = 1;
      
      foreach ($elector as $d){
        $tbody .="
        <tr>
          <td class=\"center\">{$i}</td>
          <td>{$d->elec_nombres}</td>
          <td>{$d->elec_apellidos}</td>
          <td class=\"center\">{$d->elec_cedula}</td>
          <td class=\"center\">{$d->elec_email}</td>
          <td class=\"center\">{$d->elec_telefono}</td>
        </tr>
        ";
        $i++;
      }

      $body = "
      <hr>
      <h3 class=\"center\">Listado de electores</h3>
      <table class=\"table\">
        <thead>
            <tr>
              <th>#</th>
              <th>Nombres</th>
              <th>Apellidos</th>
              <th>Cedula</th>
              <th>Email</th>
              <th>Telefono</th>
            </tr>
          </thead>
        <tbody>
          {$tbody}
        </tbody>
      </table>
      ";

    }else{
      $nombre = "Electores";
      $body = "<center> <h1>No hay electores registrados.</h1></cener>";
    }

    $header = "<div class=\"col9\">
                <p class=\"right\">Base de Datos Muicipio Sucre - Edo. Aragua
                </p>
              </div>
              <div class=\"col3\">
                <p class=\"right\" style=\"margin-top:0\">{$this->fecha}</p>
              </div>";
    $footer = "<div class=\"center\">La Información en este documento es de uso confidencial.</div>";

    $output = $this->pdf->build($body,$header,$footer);
    $this->pdf->out($output,$nombre);
  }//Electores

}//Pdf_electores

$pdf = new Pdf_electores();

if(isset($_GET['action'])):
  switch ($_GET['action']):
    case 'busqueda':
      if(isset($_GET['fechas'])){ $fechas = $_GET['fechas']; }else{ $fechas = array("",""); }
      if(isset($_GET['selector'])){ $sel = $_GET['selector']; }else{ $sel = 0; }
      if(isset($_GET['extractor'])){ $ext = $_GET['extractor']; }else{ $ext = 0; }
      if(isset($_GET['tipos'])){ $tipos = $_GET['tipos']; }else{ $tipos = NULL; }

      $pdf->busqueda($fechas,$sel,$ext,$tipos);
    break;
    case 'elector':
      $elector = $_GET['id'];

      $pdf->elector($elector);
    break;
    case 'electores_pdf':
      $pdf->electores();
    break;
    default:
      return false;
    break;
  endswitch;
endif;
?>