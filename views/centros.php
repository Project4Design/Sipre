<?
$centros   = new Centros();
$cen = $centros->obtener($id);
if($opc=="add_centro"){$li="Agregar";}elseif($opc=="edit"){$li="Editar";}elseif($opc=="ver"){$li="Detalles";}else{$li="";}

//sacar porcentaje de este centro
$total = $centros->totalElectores($id);
$totalc = $centros->totalEl();



?>
  <style>
    .google-maps {
    position: relative;
    padding-bottom: 75%; // This is the aspect ratio
    height: 0;
    overflow: hidden;
    }
    .google-maps iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100% !important;
    height: 100% !important;
  }
  </style>
<section class="content-header">
  <h1> Centros </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Inicio</a></li>
    <li><a href="inicio.php?ver=centros"> Centros</a></li>
    <?if($li!=""){echo "<li>".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
  case 'ver':
    $ubicacion= $cen->cent_nombre;

    if($ubicacion=="E.B. MANUELITA SAENZ"){$ubicacion="Manuelita Saenz I";}
    if($ubicacion=="E.B. LUCIO ANTONIO FIGUERA"){$ubicacion="Cagua aragua";}
    if($ubicacion=="E.B. ALIDA PEREZ MATOS"){$ubicacion="Cagua aragua";}
    if($ubicacion=="U.E.N. SOTERO ARTEAGA MIGUELENA"){$ubicacion="Cagua aragua";}
    if($ubicacion=="U.E. AMPARO MONROY POWER"){$ubicacion="Cagua aragua";}
    if($ubicacion=="U.E.N. MANUEL MANZO GORESTEGUI"){$ubicacion="Cagua aragua";}
    if($ubicacion=="E.B. CIRO MALDONADO ZERPA"){$ubicacion="Cagua aragua";}
    if($ubicacion=="U.E.N. CREACION BELLA VISTA"){$ubicacion="Cagua aragua";}
    if($ubicacion== "P.N.B. BELLA VISTA II"){$ubicacion="Cagua aragua";}
    if($ubicacion== "NUESTRA SEÑORA DEL VALLE"){$ubicacion="Cagua aragua";}
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=centros"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-flat btn-success" href="?ver=centros&opc=edit&id=<?=$id?>"><i class="fa fa-pencil" aria-hidden="true"></i>Modificar Centro</a>
       <a class="btn btn-flat btn-danger" href=reportes/centros.php?&action=centro&id=<?=$id?>"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</a>
      <?php if($_SESSION['nivel'] == 'A'){?>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-trash-o" aria-hidden="true"></i> Eliminar Centro</button>
      <?php } ?>
    </section>
    

    <div class="row">
      <div class="col-md-5">
        <section class="perfil">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-home" aria-hidden="true"></i><?=$cen->cent_nombre?> - UBICACION
          </h2>
          <div class="google-maps">
            <iframe  width="550" height="450" frameborder="2" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=<?=$ubicacion?>%20cagua&key=AIzaSyAJVQGZcaOqIc32LdrQwXCbY6HxFal70YI" allowfullscreen></iframe>
          </div>
        </section>
      </div>
      
      <div class="col-md-7">
                <h4>El porcentaje de electores en este centro es de:       <spam  style="font-size: 200%"><strong class="text-danger"> <?=round($total->total*100/$totalc,2)?>%</strong></spam></h4>
        <div class="box box-warning color-palette-box">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-address-book-o"></i> Electores registrados</h3>
          </div>

          <div class="box-body">
            <table class="table data-table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Nombre</th>
                  <th class="text-center">Apellido</th>
                  <th class="text-center">Cedula</th>
                  <th class="text-center">Email</th>
                  <th class="text-center">Accion</th>
                </tr>
              </thead>
              <tbody>
              <? $i = 1;
              $elec = $centros->electoresByCentro($id);

                foreach ($elec as $d) {
              ?>
                <tr>
                  <td class="text-center"><?=$i?></td>
                  <td><?=$d->elec_nombres?></td>
                  <td><?=$d->elec_apellidos?></td>
                  <td><?=$d->elec_cedula?></td>
                  <td><?=$d->elec_email?></td>
                  <td class="text-center">
                    <a class="btn btn-flat btn-primary btn-sm" href="?ver=electores&opc=ver&id=<?=$d->id_elector?>"><i class="fa fa-search"></i></a>
                    <a class="btn btn-flat btn-success btn-sm" href="?ver=electores&opc=edit&id=<?=$d->id_elector?>"><i class="fa fa-pencil"></i></a>
                    <a class="btn btn-flat btn-danger btn-sm" href="reportes/electores.php?action=elector&id=<?=$d->id_elector?>"><i class="fa fa-print"></i></a>
                  </td>
                </tr>
              <?
                $i++;
                }
              ?>    
              </tbody>
            </table>
          </div><!--Box-body-->
        </div><!--box box-warning-->
      </div><!--Col-md-6-->
    </div><!--Row-->


    <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="delModalLabel"></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="delete-user" class="col-md-8 col-md-offset-2" action="funciones/class.centros.php" method="post">
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="action" value="eliminar">
                <div class="form-group">
                  <h4 class="text-center">¿Esta seguro que des&eacute;a <b>Eliminar</b> este centro?</h4><br>
                    <li><?=$cen->cent_nombre?></li>
                  <div class="progress" style="display:none">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    </div>
                  </div>
                  <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <center>
                  <button id="b-eliminar" class="btn btn-flat btn-danger b-submit" type="submit" data-loading-text="Cargando..." >Eliminar</button>
                  <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js"></script>
    <script type="text/javascript">
      $(document).ready(function(){      
        $('#activarModal').on('show.bs.modal',function(event){
          var button = $(event.relatedTarget);
          var title = button.data('title');
          var val = button.data('val');
          var modal = $(this);

          modal.find('#estado-val').val(val);
          modal.find('#modal-msj').text(title);
          modal.find('.modal-title').text(title+" usuario");
          modal.find('.modal-body #estado').val(title);
        });
      });
    </script>


  <?
  break;
  case 'add':
  case 'edit':
    if($cen==NULL){ $id = 0; $action = "add_centro"; }else{  $action="edit"; }
  ?>
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa <?=($id>0)? 'fa-pencil':'fa-plus'?>"></i> <?=($id>0)?'Modificar':'Agregar'?> Centro</h3><br>
          </div>
          <div class="box-body">
            <form class="form-horizontal" action="funciones/class.centros.php" id="fr-registro" method="post">
              <input id="action" type="hidden" name="action" value="<?=$action?>">
              <input id="id" type="hidden" name="id" value="<?=($id>0)?$id:'0';?>"> 
              
              <div class="form-group">
                <label for="cent_nombre" class="col-md-4 control-label">Nombre: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="cent_nombre" name="cent_nombre" placeholder="Nombres" value="<?=($id>0)?$cen->cent_nombre:'';?>" maxlength="250" required>
                </div>
              </div>
              
              <div class="col-md-5 col-md-offset-4">
                <p class="help-block" style="color:red">* Campos requeridos</p>

                <div class="progress" style="display:none">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    <span class="sr-only">100% Complete</span>
                  </div>
                </div>

                <div class="alert" style="display:none" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
                </div>
              </div>
              
              <div class="col-md-4 col-md-offset-4">
                <a href="?ver=centros" class="btn btn-flat btn-default"><i class="fa fa-reply"></i> Volver</a>
                <input id="b-registro" class="btn btn-flat btn-primary b-submit" type="submit" name="registrar" value="Guardar">
              </div>
            </form>    
          </div>
        </div>
      </div>
    </div>
  <?
  break;
  default:
    $centro = $centros->consulta();
  ?>
    <div class="box box-warning color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-users"></i> Centros de votacion</h3>
        <div class="pull-right">
          <a class="btn btn-flat btn-sm btn-success" href="?ver=centros&opc=add"><i class="fa fa-user-plus" aria-hidden="true"></i> Agregar centro</a>
           <a class="btn btn-flat btn-sm btn-danger" href="reportes/centros.php?action=centros"><i class="fa fa-print" aria-hidden="true"></i>  Imprimir centros</a>
        </div>
      </div>
      <div class="box-body">
        <table class="table data-table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Nombre</th>
              <th class="text-center">Cantidad de electores</th>
              <th class="text-center">Accion</th>
            </tr>
          </thead>
          <tbody>
          <? $i = 1;
            foreach ($centro as $c) {

              if ($c->total == 0) {
                $total= "No tiene electores";
              }else{
                $total = $c->total." Electores";
              }
              
          ?>
            <tr>
              <td class="text-center"><?=$i?></td>
              <td class="text-center"><?=$c->cent_nombre?></td>
           
              <td class="text-center"><strong><?=$total?></strong></td>
             
              <td class="text-center">
                <a class="btn btn-flat btn-primary btn-sm" href="?ver=centros&opc=ver&id=<?=$c->id_centro?>"><i class="fa fa-search"></i></a>
                <a class="btn btn-flat btn-success btn-sm" href="?ver=centros&opc=edit&id=<?=$c->id_centro?>"><i class="fa fa-pencil"></i></a>
              </td>
            </tr>
          <?
            $i++;}
          ?>        
          </tbody>
        </table>
       </div>
    </div>
  <?
  break;
endswitch;
?>
</div>


