<?
$sectores   = new Sectores();
if($opc=="sector"){$li="Sector";}elseif($opc=="ubc"){$li="Ubicacion";}else{$li="";}
?>

<section class="content-header">
  <h1> Sectores </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home" aria-hidden="true"></i> Inicio</a></li>
    <li><a href="inicio.php?ver=sectores"> Sectores</a></li>
    <?if($li!=""){echo "<li>".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
  case 'sector':
    $sector      = $sectores->sector_obtener($id);
    $ubicaciones = $sectores->shBySector($id);
    $electores   = new Electores();
    $total       = $electores->consulta();
    $elector     = $electores->electoresBySector($id);
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=sectores"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-flat btn-danger" href="reportes/sectores.php?action=sector&id=<?=$id?>"><i class="fa fa-print" aria-hidden="true"></i> Imprimir sector</a>
      <?if($_SESSION['nivel']=="A"){?>
        <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      <?}?>
    </section>
    <section>
      <div class="row">
        <div class="col-md-12">
          <h3 class="text-center" style="margin-top:0">Ubicaciones pertenecientes a <span style="color:#E42126"><?=$sector->sect_nombre?></span></h3><br>
        </div>
        <div class="col-md-12">
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-green">
              <span class="info-box-icon"><i class="fa fa-globe"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Ubicaciones</span>
                <span class="info-box-number"><?=count($ubicaciones)?></span>

                <span class="progress-description">
                  Total en este sector
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-red">
              <span class="info-box-icon"><i class="fa fa-address-book-o" aria-hidden="true"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Electores</span>
                <span class="info-box-number"><?=count($elector)?></span>

                <span class="progress-description">
                  Total en este sector
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>

          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box bg-aqua">
              <span class="info-box-icon"><i class="fa fa-street-view" aria-hidden="true"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Poblacion</span>
                <span class="info-box-number"><?=round((count($elector)*100)/count($total),2)?> <small>%</small></span>

                <span class="progress-description">
                  Total en este sector
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        </div>

        <div class="col-md-12">
        <?
          foreach ($ubicaciones as $d) {
        ?>
          <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
              <span class="info-box-icon bg-yellow"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
              <div class="info-box-content">
                <span class="info-box-text"><?=$d->sh_nombre?></span>
                <span class="info-box-number"><?=$d->total?> <small>Electores</small></span>
                <a href="?ver=sectores&opc=ubc&id=<?=$d->id_sh?>">Ver ubicacion</a>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
        <?
          }
        ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title"> Electores registrados en <?=$sector->sect_nombre?></h3>
            </div>
            <div class="box-body">
              <table class="table table-striped data-table table-bordered table-hover table-condensed">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Elector</th>
                    <th>Cedula</th>
                    <th>Telefono</th>
                    <th>Correo</th>
                    <th>Ubicacion</th>
                    <th>Accion</th>
                  </tr>
                </thead>
                <tbody>
                <?$i=1;
                  foreach ($elector as $d) {
                ?>
                  <tr>
                    <td class="text-center"><?=$i?></td>
                    <td><?=$d->elec_nombres." ".$d->elec_apellidos?></td>
                    <td><?=$d->elec_cedula?></td>
                    <td><?=$d->elec_telefono?></td>
                    <td><?=$d->elec_email?></td>
                    <td><?=$d->sh_nombre?></td>
                    <td class="text-center">
                      <a class="btn btn-flat btn-primary btn-sm" href="?ver=electores&opc=ver&id=<?=$d->id_elector?>"><i class="fa fa-search" aria-hidden="true"></i></a>
                      <a class="btn btn-flat btn-danger btn-sm" href="reportes/electores.php?action=elector&id=<?=$d->id_elector?>"><i class="fa fa-print"></i></a>
                    </td>
                  </tr>
                <?$i++;
                  }
                ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div id="deleteModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fdelete" action="funciones/class.sectores.php">
            <input type="hidden" name="action" value="del_sector">
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Eliminar Sector</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro que desea eliminar a este Sector?</h4>
              <p class="text-center">No se podran eliminar secotres si tienen ubicaciones registradas.</p>
              <p class="text-center"><small>Esta acción no se puede deshacer.</small></p>

              <div class="alert alert-dismissible" role="alert" style="display:none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
              </div>

              <div class="progress progress-sm active" style="display:none">
                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%">
                  <span class="sr-only">100% Complete</span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
              <button id="b-del-item" type="submit" class="btn btn-outline b-submit">Eliminar</button>
            </div>
          </form>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>
  <?
  break;
  case 'ubc':
    $ubicacion = $sectores->sh_obtener($id);
    $electores = new Electores();
    $total     = count($electores->consulta());
    $elector   = $electores->electoresBySh($id);
  ?>
    <div class="row">
      <div class="col-md-12">
        <a class="btn btn-flat btn-default" href="?ver=sectores"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
        <a class="btn btn-flat btn-danger" href="reportes/sectores.php?action=sh&id=<?=$id?>"><i class="fa fa-print" aria-hidden="true"></i> Imprimir ubicacion</a>
        <?if($_SESSION['nivel']=="A"){?>
        <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-times" aria-hidden="true"></i> Eliminar</button>
      <?}?>
      </div>
    </div></br>

    <div class="row">
      <div class="col-md-12">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-red">
            <span class="info-box-icon"><i class="fa fa-address-book-o" aria-hidden="true"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Electores</span>
              <span class="info-box-number"><?=count($elector)?></span>

              <span class="progress-description">
                Total en esta ubicacion
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="fa fa-street-view" aria-hidden="true"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">poblacion</span>
              <span class="info-box-number"><?=round((count($elector)*100)/$total,2)?> <small>%</small></span>

              <span class="progress-description">
                Total en esta ubicacion
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
      </div>

      <div class="col-md-12">
        <div class="box box-warning">
          <div class="box-header">
            <h3 class="box-title"> Electores registrados en <span style="color:#E42126"><?=$ubicacion->sh_nombre?></span></h3>
          </div>
          <div class="box-body">
            <table class="table table-striped data-table table-bordered table-hover table-condensed">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Sector</th>
                  <th>Elector</th>
                  <th>Cedula</th>
                  <th>Correo</th>
                  <th>Telefono</th>
                  <th>Accion</th>
                </tr>
              </thead>
              <tbody>
              <?$i=1;
                foreach ($elector as $d) {
              ?>
                <tr>
                  <td class="text-center"><?=$i?></td>
                  <td><?=$d->sect_nombre?></td>
                  <td><?=$d->elec_nombres." ".$d->elec_apellidos?></td>
                  <td><?=$d->elec_cedula?></td>
                  <td><?=$d->elec_email?></td>
                  <td><?=$d->elec_telefono?></td>
                  <td class="text-center">
                    <a class="btn btn-flat btn-primary btn-sm" href="?ver=electores&opc=ver&id=<?=$d->id_elector?>"><i class="fa fa-search" aria-hidden="true"></i></a>
                    <a class="btn btn-flat btn-danger btn-sm" href="reportes/electores.php?action=elector&id=<?=$d->id_elector?>"><i class="fa fa-print"></i></a>
                  </td>
                </tr>
              <?$i++;
                }
              ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div id="deleteModal" class="modal fade modal-danger" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel">>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="fdelete" action="funciones/class.sectores.php">
            <input type="hidden" name="action" value="del_sh">
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Eliminar Ubicación</h4>
            </div>
            <div class="modal-body">
              <h4 class="text-center">¿Esta seguro que desea eliminar esta Ubicación?</h4>
              <p class="text-center">No se podran eliminar ubicaciones si tienen electores registrados.</p>
              <p class="text-center"><small>Esta acción no se puede deshacer.</small></p>

              <div class="alert alert-dismissible" role="alert" style="display:none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
              </div>

              <div class="progress progress-sm active" style="display:none">
                <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="100" aria-valuemin="100" aria-valuemax="100" style="width:100%">
                  <span class="sr-only">100% Complete</span>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
              <button id="b-del-item" type="submit" class="btn btn-outline b-submit">Eliminar</button>
            </div>
          </form>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div>

  <?
  break;
  default:
    $sector    = $sectores->sector_consulta();
    $sectortop = $sectores->sector_electores();
    $sh        = $sectores->sh_consulta();
  ?>
    <div class="row">
      <div class="col-md-4">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title"> Agregar Sector</h3>
          </div>
          <form id="sector-add" class="form-validate" action="funciones/class.sectores.php">
            <div class="box-body">
              <input type="hidden" name="action" value="sector_add">
              <input type="hidden" name="id" value="0">
              <div class="form-group">
                <label for="sector" class="control-label">Sector: *</label>
                <input id="sector" class="form-control" type="text" name="sector" required>
              </div>

              <div class="form-group">
                <div class="progress" style="display: none">
                  <div class="progress-bar progress-bar-striped" role="progressbar" aria-hidden="100" aria-valuemin></div>
                </div>

                <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
              </div>

            </div>
            <div class="box-footer">
              <input id="b-sector" class="btn btn-flat btn-sm btn-primary b-submit" type="submit" value="Guardar">
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Listado de Sectores</h3>
            <div class="pull-right"><a class="btn btn-sm btn-flat btn-danger" href="reportes/sectores.php?action=sectores_pdf"><i class="fa fa-print" aria-hidden="true"></i> Imprimir Secotres</a></div>
          </div>
          <div class="box-body">
            <table class="table table-striped table-basic table-bordered table-hover table-condensed">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">COD.</th>
                  <th class="text-center">Sector</th>
                  <th class="text-center">Electores</th>
                  <th class="text-center">%</th>
                  <th class="text-center">Accion</th>
                </tr>
              </thead>
              <tbody>
              <? $i = 1;
                foreach ($sectortop->data as $d) {
                  $avg = ($d->total*100)/$sectortop->total;
              ?>
                <tr>
                  <td class="text-center"><?=$i?></td>
                  <td class="text-center"><?=$d->id_sector?></td>
                  <td><?=$d->sect_nombre?></td>
                  <td class="text-center"><?=$d->total?></td>
                  <td class="text-center"><?=round($avg,2)?> <small>%</small></td>
                  <td class="text-center">
                    <a class="btn btn-flat btn-primary btn-sm" href="?ver=sectores&opc=sector&id=<?=$d->id_sector?>"><i class="fa fa-search" aria-hidden="true"></i></a>
                    <?if($_SESSION['nivel']=="A"){?>
                    <button id="<?=$d->id_sector?>" class="btn btn-flat btn-success btn-sm" type="button" onclick="get_data('sector-add',this.id)"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                    <?}?>
                    <a href="reportes/sectores.php?action=sector&id=<?=$d->id_sector?>" class="btn btn-sm btn-flat btn-danger"><i class="fa fa-print" aria-hidden="true"></i></a>
                  </td>
                </tr>
              <?
                $i++;
                }
              ?>        
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"> Agregar Ubicacion </h3>
          </div>
          <form id="ubicacion-add" action="funciones/class.sectores.php">
            <div class="box-body">
              <input type="hidden" name="action" value="sh_add">
              <input type="hidden" name="id" value="0">

              <div class="form-group">
                <label for="sh_sector" class="control-label">Sector:*</label>
                <select id="sh_sector" class="form-control" type="text" name="sector" required>
                  <option value="">Seleccione...</option>
                <?
                  foreach ($sector as $d){
                ?>
                  <option value="<?=$d->id_sector?>"><?=$d->sect_nombre?></option>
                <?
                  }
                ?>
                </select>
              </div>

              <div class="form-group">
                <label for="ubicacion" class="form-label">Ubicación:*</label>
                <input id="ubicacion" class="form-control" type="text" name="ubicacion" required>
              </div>

              <div class="progress" style="display: none">
                <div class="progress-bar progress-bar-striped" role="progressbar" aria-hidden="100" aria-valuemin></div>
              </div>

              <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
            </div>
            <div class="box-footer">
              <input id="b-sector" class="btn btn-flat btn-sm btn-primary b-submit" type="submit" value="Guardar">
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title"> Listado de Ubicaciones</h3>
            <div class="pull-right">
              <a href="reportes/sectores.php?action=sh_pdf" class="btn bn-sm btn-flat btn-danger">
                <i class="fa fa-print" aria-hidden="true"></i> Imprimir Ubicaciones
              </a>
            </div>
          </div>
          <div class="box-body">
            <table class="table table-striped data-table table-bordered table-hover table-condensed">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">COD.</th>
                  <th class="text-center">Sector</th>
                  <th class="text-center">Ubicación</th>
                  <th class="text-center">Electores</th>
                  <th class="text-center">%</th>
                  <th class="text-center">Accion</th>
                </tr>
              </thead>
              <tbody>
              <? $i = 1;
                foreach ($sh->data as $d) {
                  $avg = ($d->total*100)/$sectortop->total;
              ?>
                <tr>
                  <td class="text-center"><?=$i?></td>
                  <td class="text-center"><?=$d->id_sh?></td>
                  <td><?=$d->sect_nombre?></td>
                  <td><?=$d->sh_nombre?></td>
                  <td class="text-center"><?=$d->total?></td>
                  <td class="text-center"><?=round($avg,2)?> <small>%</small></td>
                  <td class="text-center">
                    <a class="btn btn-flat btn-primary btn-sm" href="?ver=sectores&opc=ubc&id=<?=$d->id_sh?>"><i class="fa fa-search" aria-hidden="true"></i></a>
                    <?if($_SESSION['nivel']=="A"){?>
                    <button id="<?=$d->id_sh?>" class="btn btn-flat btn-success btn-sm" type="button" onclick="get_data('ubicacion-add',this.id)"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                    <?}?>
                    <a class="btn btn-flat btn-sm btn-danger" href="reportes/sectores.php?action=sh&id=<?=$d->id_sh?>"><i class="fa fa-print" aria-hidden="true"></i></a>
                  </td>
                </tr>
              <?
                $i++;
                }
              ?>        
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      function get_data(lt,id){
        var action = (lt == "ubicacion-add")? "sh" : "sector";
        $('#'+lt+' input[name="action"]').val(action+'_edit');

        $.ajax({
          type: 'POST',
          cache: false,
          url: 'funciones/class.sectores.php',
          data: {action:action+'_get',id:id},
          dataType: 'json',
          success: function(r){
            if(r.response){
              $('#'+lt+' .alert').removeClass('alert-danger').addClass('alert-success');
              var idx = 0;
              if(lt == "ubicacion-add"){
                $('#ubicacion-add #sh_sector option').each(function(){
                  $(this).prop('selected',false);
                });

                $('#ubicacion-add #sh_sector').val(r.data.id_sector);
                $('#ubicacion-add #ubicacion').val(r.data.sh_nombre);
                idx = r.data.id_sh;
              }else{
                $('#sector-add #sector').val(r.data.sect_nombre);
                idx = r.data.id_sector;
              }
              $('#'+lt+' input[name="id"]').val(idx);
            }else{
              $('#'+lt+' .alert').removeClass('alert-success').addClass('alert-danger');
              $('#'+lt+' .alert').show().delay(5000).hide('slow');
            }
            $('#'+lt+' #msj').text(r.msj);
          },
          error: function(){
            $('#'+lt+' .alert').removeClass('alert-success').addClass('alert-danger');
            $('#'+lt+' #msj').text('Ha ocurrido un error.');
            $('#'+lt+' .alert').show().delay(5000).hide('slow');
          }
        })
      }
    </script>
  <?
  break;
endswitch;
?>
</div>