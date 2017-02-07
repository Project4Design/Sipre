<?
require_once 'config/config.php';
isset($_SESSION['id']) ? session_destroy() : '';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Administracion</title>
  <!-- Tell the browser to be responsive to screen width -->
  <?=Base::Meta("viewport","width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no")?>
  <!-- Bootstrap 3.3.5 -->
	<?=Base::Css("includes/css/bootstrap.min.css")?>
  <!-- Font Awesome -->
  <?=Base::Css("includes/css/font-awesome.css")?>
  <!-- Theme style -->
  <?=Base::Css("includes/css/AdminLTE.min.css")?>
  <!-- iCheck -->
  <?=Base::Css("includes/css/blue.css")?>
	<?=Base::Js("includes/js/jquery-2.2.1.min.js")?>
  <?=Base::Js("includes/js/bootstrap.js")?>
  <!-- iCheck -->
  <?=Base::Js("includes/js/icheck.min.js")?>
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="../../index2.html"><b>SIPRE</b></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">- Solo personal autorizado -<br>Ingrese sus datos de Acceso</p>
      <form id="form-login" action="funciones/class.sesiones.php" method="post">
        <input type="hidden" name="action" value="login">

        <div class="form-group has-feedback">
          <input id="email" class="form-control" type="email" name="email" placeholder="Email">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input id="password" class="form-control" type="password" name="password" placeholder="Password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group">
          <div class="progress" style="display:none">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width:100%">
            </div>
          </div>
        </div>
        <div class="alert alert-danger" role="alert" style="display:none">
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>&nbsp;
          <span id="msj">Datos incorrectos</span>
        </div>
        <div class="row">
          <div class="col-xs-4">
            <button id="b-login" type="submit" class="btn btn-primary btn-block btn-flat">Ingresar</button>
          </div><!-- /.col -->
        </div>
      </form>

      
      <a href="#">Olvidé mi contraseña</a><br>
    </div><!-- /.login-box-body -->
  </div><!-- /.login-box -->

  <script type="text/javascript">
    $(function () {
      $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
      });
    });

    $(document).ready(function() {
      $('#b-login').click(function(e){
        e.preventDefault();
        $('.progress').hide();
        $('.progress').show();
        $('#b-login').button('loading');

        $.ajax({
          type: 'POST',
          cache: false,
          url: 'funciones/class.sesiones.php',
          data: $('#form-login').serialize(),
          dataType: 'json',
          success: function(r){
            console.log(r);
            if(r.response){
              $('.alert').removeClass('alert-danger').addClass('alert-success');
              window.location.replace(r.redirect);
            }else{
              $('.alert').removeClass('alert-success').addClass('alert-danger');
            }
            $('#msj').html(r.msj);
          },
          error: function(){
            $('.alert').removeClass('alert-success').addClass('alert-danger');
            $('#msj').html('Ha ocurrido un error inesperado')
          },
          complete: function(){
            $('.progress').hide('fast');
            $('.alert').show().delay(7000).hide('slow');
            $('#b-login').button('reset');
          }
        })
      })
    })
  </script>
</body>
</html>