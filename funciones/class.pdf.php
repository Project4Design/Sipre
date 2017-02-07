<?
require "../funciones/dompdf/dompdf_config.inc.php";
class Pdf{
	private $pdf;

	public function __CONSTRUCT()
	{
		$this->pdf = new DOMPDF();
	}

	public function build($content,$header = "",$footer = "")
	{
		$body = "<html>
					    <head>
					      <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
					      <style>
					        html{font-family: sans-serif;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;}
					        header{position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; text-align: center; }
					        .footer{position:fixed;left:0px;right:0px;bottom:0px;}
					        .footer .page:after{content: counter(page);}
					        .table{
					          width: 100%;
					          max-width: 100%;
					          margin-bottom: 20px;
					          border: 1px solid #ccc;
					          border-spacing: 0;
					          border-collapse: collapse;
					        }
					        .table>thead>tr>th,
					        .table>tbody>tr>th,
					        .table>tfoot>tr>th,
					        .table>thead>tr>td,
					        .table>tbody>tr>td,
					        .table>tfoot>tr>td{
					          padding: 3px;
					          line-height: 1.42857143;
					          vertical-align: top;
					          border: 1px solid #CCC;
					        }
					        .table > thead > tr > th {
					          vertical-align: bottom;
					          border-bottom: 2px solid #CCC;
					        }
					        .right{
					          text-align:right;
					        }
					        .center{
					          text-align:center;
					        }
					        .col1,.col2,.col3,.col4,.col5,.col6,.col7,.col8,.col9,.col10,.col11,.col12{ margin:0;padding:0;display: inline-block; }
					        .col1{width: 8.33333333%;}
					        .col2{width: 16.66666667%;}
					        .col3{width: 24%;}
					        .col4{width: 32.33%;}
					        .col5{width: 41.66666667%;}
					        .col6{width: 50%;}
					        .col7{width: 58.33333333%;}
					        .col8{width: 66.66666667%;}
					        .col9{width: 75%;}
					        .col10{width: 83.33333333%;}
					        .col11{width: 91.66666667%;}
					        .row,.col12{width: 99%;}
					        .row{position:relative;}
					      </style>
					    </head>
					    <body>
					    	<div class=\"header\">
					    	".$header."
					    	</div>
								<div class=\"footer\">".$footer."</div>
							".$content."
							</body>
						</html>
						";
		return $body;
	}

	public function out($output,$name = "reporte")
	{
	  $this->pdf->load_html($output);
	  ini_set("memory_limit","128M");
	  $this->pdf->render();
	  $this->pdf->stream($name.".pdf");
	}//print

}//Class Pdf
?>