<?php
ob_start();
require_once '../config/config.php';

class Pdf_sectores{
  private $pdf;
  private $fecha;
  private $centros;

  public function __CONSTRUCT()
  {
    $this->pdf   = new Pdf();
    $this->fecha = Base::Fecha("d-m-Y");
    $this->centros = new Centros();
  }


  public function centros($id)
  {
    $centro = $this->centros->obtener($id);
    $elec = $this->centros->electoresByCentro($id);
    $total = $this->centros->totalElectores($id);
    $totalc = $this->centros->totalEl();

if (count($elec) > 0) {


      $nombre = "Centros".$centro->cent_nombre;
      $tbody = ""; $i = 1;
      
foreach($elec as $e){
       
        $tbody .="
        <tr>
          <td class=\"center\">{$i}</td>
          <td class=\"center\">{$e->elec_cedula}</td>
          <td>{$e->elec_nombres}</td>
          <td>{$e->elec_apellidos}</td>
          <td class=\"center\">{$e->elec_telefono}</td>
          <td class=\"center\">{$e->elec_email}</td>
          <td class=\"center\">{$e->elec_profesion}</td>
        </tr>
        ";
        $i++;
      }
 $porcentaje_electores_centros = round($total->total*100/$totalc,2);

      $body = "<h3 class=\"center\">{$centro->cent_nombre}</h3>
      <hr>
      <h2 class=\"center\">Este centro tiene el {$porcentaje_electores_centros}% de electores</h2>
      <hr>
      <h3 class=\"center\">Listado de electores</h3>
      <table class=\"table\">
        <thead style='background-color: skyblue'>
            <tr>
              <th>#</th>
              <th>Cedula</th>
              <th>Nombres</th>
              <th>Apellidos</th>
              <th>Telefono</th>
              <th>Correo</th>
              <th>Profesion</th>
            </tr>
          </thead>
        <tbody>
          {$tbody}
        </tbody>
      </table>
      ";

    }else{
      $nombre = "Centro".$centro->cent_nombre;
      $body="<h2 class=\"center\">No tiene electores registrados</h2>";
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
    case 'centro':
      $id = $_GET['id'];
      $pdf->centros($id);
    break;
    default:
      return false;
    break;
  endswitch;
endif;
?>