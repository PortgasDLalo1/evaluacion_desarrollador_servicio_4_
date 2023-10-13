<?php 
	/**
	 * 
	 */
	require_once('modelo/valida.php');
	require_once('datos/objeto.php');
	class api
	{
		//$metodo = null; se comento esta variable, no se necesitaba
		
		public function __construct($metodo)
		{			
			$this->metodo = $metodo;			
		}

		public function call(){
			try {
				$tipo = "1";
				if(isset($_GET['tipo'])){
					$tipo = $_GET['tipo'];
				}
				if(isset($_GET['nombre'])){
					$nombre = $_GET['nombre'];
				}
				switch ($this->metodo) {
					case 'GET':
						if($tipo == "1"){
							$this->MetodoGet();
						}else{
							$this->exportar($nombre);
						}
						break;			
					default:					
						break;
				}				
			} catch (Exception $e) {
				$Validar->CreaRespuesta("-1", "Error ".$e, []); // se agrego para crear la respuesta en caso de fallo
			}				
		}

		public function MetodoGet(){			
			try {
				$ObjetoColor = new objeto();
				$Validar = new valida();
				$Valor = $ObjetoColor->ObtenerObjeto(); // se le asigno el array de objectos al campo valor
				
				$Validar->CreaRespuesta("0", "", $Valor);
				
				$Validar->ObtenerResponse();
			} catch (Exception $e) {
				$Validar->CreaRespuesta("-1", "Error", []);
			}
			// $Response = $Validar->ObtenerResponse(); se comento ya que estaba de mas
		}
		public function exportar($nombreArchivo){
			try{
				$Validar = new valida();
				$rutatemp = "temp/";
				$ValorObjeto = $ObjetoColor->ObtenerObjeto();

				$nombreArchivo = $nombreArchivo . ".json";
				file_put_contents($rutatemp . $nombreArchivo, json_encode($ValorObjeto), FILE_APPEND | LOCK_EX);
				$fileName = basename($nombreArchivo);
				$filePath = "../".$rutatemp . $fileName;
				if(!empty($fileName) && file_exists($filePath)){
					//echo "rutatemp: " . $rutatemp . ", nombreArchivo: " . $nombreArchivo . ", filePath: " . $filePath  . ", json: " . json_encode($Respuesta);

					//Define header information
					header('Content-Description: File Transfer');
					header('Content-Type: txt/html');
					header("Cache-Control: no-cache, must-revalidate");
					header("Expires: 0");
					header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
					header('Content-Length: ' . filesize($filePath));
					header('Pragma: public');
					//Clear system output buffer
					flush();

					//Read the size of the file
					readfile($filePath);

					//Terminate from the script
					die();
				}
			}catch(Exception $e) {
				$Validar->CreaRespuesta("-1", "Error", []);
			}
		}

	}
?>