<?
require_once 'header.php';
if(isset($_SESSION['id'])===false):
	require_once 'views/prohibido.php';
else:

	switch($inicio):
		case 'perfil':
			require_once 'views/perfil.php';
		break;
		case 'usuarios':
			if($_SESSION['nivel']=="A"):
				require_once 'views/usuarios.php';
			else:
				require_once 'views/prohibido.php';
			endif;
		break;
		case 'electores':
			require_once 'views/electores.php';
		break;
		case 'sectores':
			require_once 'views/sectores.php';
		break;
			case 'centros':
			require_once 'views/centros.php';
		break;
		default:
			$usuarios    = new Usuarios();
			//Total de usuarios
			$totaluser   = count($usuarios->consulta());
			$electores   = new Electores();
			//Total de electores
	    $totale      = $electores->total_sexo("M") + $electores->total_sexo("F");
	    //Top 10 secotres
	    $sh          = new Sectores();
	    $ubicaciones = $sh->topSh();
	    //To 10 centros de votacion
	    $centros     = new Centros();
	    $topcentros  = $centros->topCentros();
		?>
	    <section class="content-header">
	      <h1 class="text-center">
	        BASE DE DATOS MUNICIPIO SUCRE –EDO. ARAGUA
	      </h1>
	      <ol class="breadcrumb">
	        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
	      </ol>
	    </section>

	    <!-- Main content -->
	    <section class="content">
	      <!-- Info boxes -->
	      <div class="row">
	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="small-box bg-yellow">
	            <div class="inner">
	              <h3><?=$totaluser?></h3>

	              <p>Usuarios</p>
	            </div>
	            <div class="icon">	
	              <i class="fa fa-user-plus"></i>
	            </div>
	            <a href="?ver=usuarios" class="small-box-footer">
	              Mas informacion <i class="fa fa-arrow-circle-right"></i>
	            </a>
	          </div>
	        </div>
	        <!-- /.col -->
	        <div class="col-md-3 col-sm-6 col-xs-12">
		        <div class="small-box bg-red">
	            <div class="inner">
	              <h3><?=$totale?></h3>

	              <p>Electores</p>
	            </div>
	            <div class="icon">	
	              <i class="fa fa-address-book"></i>
	            </div>
	            <a href="?ver=electores" class="small-box-footer">
	              Mas informacion <i class="fa fa-arrow-circle-right"></i>
	            </a>
	          </div>
	        </div>
	        <!-- /.col -->
	      </div>
	      <!-- /.row -->

	      <div class="row">
	        <div class="col-md-6">
	          <div class="box box-danger">
	            <div class="box-header with-border">
	              <h3 class="box-title"> Centros de votacion</h3>

	              <div class="box-tools pull-right">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
	              </div>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
            		<table id="tableCentros" class="table table-basic no-margin table-condensed">
            			<thead>
            				<tr>
            					<th>#</th>
            					<th>Centros</th>
            					<th>Electores</th>
            					<th>%</th>
            				</tr>
            			</thead>
            			<tboby>
          				<?$i=1;
          					foreach ($topcentros as $d){
          				?>
          					<tr>
            					<td><?=$i?></td>
            					<td><?=$d->cent_nombre?></td>
            					<td class="text-center"><?=$d->total?></td>
            					<td><?=round(($d->total*100)/$totale,2)?> <small>%</small></td>
          					</tr>
          				<?$i++;
          					}
          				?>
            			</tboby>
            		</table>
	            </div>
	            <!-- ./box-body -->
	            <div class="box-footer">
	            </div>
	            <!-- /.box-footer -->
	          </div>
	          <!-- /.box -->
	        </div>
	        <!-- /.col -->
	        <div class="col-md-6">
	          <div class="box box-warning">
	            <div class="box-header with-border">
	              <h3 class="box-title">Ubicaciones</h3>

	              <div class="box-tools pull-right">
	                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
	                </button>
	                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
	              </div>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body">
            		<table id="tableUbicaciones" class="table table-basic no-margin table-condensed">
            			<thead>
            				<tr>
            					<th>#</th>
            					<th>Ubicaciones</th>
            					<th>Electores</th>
            					<th>%</th>
            				</tr>
            			</thead>
            			<tboby>
          				<?$i=1;
          					foreach ($ubicaciones as $d){
          				?>
          					<tr>
            					<td><?=$i?></td>
            					<td><?=$d->sh_nombre?></td>
            					<td class="text-center"><?=$d->total?></td>
            					<td><?=round(($d->total*100)/$totale,2)?> <small>%</small></td>
          					</tr>
          				<?$i++;
          					}
          				?>
            			</tboby>
            		</table>
	            </div>
	            <!-- ./box-body -->
	            <div class="box-footer">
	            </div>
	            <!-- /.box-footer -->
	          </div>
	          <!-- /.box -->
	        </div>
	        <!-- /.col -->
	      </div>
	      <!-- /.row -->

		<?
		break;
	endswitch;//Switch($vista)
endif;
require_once 'footer.php';
?>