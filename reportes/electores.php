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

  public function busqueda($registro,$nacimiento,$registrado,$centro,$sector,$ubicacion,$profesion)
  {
    $table = $arguments = $params = "";
    $total = $x = $i = 0;
    //===================================|| CONSTRUYENDO LA QUERY DE BUSQUEDA ||=================================================
    if($registro[0] == "" && $registro[1] == "" && $nacimiento[0] == "" && $nacimiento[1] == "" && $registrado == 0 && $centro == 0 && $sector == 0 && $ubicacion == 0 && $profesion == NULL){
      $where = "";
    }else{
      $where = "WHERE ";
    }

    if($registrado > 0){
      $arguments .= $registrado;
      $params .= "i";
      $where .= " e.id_user = ? ";
      $x++;
    }

    if($centro > 0){
      $pre = ($x > 0)? ",":" ";
      $arguments .= $pre.$centro;
      $params .= "i";
      $where .= ($x > 0)?" AND c.id_centro = ?" : " c.id_centro = ? ";
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
      $x++;
    }

    
    if($sector > 0){
      $pre = ($x > 0)? ",":" ";
      $arguments .= $pre.$sector;
      $params .= "i";
      $where .= ($x > 0)?" AND s.id_sector = ?" : " s.id_sector = ? ";
      $x++;
    }

    if($ubicacion > 0){
      $pre = ($x > 0)? ",":"";
      $arguments .= $pre.$ubicacion;
      $params .= "i";
      $where .= ($x > 0)?" AND sh.id_sh = ?" : " sh.id_sh = ? ";
      $x++;
    }

    if($profesion){
      $pre = ($x > 0) ? " AND (" : " (";
      $preQuery = ""; $j = 0;
      foreach ($profesion as $k => $v) {
        $preQuery .= ($j > 0) ? " || e.elec_profesion = ?" : " e.elec_profesion = ?";
        $preArg = ($x > 0)? ",":"";
        $arguments .= $preArg.$v;

        $params .= "s";
        $x++; $j++;
      }

      $where .= $pre.$preQuery.")";
    }

    
    if($params == "" && $arguments == ""){
      $cadena = NULL;
    }else{
      $params .= ",".$arguments;
      $cadena = explode(",",$params);
    }
    

    $query = Query::prun("SELECT e.* , s.* , sh.* , c.* FROM electores AS e 
                INNER JOIN sectores AS s ON s.id_sector = e.id_sector 
                INNER JOIN sectores_hijos AS sh ON sh.id_sh = e.id_sh 
                INNER JOIN centros AS c ON c.id_centro=e.id_centro $where",$cadena);

    if($query->response){
      if($query->result->num_rows > 0){

        if($sector>0||$ubicacion>0){ $s = new Sectores(); }
        $i = 1;
        //Bu
        while ($elec = $query->result->fetch_array(MYSQLI_ASSOC)) {
          $total ++;

          $table .="<tr>
                      <td class=\"text-center\">".$i."</td>
                      <td>".$elec['sh_nombre']."</td>
                      <td>".$elec['elec_nombres']."</td>
                      <td>".$elec['elec_apellidos']."</td>
                      <td>".$elec['elec_cedula']."</td>
                      <td>".$elec['elec_telefono']."</td>
                      <td>".$elec['elec_email']."</td>
                    </tr>";
          $i++;
        }
        $body ='
          <p>Fecha: '.Base::Convert(Base::Fecha()).'</p>
          <hr>
          <div class="col12">';
          if($registro>0){
            $re = new Usuarios(); $us = $re->obtener($registro);
            $body .= '<p><b>Registro por: </b> '.$us->user_nombres.' '.$us->user_apellidos.'</p>';
          }
          if($registro[0] != "" || $registro[1] != ""){
            $body .= '<p><b>Fecha de registro: </b> '.$registro[0].' - '.$registro[1].'</p>';
          }
          if($nacimiento[0] != "" || $nacimiento[1] != ""){
            $body .= '<p><b>Fecha de nacimiento: </b> '.$nacimiento[0].' - '.$nacimiento[1].'</p>';
          }
          if($sector > 0){
            $sect = $s->sector_obtener($sector);
            $body .= '<p><b>Sector: </b>'.$sect->sect_nombre.'</p>';
          }
          if($ubicacion > 0){
            $ubc = $s->sh_obtener($ubicacion);
            $body .= '<p><b>Ubicación: </b>'.$ubc->sh_nombre.'</p>';
          }
          if($centro > 0){
            $c = new Centros(); $cent = $c->obtener($centro);
            $body .= '<p><b>Centro: </b>'.$cent->cent_nombre.'</p>';
          }
          if($profesion){
            $body .= '<p><b>Profesión: </b>'; $j = 0;
            foreach ($profesion as $k => $v) {
              $pre = ($j>0)?', ':'';
              $body.= $pre.$v;
              $j++;
            }
            $body.='</p>';
          }






        $body .= '</div>'; //Cerrando col12 de parametros de busqueda

        $body .='
          <h3 class="center">Listado de Electores</h3>
          <table class="table">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">Ubicación</th>
                <th class="text-center">Nombres</th>
                <th class="text-center">Apellidos</th>
                <th class="text-center">Cedula</th>
                <th class="text-center">Teléfono</th>
                <th class="text-center">Email</th>
              </tr>
            </thead>
            <tbody>
              '.$table.'
            </tbody>
          </table>';
      }else{
        $body   = "<center> <h1>No se encontraron electores.</h1></cener>";
      }
    }else{
      $body   = "<center> <h1>Ha ocurrido un error.</h1></cener>";
    }

    $header = "<div class=\"col9\">
                <p class=\"right\">Base de Datos Muicipio Sucre - Edo. Aragua
                </p>
              </div>";
    $footer = "<div class=\"center\">La Información en este documento es de uso confidencial.</div>";

    $output = $this->pdf->build($body,$header,$footer);
    ob_clean();
    $this->pdf->out($output,"Electores");
  }//Consulta

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
        <p><b>Teléfono:</b> {$elector->elec_telefono}</p>
        <p><b>2do. Teléfono:</b> {$elector->elec_telefono2}</p>

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
  }//Elector

  public function electores()
  {
    $electores = $this->elect->consulta();

    if(count($electores)>0){
      $nombre = "electores";
      $tbody = ""; $i = 1;
      
      foreach ($electores as $d){
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
    ob_clean();
    $this->pdf->out($output,$nombre);
  }//Electores

}//Pdf_electores

$pdf = new Pdf_electores();

if(isset($_GET['action'])):
  switch ($_GET['action']):
    case 'busqueda':
      $registro    = isset($_GET['registro'])?$_GET['registro']:array("","");
      $nacimiento  = isset($_GET['nacimiento'])?$_GET['nacimiento']:array("","");
      $registrado  = isset($_GET['registrado'])?$_GET['registrado']:0;
      $centro      = isset($_GET['centro'])?$_GET['centro']:0;
      $sector      = isset($_GET['sector'])?$_GET['sector']:0;
      $ubicacion   = isset($_GET['ubicacion'])?$_GET['ubicacion']:0;
      $profesion   = isset($_GET['profesion'])?$_GET['profesion']:NULL;

      $pdf->busqueda($registro,$nacimiento,$registrado,$centro,$sector,$ubicacion,$profesion);
    break;
    case 'elector':
      $elector = $_GET['id'];

      $pdf->elector($elector);
    break;
    case 'electores':
      $pdf->electores();
    break;
    default:
      return false;
    break;
  endswitch;
endif;
?>