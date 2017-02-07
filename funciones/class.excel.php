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

		//Iterador
		$i = 0; $data = "";
		foreach ($objWorksheet->getRowIterator() as $row) {

			//Saltar la primera fila que seria el titulo.
			if($i!=0){
			  $cellIterator = $row->getCellIterator();
			  $cellIterator->setIterateOnlyExistingCells(false);

			  $data .= "<tr><td>{$i}</td>";
			  foreach ($cellIterator as $cell) {
			    $data .= "<td>" . $cell->getValue() . "</td>";
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