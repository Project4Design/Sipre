<?php
ob_start();
require_once '../config/config.php';

class Pdf_sectores{
  private $pdf;
  private $sectores;
  private $fecha;

  public function __CONSTRUCT()
  {
    $this->pdf   = new Pdf();
    $this->sectores = new Sectores();
    $this->fecha = Base::Fecha("d-m-Y");
  }



  public function sector_solo($id){
    $sector = $this->sectores->sector_obtener($id);

    if(count($sector)>0){
      $ubicaciones = $this->sectores->shBySector($id);
      $electores   = new Electores();
      $total       = $electores->consulta();
      $elector     = $electores->electoresBySector($id);

      $poblacion   = round((count($elector)*100)/count($total),2);

      $nombre = $sector->sect_nombre;

      //Ubicaciones
      $ubi = "";  $i = 1;
      foreach ($ubicaciones as $d) {
        if($i==1){
          $ubi.="<div class=\"row\">";
        }

        $ubi .="
          <div class=\"col3\">
            <p class=\"center\">{$d->sh_nombre}<br>
              {$d->total}
            </p>
          </div>
          ";

        if($i==4){
          $i==0;
          $ubi.="</div>";
        }
          $i++;
      }//Foreach Ubicaciones

      //Electores
      $tbody = ""; $i = 1;
      foreach ($elector as $d) {
        $tbody .="
        <tr>
          <td class=\"center\">{$i}</td>
          <td>{$d->elec_nombres} {$d->elec_apellidos}</td>
          <td>{$d->elec_cedula}</td>
          <td>{$d->elec_telefono}</td>
          <td>{$d->elec_email}</td>
          <td>{$d->sh_nombre}</td>
        </tr>
        ";
        $i++;
      }//Foreach Electores

      $body="<hr>
        <div class=\"row\">
          <h4 class=\"center\">informacion - {$sector->sect_nombre}</h4>
          <div class=\"col4\">
            <b>Ubicaicones: </b>".count($ubicaciones)."
          </div>
          <div class=\"col4\">
            <b>Electores: </b>".count($elector)."
          </div>
          <div class=\"col4\">
            <b>Poblacion: </b> {$poblacion}<small>%</small>
          </div>
        </div>
        
        <hr>
        <h4 class=\"center\">Ubicaciones</h4>
        {$ubi}
        <hr>
        <h4 class=\"center\">Electores</h4>
        <table class=\"table\">
          <thead>
              <tr>
                <th>#</th>
                <th>Elector</th>
                <th>Cedula</th>
                <th>Telefono</th>
                <th>Correo</th>
                <th>Ubicacion</th>
              </tr>
            </thead>
          <tbody>
            {$tbody}
          </tbody>
        </table>
      ";

    }else{
      $nombre = "sector";
      $body = "<center> <h1>No hay informacion sobre este sector.</h1></cener>";
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
  }//sector_solo

  public function sh_solo($id){
    $sh = $this->sectores->sh_obtener($id);

    if(count($sh)>0){
      $electores   = new Electores();
      $total     = count($electores->consulta());
      $elector   = $electores->electoresBySh($id);

      $poblacion   = round((count($elector)*100)/$total,2);

      $nombre = $sh->sh_nombre;

      //Electores
      $tbody = ""; $i = 1;
      foreach ($elector as $d) {
        $tbody .="
        <tr>
          <td class=\"center\">{$i}</td>
          <td>{$d->sect_nombre}</td>
          <td>{$d->elec_nombres} {$d->elec_apellidos}</td>
          <td>{$d->elec_cedula}</td>
          <td>{$d->elec_email}</td>
          <td>{$d->elec_telefono}</td>
        </tr>
        ";
        $i++;
      }//Foreach Electores

      $body="<hr>
        <div class=\"row\">
          <h4 class=\"center\">informacion - {$sh->sh_nombre}</h4>
          <div class=\"col4\">
            <b>Electores: </b>".count($elector)."
          </div>
          <div class=\"col4\">
            <b>Poblacion: </b> {$poblacion}<small>%</small>
          </div>
        </div>
        
        <hr>
        <h4 class=\"center\">Electores</h4>
        <table class=\"table\">
          <thead>
              <tr>
                <th>#</th>
                <th>Sector</th>
                <th>Elector</th>
                <th>Cedula</th>
                <th>Correo</th>
                <th>Telefono</th>
              </tr>
            </thead>
          <tbody>
            {$tbody}
          </tbody>
        </table>
      ";

    }else{
      $nombre = "ubicacion";
      $body = "<center> <h1>No hay informacion sobre esta ubicacion.</h1></cener>";
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
  }//sh_solo

  public function sectores()
  {
    $sectortop = $this->sectores->sector_electores();

    if(count($sectortop)>0){
      $nombre = "Sectores";
      $tbody = ""; $i = 1;
      
      foreach ($sectortop->data as $d) {
        $avg = ($d->total*100)/$sectortop->total;
        $avg = round($avg,2);
        $tbody .="
        <tr>
          <td class=\"center\">{$i}</td>
          <td class=\"center\">{$d->id_sector}</td>
          <td>{$d->sect_nombre}</td>
          <td class=\"center\">{$d->total}</td>
          <td class=\"center\">{$avg} <small>&</small></td>
        </tr>
        ";
        $i++;
      }

      $body = "
      <hr>
      <h3 class=\"center\">Listado de sectores</h3>
      <table class=\"table\">
        <thead>
            <tr>
              <th>#</th>
              <th>COD.</th>
              <th>Sector</th>
              <th>Electores</th>
              <th>%</th>
            </tr>
          </thead>
        <tbody>
          {$tbody}
        </tbody>
      </table>
      ";

    }else{
      $nombre = "sectores";
      $body = "<center> <h1>No hay sectores registrados.</h1></cener>";
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
  }//sectores

  //Sh
  public function sh()
  {
    $sh = $this->sectores->sh_consulta();

    if(count($sh)>0){
      $nombre = "Ubicaciones";
      $tbody = ""; $i = 1;
      
      foreach ($sh->data as $d) {
        $avg = ($d->total*100)/$sh->total;
        $avg = round($avg,2);
        $tbody .="
        <tr>
          <td class=\"center\">{$i}</td>
          <td class=\"center\">{$d->id_sh}</td>
          <td>{$d->sect_nombre}</td>
          <td>{$d->sh_nombre}</td>
          <td class=\"center\">{$d->total}</td>
          <td class=\"center\">{$avg} <small>%</small></td>
        </tr>
        ";
        $i++;
      }

      $body = "
      <hr>
      <h3 class=\"center\">Listado de Ubicaciones</h3>
      <table class=\"table\">
        <thead>
            <tr>
              <th>#</th>
              <th>COD.</th>
              <th>Sector</th>
              <th>Ubicación</th>
              <th>Electores</th>
              <th>%</th>
            </tr>
          </thead>
        <tbody>
          {$tbody}
        </tbody>
      </table>
      ";

    }else{
      $nombre = "sectores";
      $body = "<center> <h1>No hay ubicaciones registradas.</h1></cener>";
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
  }//sh

}//Pdf_sectores

$pdf = new Pdf_sectores();

if(isset($_GET['action'])):
  switch ($_GET['action']):
    case 'sector':
      $id = $_GET['id'];
      
      $pdf->sector_solo($id);
    break;
    case 'sh':
      $id = $_GET['id'];

      $pdf->sh_solo($id);
    break;
    case 'sectores_pdf':
      $pdf->sectores();
    break;
    case 'sh_pdf':
      $pdf->sh();
    break;
    default:
      return false;
    break;
  endswitch;
endif;
?>