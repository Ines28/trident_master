<?


class QueryClass extends Configuration{

	//Definición de variables para la conexión con el servidor
	 var $_hostname;
	 var $_database; 
	 var $_username;
	 var $_password;
	 var $_connection;
	
	 var $_recordset;
	 var $_row;
	
	//Constructor de la Clase
	function QueryClass(){
	
		//
		
	}
	
	//Métodos de la clase------------------------------------
	
	//Establece la conexión con el servidor y la base de datos
	function serverConnection(){
		$this->_connection = mysql_pconnect($this->cf_host, $this->cf_user,$this->cf_password) or trigger_error(mysql_error(),		E_USER_ERROR);
		//$this->_connection = mysql_connect($this->cf_host, $this->cf_user,$this->cf_password) or trigger_error(mysql_error(),		E_USER_ERROR);
		mysql_select_db($this->cf_db, $this->_connection) or trigger_error(mysql_error(),E_USER_ERROR);
		
	}
	
	function query($query,$type){
		$this->_recordset = mysql_query($query, $this->_connection) or die(mysql_error());
		
		if($type == "select"){
			$this->_row = mysql_fetch_assoc($this->_recordset);	
			return $this->_row;
			
		}
		
		if($type == "list"){
			return $this->_recordset;
			
		}
		
		
		mysql_free_result($this->_recordset);
		/*mysql_close($this->_connection);*/
	}
}
?>
