<?
$usuarios   = new Usuarios();
$user = $usuarios->obtener($id);
if($opc=="add"){$li="Agregar";}elseif($opc=="edit"){$li="Editar";}elseif($opc=="ver"){$li="Perfil";}else{$li="";}
?>

<section class="content-header">
  <h1> Usuarios </h1>
  <ol class="breadcrumb">
    <li><a href="inicio.php"><i class="fa fa-home"></i> Inicio</a></li>
    <li><a href="inicio.php?ver=usuarios"> Usuarios</a></li>
    <?if($li!=""){echo "<li>".$li."</li>";}?>
  </ol>
</section>

<div class="content">
<?
switch($opc):
  case 'ver':
    $electores = new Electores();
    $elector   = $electores->electoresByUser($id);
    if($user->user_estado == "A"){
      $color = "green";
      $estado = "Activo";
    }else{
      $color  = "red";
      $estado = "Inactivo";
    }
  ?>
    <section>
      <a class="btn btn-flat btn-default" href="?ver=usuarios"><i class="fa fa-reply" aria-hidden="true"></i> Volver</a>
      <a class="btn btn-flat btn-success" href="?ver=usuarios&opc=edit&id=<?=$id?>"><i class="fa fa-pencil" aria-hidden="true"></i> Modificar</a>
      <?if($_SESSION['id']!=$id){?>
        <span id="botones">
        <?if($user->user_estado == "A"){?>
          <button id="btn-activar" class="btn btn-flat btn-danger" data-toggle="modal" data-target="#activarModal" data-title="Desactivar" data-val="I"><i class="fa fa-close" aria-hidden="true"></i>&nbsp;Desactivar</button>
        <?}else{?>
          <button id="btn-activar" class="btn btn-flat btn-success" data-toggle="modal" data-target="#activarModal" data-title="Activar" data-val="A"><i class="fa fa-check" aria-hidden="true"></i>&nbsp;Activar</button>
        <?}?>
        </span>
      <?}?>
      <button class="btn btn-flat btn-warning" data-toggle="modal" data-target="#passModal"><i class="fa fa-key" aria-hidden="true"></i> Reesteblecer contraseña</button>
      <?if($_SESSION['id']!=$id){?>
      <button class="btn btn-flat btn-danger" data-toggle="modal" data-target="#delModal"><i class="fa fa-user-times" aria-hidden="true"></i> Eliminar</button>
      <?}?>
    </section>
    <section class="perfil">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-header" style="margin-top:0!important">
            <i class="fa fa-user" aria-hidden="true"></i> 
            <?=$user->user_nombres?> <?=$user->user_apellidos?>
            <small class="pull-right">Registrado: <?=Base::Convert($user->user_fecha_reg)?></small>
          </h2>
        </div>
        <div class="col-md-4">
          <h4>Datos del usuario</h4>
          <p><b>Nombres:</b> <?=$user->user_nombres?></p>
          <p><b>Apellidos:</b> <?=$user->user_apellidos?></p>
          <p><b>Cedula:</b> <?=$user->user_cedula?></p>
          <p><b>Correo:</b> <?=$user->user_email?></p>
          <p><b>Sexo:</b> <?=($user->user_sexo=="M")?'Masculino':'Femenino'?></p>
          <p><b>Telefono:</b> <?=$user->user_telefono?></p>
        </div>

        <div class="col-md-4">
          <h4>&nbsp;</h4>
          <p><b>Nivel:</b> <?=($user->user_nivel=="A")?'Administrador':'Colaborador'?></p>
          <p><b>Estado:</b> <span id="estado" style="color:<?=$color?>"><?=$estado?></span></p>
        </div>
      </div>
    </section>

    <div class="row" style="padding:0 20px">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-red">
          <span class="info-box-icon"><i class="fa fa-address-book-o"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Electores</span>
            <span class="info-box-number"><?=count($elector)?></span>

            <span class="progress-description">
              Registrados
            </span>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>

      <div class="col-md-12">
        <div class="box box-info">
          <div class="box-header with-border">
            <i class="fa fa-address-book"></i> Electores registrados por este usuario
          </div>
          <div class="box-body">
            <table class="table table-striped data-table table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Cedula</th>
                  <th>Telefono</th>
                  <th>Correo</th>
                  <th>Accion</th>
                </tr>
              </thead>
              <tbody>
              <?  $i=1;
                foreach($elector as $d){
              ?>
                <tr>
                  <td><?=$i?></td>
                  <td><?=$d->elec_nombres." ".$d->elec_apellidos?></td>
                  <td><?=$d->elec_cedula?></td>
                  <td><?=$d->elec_telefono?></td>
                  <td><?=$d->elec_email?></td>
                  <td class="text-center">
                    <a class="btn btn-flat btn-sm btn-primary" href="?ver=electores&opc=ver&id=<?=$d->id_elector?>"><i class="fa fa-search"></i></a>
                  </td>
                </tr>
              <? $i++;
                }
              ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div id="activarModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="activarModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="activarModalLabel"></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="activar-user" class="col-md-8 col-md-offset-2" action="funciones/class.usuarios.php" method="post">
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="action" value="activar">
                <input id="estado-val" type="hidden" name="estado" value="">
                <div class="form-group">
                  <h4 class="text-center">Confirme que des&eacute;a <b><span id="modal-msj"></span></b> este usuario.</h4><br>
                  <div class="progress" style="display:none">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
                    </div>
                  </div>
                  <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>
                </div>
                <center>
                  <button id="b-habilitar" class="btn btn-flat btn-primary" type="submit" data-loading-text="Cargando..." >Aceptar</button>
                  <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="passModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="passModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="passModalLabel">Reestablecer contraseña</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="pass-user" class="col-md-8 col-md-offset-2" action="funciones/class.usuarios.php" method="post">
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="action" value="recuperar">
                <p class="text-center">Reestablecer contraseña para este usuario.</p>

                <div class="form-group">
                  <label for="filtro">Establecer manualmente?: *</label>
                  <label class="radio-inline">
                    <input id="switch" class="switch" type="checkbox" name="filtro">
                  </label>
                </div>

                <fieldset id="aleatorio">
                  <p>Contaseña: <b><span id="password" style="padding:10px;background-color:#D6CACA;color:#D83636;"></span></b></p>
                  <p class="help-block">Tome nota de la contraseña una vez cambiada.</p>
                </fieldset>

                <fieldset id="manual" disabled style="display:none">
                  <div class="form-group">
                    <label for="p1" class="control-label">Contraseña nueva: *</label>
                    <input id="p1" class="form-control" type="password" name="p1" required>
                  </div>
                  <div class="form-group">
                    <label for="p2" class="control-label">Repetir contraseña: *</label>
                    <input id="p2" class="form-control" type="password" name="p2" required>
                  </div>
                </fieldset>

                <div class="progress" style="display:none">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%"></div>
                </div>

                <div class="alert" style="display:none" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;<span id="msj"></span></div>

                <center>
                  <button id="b-recuperar" class="btn btn-flat btn-primary" type="submit" data-loading-text="Cargando..." >Aceptar</button>
                  <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Cerrar</button>
                </center>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="delModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="delModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="delModalLabel"></h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <form id="delete-user" class="col-md-8 col-md-offset-2" action="funciones/class.usuarios.php" method="post">
                <input type="hidden" name="id" value="<?=$id?>">
                <input type="hidden" name="action" value="eliminar">
                <div class="form-group">
                  <h4 class="text-center">¿Esta seguro que des&eacute;a <b>Eliminar</b> este usuario?</h4><br>
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

    <script type="text/javascript">
      $(document).ready(function(){
        $(".switch").bootstrapSwitch({
          state: false,
          size: 'small',
          onText: 'Sí',
          offText: 'No',
          onSwitchChange: function(event,state){
            activate(event,state);
          }
        });
      });

      function activate(e,s){
        if(s){
          $('#manual').show();
          $('#manual').prop('disabled',false);
          $('#aleatorio').hide('fast');
        }else{
          $('#manual').hide('fast');
          $('#manual').prop('disabled',true);
          $('#aleatorio').show();
        }
      }
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

      $('#b-habilitar').click(function(e){
        e.preventDefault();
        $('#b-habilitar').button('loading');
        $('#activar-user .progress').show();
        $('#activar-user .alert').hide('fast');

        var form = $('#activar-user');
        var action = form.attr('action');

        $.ajax({
          type: 'POST',
          cache: false,
          url: action,
          data: form.serialize(),
          dataType: 'json',
          success: function(r){
            if(r.response){
              $('#activar-user .alert').removeClass('alert-danger').addClass('alert-success');
            }else{
              $('#activar-user .alert').removeClass('alert-success').addClass('alert-danger');
            }
            if(r.data != null){
              if(parseInt(r.data.e)){
                $('#estado').html('Activo').css('color','green');
              }else{
                $('#estado').html('Inactivo').css('color','red');
              }
              $('#botones').html('');
              $('#botones').html(r.data.b);
            }

            $('#activar-user .alert #msj').html(r.msj);
          },
          error: function(){
            $('#activar-user .alert').removeClass('alert-success').addClass('alert-danger');
            $('#activar-user .alert #msj').html('Ha ocurrido un error inesperado');
          },
          complete: function(){
            $('#b-habilitar').button('reset');
            $('#activar-user .progress').hide('fast');
            $('#activar-user .alert').show().delay(7000).hide('slow');
          }
        })
      });

      $('#b-recuperar').click(function(e){
        e.preventDefault();
        $('#pass-user .alert').hide('fast');
        $('#b-recuperar').button('loading');
        $('#pass-user .progress').show();

        var form = $('#pass-user');
        var action = form.attr('action');
        var pasa = false;
        if($('input[name=filtro]').is(':checked')){ 
          var switche = true;
        }else{ 
          var switche = false;
        }       
        
        var fields = $('#pass-user input:not(:disabled)').filter('[required]').length;
        $('#pass-user input:not(:disabled)').filter('[required]').each(function(){
          var val = $(this).val();
          if(val == ""){
            $(this).closest('.form-group').addClass('has-error');
          }
          else{
            $(this).closest('.form-group').removeClass('has-error');
            fields = fields-1;
          }
        });

        if(fields!=0){
          $('#pass-user .alert').removeClass('alert-success').addClass('alert-danger');
          $('#pass-user .alert #msj').html('Debe completar todos los campos requeridos');
          $('#pass-user .progress').hide();
          $('#b-recuperar').button('reset');
          $('#pass-user .alert').show().delay(7000).hide('slow');
        }else{
          if(switche){
            var p1 = $('#p1').val();
            var p2 = $('#p2').val();
            if(p1===p2){
              pasa = true;
            }else{
              $('#pass-user .alert').removeClass('alert-success').addClass('alert-danger');
              $('#pass-user .alert #msj').html('Las contraseñas no coinciden');
              $('#pass-user .progress').hide();
              $('#b-recuperar').button('reset');
              $('#pass-user .alert').show().delay(7000).hide('slow');
            }
          }else{
            pasa = true;
          }

          if(pasa){
            $.ajax({
              type: 'POST',
              cache: false,
              url: action,
              data: form.serialize(),
              dataType: 'json',
              success: function(r){
                if(r.response){
                  $('#pass-user .alert').removeClass('alert-danger').addClass('alert-success');
                  $('#pass-user')[0].reset();
                }else{
                  $('#pass-user .alert').removeClass('alert-success').addClass('alert-danger');
                }
                $('#password').html(r.data);
                $('#pass-user .alert #msj').html(r.msj);
              },
              error: function(){
                $('#pass-user .alert').removeClass('alert-success').addClass('alert-danger');
                $('#pass-user .alert #msj').html('Ha ocurrido un error inesperado');
              },
              complete: function(){
                $('#b-recuperar').button('reset');
                $('#pass-user .progress').hide('fast');
                $('#pass-user .alert').show().delay(7000).hide('slow');
              }
            })
          }
        }
      });

    </script>


  <?
  break;
  case 'add':
  case 'edit':
    if($user==NULL){ $id = 0; $action = "add"; }else{ ($_SESSION['nivel']=="A")?$action="edit_admin" : $action="edit"; }
  ?>
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="box box-success">
          <div class="box-header">
            <h3 class="box-title"><i class="fa <?=($id>0)? 'fa-pencil':'fa-user-plus'?>"></i> <?=($id>0)?'Modificar':'Agregar'?> Usuario</h3><br>
          </div>
          <div class="box-body">
            <form class="form-horizontal" action="funciones/class.usuarios.php" id="fr-registro" method="post">
              <input id="action" type="hidden" name="action" value="<?=$action?>">
              <input id="id" type="hidden" name="id" value="<?=($id>0)?$id:'0';?>">

              <div class="form-group">
                <label for="cedula" class="col-md-4 control-label">Cedula: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control numeros" id="cedula" name="cedula" placeholder="C.I" value="<?=($id>0)?$user->user_cedula:'';?>" maxlength="10" required>
                </div>
              </div>

              <div class="form-group">
                <label for="nombres" class="col-md-4 control-label">Nombres: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="nombres" name="nombres" placeholder="Nombres" value="<?=($id>0)?$user->user_nombres:'';?>" maxlength="50" required>
                </div>
              </div>

              <div class="form-group">
                <label for="apellidos" class="col-md-4 control-label">Apellidos: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos" value="<?=($id>0)?$user->user_apellidos:'';?>" maxlength="50" required>
                </div>
              </div>

              <div class="form-group">
                <label for="email" class="col-md-4 control-label">Email: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?=($id>0)?$user->user_email:'';?>" maxlength="40" pattern="^[a-zA-Z0-9.+_-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$" required>
                </div>
              </div>

              <div class="form-group">
                <label for="sexo" class="col-md-4 control-label">Sexo: *</label>
                <div class="col-md-3">
                  <select id="sexo" class="form-control" name="sexo" required>
                    <option value="">Seleccione...</option>
                    <option value="M" <?if($id>0){echo ($user->user_sexo=="M")?'selected':'';} ?>>Masculino</option>
                    <option value="F" <?if($id>0){echo ($user->user_sexo=="F")?'selected':'';} ?>>Femenino</option>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="telefono" class="col-md-4 control-label">Telefono: *</label>
                <div class="col-md-5">
                  <input type="text" class="form-control numeros" id="telefono" placeholder="Telefono" name="telefono" value="<?=($id>0)?$user->user_telefono:'';?>" maxlength="11" required>
                </div>
              </div>
            <?
              if($id==0){
            ?>
              <div class="form-group">
                <label for="pass" class="col-md-4 control-label">Contraseña: *</label>
                <div class="col-md-5">
                  <input type="password" class="form-control" id="pass" placeholder="Contrase&ntilde;a" name="pass" required>
                </div>
              </div>
            <?
            }
            ?>

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
                <a href="?ver=usuarios" class="btn btn-flat btn-default"><i class="fa fa-reply"></i> Volver</a>
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
    $user = $usuarios->consulta();
  ?>
    <div class="box box-warning color-palette-box">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-users"></i> Usuarios registrados</h3>
        <div class="pull-right">
          <a class="btn btn-flat btn-sm btn-success" href="?ver=usuarios&opc=add"><i class="fa fa-user-plus" aria-hidden="true"></i> Agregar usuario</a>
        </div>
      </div>
      <div class="box-body">
        <table class="table data-table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th class="text-center">Nombre</th>
              <th class="text-center">Apellido</th>
              <th class="text-center">Email</th>
              <th class="text-center">Nivel</th>
              <th class="text-center">Accion</th>
            </tr>
          </thead>
          <tbody>
          <? $i = 1;
            foreach ($user as $d) {
          ?>
            <tr>
              <td class="text-center"><?=$i?></td>
              <td><?=$d->user_nombres?></td>
              <td><?=$d->user_apellidos?></td>
              <td><?=$d->user_email?></td>
              <td class="text-center"><?=($d->user_nivel == "A")?'Administrador':'Colaborador'?></td>
              <td class="text-center">
                <a class="btn btn-flat btn-primary btn-sm" href="?ver=usuarios&opc=ver&id=<?=$d->id_user?>"><i class="fa fa-search"></i></a>
                <a class="btn btn-flat btn-success btn-sm" href="?ver=usuarios&opc=edit&id=<?=$d->id_user?>"><i class="fa fa-pencil"></i></a>
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
?>
</div>