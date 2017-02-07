<?php

//Clase para la conexion a la Base de datos
class DB {
	private $_connection;
	private static $_instance; //The single instance
	private $_host = DB_HOST;
	private $_username = DB_USER;
	private $_password = DB_PASS;
	private $_database = DB_TABLE;

	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	// Constructor
	private function __construct() {
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		try{
			$this->_connection = new mysqli($this->_host, $this->_username,$this->_password, $this->_database);
			$this->_connection->set_charset("utf8");
		} catch(Exception $e){
			Base::Elog($e);
		}
	}
	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }
	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
	}
}

//Clase para ejecutar las Querys de forma dinamica
class Query extends DB {

	//Query normal, devuelve el arreglo completo
	public static function run($sql) {
		//Variable $r que sera devuelta por la funcion. en caso de de un error con la sentencia se devuelve NULL
		//si no, se devuelve el resultado de la sentencia
		$r = null;

		try{
			//Ejecutar la sentencia
			$r = parent::getInstance()->getConnection()->query($sql);

		}catch(Exception $e){
			Base::Elog($e);
		}
		//Cerrar conexion con la BD
		//DB::getInstance()->getConnection()->close();

		return $r;
	}

	//Sentencia preparada dinamicas.
	//Se preparan las sentencias a ejecutarse $query y se reemplazan los parametros de $data en las posiciones ?
	public static function prun($query,$data = NULL){
		$rh = (object)array();

		//Array que contendra los parametros a ser utilizado en la sentencia extraidos de $data
		$refs = array();

		//Se recorre $data para asignarlos a $refs como referencia &$data[$key] (Necesario para la sentencia dinamica).
		if(count($data) > 0){
			foreach ($data as $key => $value){
				if($value == ''){
					$refs[$key] = NULL;
				}else{
					$refs[$key] = &$data[$key];		
				}
			}
		}
		//Manejar errores, en caso de error, se ejecuta el Catch()
		try{
			//Se prepara la sentencia a ejecutar y se almacena en $stm
			$stm = parent::getInstance()->getConnection()->prepare($query);
			
			if($data !== NULL){
				//Se instancian los metodos de mysqli, se enlazan los parametros $refs (bind_param) a la sentencia $stm
				$ref = new ReflectionClass('mysqli_stmt');
				$method = $ref->getMethod("bind_param");
				$method->invokeArgs($stm,$refs);
			}

			//Se guarda el resultado de la sentencia en $rh->response, true o false en caso de error.
			$rh->response = $stm->execute();

			//Si la sentencia ejecutada es de tipo INSERT, se devuelte el id del dato insertado para su uso.
			//Si no es Insert, (SELECT,UPDATE,DELETE) se devuelve los resultados $rh->result = $stm->get_result()
			if($rh->response === true && strpos($query, strtoupper('INSERT')) === 0):
				$rh->id = $stm->insert_id;
			else:
				$rh->result = $stm->get_result();
			endif;
		}catch(Exception $e){
			$rh->response = false;
			$rh->error = $e;
			Base::Elog($e);
		}

		//Devolver los resultados
		return $rh;
	}

	//Cerrar conexion con la BD
	public static function close(){
		return DB::getInstance()->getConnection()->close();
	}
}

//Funcion para escapar caracteres por seguridad.
function escape($k){ return DB::getInstance()->getConnection()->real_escape_string($k); }
?>