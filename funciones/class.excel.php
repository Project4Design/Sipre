<?
require_once('phpexcel/PHPExcel.php');

class Excel{
	private $excel;
	private $path;

	public function __CONSTRUCT(){
		$this->excel = PHPExcel_IOFactory::createReader('Excel2007');
		$this->excel->setReadDataOnly(true);
		$this->path  = "temp/temp.xlsx";
	}

	public function table($file){
		move_uploaded_file($file, "temp/temp.xlsx");
		$objPHPExcel = $this->excel->load($this->path);
		$objWorksheet = $objPHPExcel->getActiveSheet();

		$i = 0; //Iterador
		$data = ""; //Tabla
		$required = [0,1,2,3,4,5,7,8,9,10,12]; //Campos requeridos
		foreach ($objWorksheet->getRowIterator() as $row) {

			//Saltar la primera fila que seria el titulo.
			if($i!=0){
			  $cellIterator = $row->getCellIterator();
			  $cellIterator->setIterateOnlyExistingCells(false);

			  $data .= "<tr><td>{$i}</td>";
			  //Contador para la fecha
			  $x = 0;
			  foreach ($cellIterator as $cell) {
			  	if(in_array($x,$required)&&($cell->getValue()==NULL||$cell->getValue()=="")){
			  		$data .= "<td><span style='color:red'>Requerido</span></td>";
			  	}else{
				  	if($x==7){
				  		//Tranformar fecha de formato excel a formato normal
				  		$fecha = ($cell->getValue()!=NULL||$cell->getValue()!="")?gmdate("Y-m-d",($cell->getValue() - 25569) * 86400) : '-';
				  		$data .= "<td>" . $fecha . "</td>";
				  	}else{
				    	$data .= "<td>" . $cell->getValue() . "</td>";
				  	}
				  }
			  	$x++;
			  }
			  $data .= "</tr>";
			}
			$i++;
		}

		return $data;
	}

	public function cargar(){
		//Verificar que el archivo temporal exista.
		if(is_readable('temp/temp.xlsx')){
			$objPHPExcel = $this->excel->load($this->path);
			$objWorksheet = $objPHPExcel->getActiveSheet();

			//Iterador | Array a devolver
			$i = 0; $data = array();
			foreach ($objWorksheet->getRowIterator() as $row) {

				//Saltar la primera fila que seria el titulo.
				if($i!=0){
				  $cellIterator = $row->getCellIterator();
				  $cellIterator->setIterateOnlyExistingCells(false);

				  //Array con datos individuales de votantes
				  $data2 = array();
				  foreach ($cellIterator as $cell) {
				    $data2[] = $cell->getValue();
				  }

				  $data[] = $data2;
				}
				$i++;	
			}//Foreach
		}else{
			$data = false;
		}

		return $data;
	}//Cargar
}
?>