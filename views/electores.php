<?
$electores   = new Electores();
$elec = $electores->obtener($id);
if($opc=="add"){$li="Agregar";}elseif($opc=="edit"){$li="Editar";}elseif($opc=="ver"){$li="Perfil";}elseif($opc=="carga"){$li="Cargar";}elseif($opc=="consulta"){$li="Consulta";}else{$li="";}
?>

<?=Base::Js("plugins/select2/select2.min.js")?>

<section class="content-header">
  <h1> Electores </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Inicio</a></li>
    <li><a href="inicio.php?ver=electores"> Electores</a></li>
    <?if($li!=""){echo "<li>".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
  case 'ver':
    $date = date_diff(date_create($elec->elec_nacimiento),date_create(Base::Fecha()));
    $edad = $date->format('%y');
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=electores"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-flat btn-danger" href="reportes/electores.php?action=elector&id=<?=$elec->id_elector?>"><i class="fa fa-print" aria-hidden="true"></i> Imprimir ficha</a>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-address-book-o" aria-hidden="true"></i>
            <?=$elec->elec_nombres?> <?=$elec->elec_apellidos?>
            <small class="pull-right">
              Registrado por: <a href="?ver=usuarios&opc=ver&id=<?=$elec->id_user?>"><?=$elec->user_nombres." ".$elec->user_apellidos?></a> | El: <?=Base::ConvertTS($elec->elec_fecha_reg)?>
            </small>
          </h2>
        </div>
        <div class="col-md-4">
          <h4>Datos del Elector</h4>
          <p><b>Cedula:</b> <?=$elec->elec_cedula?></p>
          <p><b>Nombres:</b> <?=$elec->elec_nombres?></p>
          <p><b>Apellidos:</b> <?=$elec->elec_apellidos?></p>
          <p><b>Profesión:</b> <?=$elec->elec_profesion?></p>
          <p><b>Sexo:</b> <?=($elec->elec_sexo=="M")?'Masculino':'Femenino'?></p>
          <p><b>Fecha de nacimiento:</b> <?=Base::Convert($elec->elec_nacimiento)." ({$edad} años)"?></p>
        </div>

        <div class="col-md-4">
          <h4>Contacto</h4>
          <p><b>Correo:</b> <a href="mailto:<?=$elec->elec_email?>" title="Enviar correo"><?=$elec->elec_email?></a></p>
          <p><b>Telefono:</b> <?=$elec->elec_telefono?></p>
          <p><b>Facebook:</b> <?=($elec->elec_facebook)? $elec->elec_facebook:'N/A'?></p>
          <p><b>Twitter:</b>  <?=($elec->elec_twitter)? "<a href=\"http://twitter.com/{$elec->elec_twitter}\">@{$elec->elec_twitter}</a>":"N/A"?></p>
          <p><b>Instagram:</b> <?=($elec->elec_instagram)? "<a href=\"http://instagram.com/{$elec->elec_instagram}\">@{$elec->elec_instagram}</a>":"N/A"?></p>
        </div>

        <div class="col-md-4">
          <h4>Dirección</h4>
          <p><b>Sector:</b> <?=$elec->sect_nombre?></p>
          <p><b>Ubicacion:</b> <?=$elec->sh_nombre?></p>
          <p><b>Direccion:</b> <?=$elec->elec_direccion?></p><br>
          <h4>Centro de votación</h4>
          <p><?=$elec->cent_nombre?></p>
        </div>
      </div>
    </section>

  <?
  break;
  case 'add':
  case 'edit':
    if($elec==NULL){ $id = 0; $action = "add"; $disabled="disabled"; }else{ $disabled=""; $action="edit"; }
    $sectores = new Sectores();
    $sector = $sectores->sector_consulta();
    $centros = new Centros();
    $centro = $centros->consulta();
  ?>
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa <?=($id>0)? 'fa-pencil':'fa-user-plus'?>"></i> <?=($id>0)?'Modificar':'Agregar'?> Elector</h3><br>
          </div>
          <div class="box-body">
            <form class="form-horizontal" action="funciones/class.electores.php" id="fr-registro" method="post">
              <input id="action" type="hidden" name="action" value="<?=$action?>">
              <input id="id" type="hidden" name="id" value="<?=($id>0)?$id:'0';?>">

              <div class="form-group">
                <label for="cedula" class="col-md-4 control-label">Cedula: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control numeros" id="cedula" name="cedula" placeholder="C.I" value="<?=($id>0)?$elec->elec_cedula:'';?>" maxlength="10" required>
                </div>
              </div>

              <div class="form-group">
                <label for="nombres" class="col-md-4 control-label">Nombres: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Nombres" value="<?=($id>0)?$elec->elec_nombres:'';?>" maxlength="50" required>
                </div>
              </div>

              <div class="form-group">
                <label for="apellidos" class="col-md-4 control-label">Apellidos: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos" value="<?=($id>0)?$elec->elec_apellidos:'';?>" maxlength="50" required>
                </div>
              </div>

              <div class="form-group">
                <label for="email" class="col-md-4 control-label">Email: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?=($id>0)?$elec->elec_email:'';?>" maxlength="40" pattern="^[a-zA-Z0-9.+_-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$" required>
                </div>
              </div>

              <div class="form-group">
                <label for="sexo" class="col-md-4 control-label">Sexo: *</label>
                <div class="col-md-3">
                  <select id="sexo" class="form-control" name="sexo" required>
                    <option value="">Seleccione...</option>
                    <option value="M" <?if($id>0){echo ($elec->elec_sexo=="M")?'selected':'';} ?>>Masculino</option>
                    <option value="F" <?if($id>0){echo ($elec->elec_sexo=="F")?'selected':'';} ?>>Femenino</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="telefono" class="col-md-4 control-label">Teléfono: *</label>
                <div class="col-md-5">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-phone"></i>
                    </div>
                    <input id="telefono" class="form-control" type="text" name="telefono" placeholder="Telefono" data-inputmask="'mask': '(9999) 9999 99 99'" data-mask="" value="<?=($id>0)?$elec->elec_telefono:'';?>" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="fecha" class="col-md-4 control-label">Fecha de nacimiento: *</label>
                <div class="col-md-5">
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input id="nacimiento" class="form-control" type="text" name="nacimiento" placeholder="Fecha de nacimiento" data-inputmask="'alias': 'dd-mm-yyyy'" data-mask="" value="<?=($id>0)?Base::Convert($elec->elec_nacimiento):'';?>" required>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="profesion" class="col-md-4 control-label">Profesión: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="profesion" placeholder="Profesión" name="profesion" value="<?=($id>0)?$elec->elec_profesion:'';?>" required>
                </div>
              </div>

              <div class="form-group">
                <label for="sector" class="col-md-4 control-label">Sector: *</label>
                <div class="col-md-5">
                  <select id="sector" class="form-control" type="text" name="sector" required>
                    <option value="">Seleccione...</option>
                    <?
                      foreach ($sector as $d) {
                        $selected = ($elec->id_sector==$d->id_sector) ? "selected":"";
                    ?>
                    <option value="<?=$d->id_sector?>" <?=$selected?>><?=$d->sect_nombre?></option>
                    <?
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="ubicacion" class="col-md-4 control-label">Ubicación: *</label>
                <div class="col-md-5">
                  <select id="ubicacion" class="form-control" type="text" name="ubicacion" <?=$disabled?> required>
                    <option value=""></option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="direccion" class="col-md-4 control-label">Dirección: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="direccion" placeholder="Direccion" name="direccion" value="<?=($id>0)?$elec->elec_direccion:'';?>" required>
                </div>
              </div>

              <div class="form-group">
                <label for="centro" class="col-md-4 control-label">Centro de votación: *</label>
                <div class="col-md-5">
                  <select id="centro" type="text" class="form-control" name="centro" required>
                    <option value="">&nbsp;</option>
                    <?
                      foreach ($centro as $d) {
                        $selected = ($elec->id_centro==$d->id_centro) ? "selected":"";
                    ?>
                    <option value="<?=$d->id_centro?>" <?=$selected?>><?=$d->cent_nombre?></option>
                    <?
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="facebook" class="col-md-4 control-label">Facebook: </label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="facebook" placeholder="Facebook" name="facebook" value="<?=($id>0)?$elec->elec_facebook:'';?>">
                </div>
              </div>

              <div class="form-group">
                <label for="twitter" class="col-md-4 control-label">Twitter: </label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="twitter" placeholder="Twitter" name="twitter" value="<?=($id>0)?$elec->elec_twitter:'';?>">
                </div>
              </div>

              <div class="form-group">
                <label for="instagram" class="col-md-4 control-label">Instagram: </label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="instagram" placeholder="Instagram" name="instagram" value="<?=($id>0)?$elec->elec_instagram:'';?>">
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
                <input id="b-registro" class="btn btn-flat btn-primary b-submit" type="submit" name="registrar" value="Guardar">
                <input class="btn btn-flat btn-default" type="reset" name="registrar" value="Limpiar">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?=Base::Js("plugins/datepicker/bootstrap-datepicker.js")?>

    <script>

      $(document).ready(function() {
        $('#fecha').datepicker({
          autoclose: true
        });
        $(".hora").timepicker({
          showInputs: false
        });
      });
    </script>
  <?
  break;
  case 'cargar':
  ?>
    <div class="row">
      <div class="col-md-6">
        <div class="box box-solid">
          <div class="box-header with-border">
            <i class="fa fa-info-circle"></i>
            <h3 class="box-title">Instrucciones para su uso</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <ol>
              <li>Descargar formato ubicado al lado derecho en <b>"Descargar formato"</b>.</li>
              <li>Llenar la información siguiendo el formato.</li>
              <li>Seleccionar el archivo a cargar.</li>
              <li>Una vez cargado. Pulsar al botón <b>"Cargar"</b>.</li>
              <li>Visualizar la informacion y luego pulsar <b>"Registrar Electores"</b>.</li>
            </ol>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>

      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-file-excel-o"></i> Carga Masiva</h3>
            <div class="pull-right">
              <a href="includes/Formato.xlsx"><i class="fa fa-download" aria-hidden="true"></i> Decargar formato</a>
            </div>
          </div>
          <div class="box-body">
            <form id="f-carga" class="form-inline" action="#" enctype="multipart/formdata">
              <input type="hidden" name="action" value="preview">
              <div class="form-group">
                <label for="file">Cargar archivo: *</label>
                <input id="file" class="form-control" type="file" name="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="border:none!important">
                <p class="help-block">Formato admitido: .xlsx</p>
              </div>
              <div class="form-group">
                <input id="btn-cargar" class="btn btn-flat btn-primary" type="submit" value="Cargar">
              </div>
            </form>

          </div>
        </div>

        <div class="alert" role="alert" style="display:none">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span>
        </div>

        <div class="box box-info">
          <div class="box-header">
            <h3 class="box-title">Informacion</h3>
            <div class="pull-right">
              <button id="b-reg" class="btn btn-flat btn-warning" type="button" onclick="registrar()" role="button" disabled>Registrar Electores</button>
            </div>
          </div>
          <div class="box-body">
            <table class="table table-striped table-bordered table-condensed">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombres</th>
                  <th>Apellidos</th>
                  <th>Cedula</th>
                  <th>Email</th>
                  <th>Sexo</th>
                  <th>Telefono</th>
                  <th>Fecha</th>
                  <th>Profesion</th>
                  <th>Sector</th>
                  <th>Ubicacion</th>
                  <th>Direccion</th>
                  <th>Centro</th>
                  <th>Facebook</th>
                  <th>Twitter</th>
                  <th>Instagram</th>
                </tr>
              </thead>
              <tbody id="tbody">
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="overlay" style="display:none">
            <i class="fa fa-refresh fa-spin"></i>
          </div>
        </div>

      </div><!--col-md-12-->
    </div><!--Row-->

    <script type="text/javascript">
      $(document).ready(function(){
        $('#btn-cargar').click(function(e){
          e.preventDefault();
          $('.overlay').show();
          $('.alert').hide();
          var form = $('#f-carga');
          var form = new FormData(form[0]);
          $.ajax({
            type: 'POST',
            url: 'funciones/class.electores.php',
            cache: false,
            data: form,
            processData: false,
            contentType: false,
            dataType: 'json',
            success:function(r){
              if(r.response){
                if(!$('body').hasClass('sidebar-collapse')){
                  $('body .sidebar-toggle').click();
                }
                $('#tbody').html('');
                $('#tbody').html(r.data);
                $('#b-reg').prop('disabled',false);
              }else{
                $('#b-reg').prop('disabled',true);
                $('.alert').show();
              }
              $('.alert #msj').html(r.msj);
            },
            error: function(){

            },
            complete: function(){
              $('.overlay').hide();
            }
          })
        });
      })

      function registrar(){
        $('#b-reg').prop('disabled',true);
        $('.alert').hide();
        $.ajax({
          type: 'POST',
          url: 'funciones/class.electores.php',
          cache: false,
          data: {action:'cargar'},
          dataType: 'json',
          success: function(r){
            if(r.response==true){
              $('.alert').removeClass('alert-danger alert-danger').addClass('alert-success');
            }else if(r.response == 2){
              $('.alert').removeClass('alert-success alert-danger').addClass('alert-warning');
            }else{
              $('.alert').removeClass('alert-warning alert-danger').addClass('alert-danger');
            }
            $('.alert #msj').html(r.msj);
            $('#tbody').html('');
            $('#tbody').html(r.data);
          },
          error: function(){
            $('.alert').removeClass('alert-success alert-warning').addClass('alert-danger');
            $('.alert #msj').text('Ha ocurrido un error inesperado.');
          },
          complete: function(){
            if(!$('body').hasClass('sidebar-collapse')){
              $('body .sidebar-toggle').click();
            }
            $('.alert').show();
            $('#b-reg').prop('disabled',false);
          }
        })
      }
    </script>
  <?
  break;
  case 'consulta':
    $usuarios  = new Usuarios();
    $users     = $usuarios->consulta();
    $centros   = new Centros();
    $centro    = $centros->consulta();
    $sectores  = new Sectores();
    $sector    = $sectores->sector_consulta();
    $sh        = $sectores->sh_consulta();
    $profesion = $electores->profesiones();
  ?>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-info color-palette-box">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-info-circle"></i> Panel de consultas</h3>
          </div>
          <div class="box-body">

            <form id="rep-donaciones" class="" action="#" method="POST">
              <input type="hidden" name="action" value="donaciones">

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Ingrese rango de fechas:</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input id="fechas" class="form-control" type="text" name="fechas">
                    </div>
                    <p class="help-block">Fechas en que se registron los electores.</p>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="registrado">Registrado por: </label>
                    <select id="registrado" class="form-control" name="registrado">
                      <option value="0">Todos</option>
                      <?
                        foreach ($users as $d){
                      ?>
                        <option value="<?=$d->id_user?>"><?=$d->user_nombres." ".$d->user_apellidos?></option>
                      <?
                        }
                      ?>
                    </select>
                    <p class="help-block">Persona que registro a los electores.</p>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="centro">Centro de votación: </label>
                    <select id="centro" class="form-control" name="centro">
                      <option value="0">Todos</option>
                      <?
                        foreach ($centro as $d){
                      ?>
                        <option value="<?=$d->id_centro?>"><?=$d->cent_nombre?></option>
                      <?
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="sector">Sector: </label>
                    <select id="sector" class="form-control" name="sector">
                      <option value="0">Todos</option>
                      <?
                        foreach ($sector as $d){
                      ?>
                        <option value="<?=$d->id_sector?>"><?=$d->sect_nombre?></option>
                      <?
                        }
                      ?>
                    </select>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="form-group">
                    <label for="ubicacion">Ubicación:</label>
                    <select id="ubicacion" class="form-control" name="ubicacion">
                      <option value="0">Todos</option>
                      <?
                        foreach ($sh as $d){
                      ?>
                        <option value="<?=$d->id_sh?>"><?=$d->sh_nombre?></option>
                      <?
                        }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="profesion">Profesión:</label>
                    <select id="profesion" class="form-control" multiple="multiple" name="profesion">
                      <?
                        foreach ($profesion as $d){
                      ?>
                        <option value="<?=$d->profesion?>"><?=$d->profesion?></option>
                      <?
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>

              <center>
                <input id="b-buscar" class="btn btn-flat btn-primary" type="submit" value="Buscar">
                <button id="rep-reset" class="btn btn-flat btn-default" type="button">Limpiar</button>
              </center>
            </form>
          <!--==================================================================================-->
           </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-question" aria-hidden="true"></i> Resultados</h3>
          </div>
          <div class="box-body">

          </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">
      $("#profesion").select2({
        placeholder: 'Profesión'
      });
      $('#fechas').daterangepicker();
    </script>
  <?
  break;
  default:
    $elec = $electores->consulta();

    //Total de electores masculinos
    $m = $electores->total_sexo("M");

    //Total de electores femenino
    $f = $electores->total_sexo("F");
  ?>
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-aqua"><i class="fa fa-address-book-o"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total</span>
            <span class="info-box-number"><?=$m+$f?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-green"><i class="fa fa-mars"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Masculinos</span>
            <span class="info-box-number"><?=$m?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
          <span class="info-box-icon bg-yellow"><i class="fa fa-venus"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Femeninos</span>
            <span class="info-box-number"><?=$f?></span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <!-- /.col -->
    </div>

    <div class="box box-warning color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-address-book-o"></i> Electores registrados</h3>
        <div class="pull-right">
          <a class="btn btn-flat btn-sm btn-success" href="?ver=electores&opc=add"><i class="fa fa-user-plus" aria-hidden="true"></i> Agregar Elector</a>
          <a class="btn btn-flat btn-sm btn-warning" href="?ver=electores&opc=carga"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Carga Masiva</a>
          <a class="btn btn-flat btn-sm btn-danger" href="reportes/electores.php?action=electores_pdf"><i class="fa fa-print" aria-hidden="true"></i> Imprimir listado</a>
        </div>
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

       </div>
    </div>
  <?
  break;
endswitch;

if($opc=="add"||$opc=="edit"||$opc=="consulta"){
?>
  <script type="text/javascript">
    $(document).ready(function(){
      $("#centro").select2({
        placeholder:'Seleccione...'
      });
      $("#ubicacion").select2();

      //Cargar los Sectores hijos al seleccionar un Sector
      $('#sector').on('change',function(){
        var sector = $('#sector').val();
        $.ajax({
          type: 'post',
          cache: false,
          url: 'funciones/class.sectores.php',
          data: {action:'obtener_sh',sector:sector,elect:'<?=$id?>'},
          dataType: 'json',
          success: function(r){
            if(r.response){
              $('#ubicacion').html('');
              $('#ubicacion').append(r.data);
              loadSelect();
            }else{
              $('#ubicacion').html('');
              $("#ubicacion").prop("disabled", true);
            }
          },
          error: function(){
            $('#ubicacion').html('');
            $("#ubicacion").prop("disabled", true);
          },
          complete: function(){

          }
        });
      });

      $('#sector').change();
    });

    function loadSelect(){
      $('#ubicacion').trigger('change.select2');
      $("#ubicacion").prop("disabled", false);
    }
  </script>
<?}?>
</div>
