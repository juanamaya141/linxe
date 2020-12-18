<?php

namespace Drupal\linxecredit\Libs;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;


class LinxeLibrary  {

	public function validaVigenciaSesion(){
		$session = \Drupal::request()->getSession();

		if( $session->get('last_activity') < time() - $session->get('expire_time') ) { //have we expired?
		    //redirect to logout.php
		    return false;
		} else{ //if we haven't expired:
	
			$session->set('last_activity',time());
			return true;
		}
	}

	public function getAccessToken()
	{
		$session = \Drupal::request()->getSession();

	    $session->remove('tkn_access');
	    $session->remove('tkn_expiresin');

		$urlws_token = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_token');
		
		$username_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.username_datascoring');
		$password_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.password_datascoring');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["username"] = $username_datascoring; 
	    $data["Password"] = $password_datascoring; 
	    $data["grant_type"] = "password"; 
	    $data["applicationid"] = $applicationid_datascoring; 
	    $data["projectid"] = $projectid_datascoring; 


	    $arrayreturn = [];

		$ch = curl_init($urlws_token);                                                                      
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));                                                                  
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);
		curl_setopt($ch, CURLOPT_TIMEOUT, 90);                                                                   
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	        'Content-Type: application/x-www-form-urlencoded')                                                                       
		);  
		//Disable CURLOPT_SSL_VERIFYHOST and CURLOPT_SSL_VERIFYPEER by
		//setting them to false.
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		//


	    $result = curl_exec($ch);

	    if (!curl_errno($ch)) {
	    	$obj = json_decode($result);
	    	$session->set('tkn_access',$obj->access_token);
      		$session->set('tkn_expiresin',$obj->expires_in);
	      	
			return $obj;
	    }else{
			echo 'Curl error: ' . curl_error($ch);
			$session->remove('tkn_access');
	   		$session->remove('tkn_expiresin');
	    	$arrayreturn["error"] = "No se ha podido conectar al servicio";
	    	return json_encode($arrayreturn);
	    }
	    
	    // Cerrar el manejador
	    curl_close($ch);	
	}

	private function requestService($urlws_backend,$data)
	{
		$session = \Drupal::request()->getSession();
		
		set_time_limit(0);
		$respuestaOk = false;
		$dataencode = json_encode($data);

		if(!$session->has('tkn_access'))
	    {
	      $objAccessToken = $this->getAccessToken();
	    }

	    try{
		    do{

				$token = $session->get('tkn_access');
				//echo $token;
				//echo $urlws_backend;
				//exit();
				//
				$ch = curl_init($urlws_backend);                                                                     
			    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataencode);                                                                  
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
			    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);
				curl_setopt($ch, CURLOPT_TIMEOUT, 90);                                                                 
			    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			        'Content-Type: application/json',
			    	'Authorization: Bearer '.$token)                                                                       
				);  
				//Disable CURLOPT_SSL_VERIFYHOST and CURLOPT_SSL_VERIFYPEER by
				//setting them to false.
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				//
			    $result = curl_exec($ch);

			   
			    
			    if(curl_errno($ch)){
			    	//echo "exception";
				    throw new \Exception(curl_error($ch));
				    
				}else{
					$obj = json_decode($result);

			      	if($obj->Message=="Se ha denegado la autorización para esta solicitud.")
			      	{
			      		
			      		//significa que hay que solicitar nuevamente un token de acceso ya que se venció el anterior
			      		$objAccessToken = $this->getAccessToken();
			      		if(array_key_exists('access_token', $objAccessToken))
			      		{
			      			//obtengo nuevo token y vuelvo a iterar
			      			$respuestaOk = false;
			      		}else{
			      			//responder con error de generacion de token y no itera
			      			$respuestaOk = true;
			      			$arrayreturn = json_encode($objAccessToken);
			      		}
			      	}else{
			      		//retornar respuesta del servicio
			      		$arrayreturn = $obj;
			      		$respuestaOk = true;
			      	}
				}
			    
			}while($respuestaOk == false);
		} catch(\Exception $e) {
		    // catch exception here
		    $obj["error"] = "No se ha podido conectar al servicio";
			$arrayreturn = json_encode($obj);
		    return $arrayreturn;
		}

		curl_close($ch);
		return $arrayreturn;
	}


	public function getLogin($user,$pass)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 23200; 
	    $data["Table"] = 1; 

	    $controls = [];
	    $controls[0]["Id"] = "usuario";
	    $controls[0]["Valor"] = $user;
	    $controls[1]["Id"] = "contrasenia";
	    $controls[1]["Valor"] = $pass;

	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);
	
	}



	public function getEmpresas()
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 20672; 
	    $data["Table"] = 1; 
	    
	    return $this->requestService($urlws_backend,$data);	
	}


	public function getCargosYTiposContrato($empresa)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 20674; 
	    $data["Table"] = 1; 
	    
	    $controls = [];
	    $controls[0]["Id"] = "idEmpresa";
	    $controls[0]["Valor"] = $empresa;

	    $data["Controls"] = $controls; 

	    return $this->requestService($urlws_backend,$data);		
	}


	public function getRegister($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 17621; 
	    $data["Table"] = 0; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
		
		//print_r($data);

	    return $this->requestService($urlws_backend,$data);		
	}

	public function getRegisterWithoutValidation($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 50214; 
	    $data["Table"] = 0; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);		
	}

	public function createEmpresaDatascoring($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 50202; 
	    $data["Table"] = 0; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);		
	}

	public function getChangePw($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 18645; 
	    $data["Table"] = 0; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);		
	}

	public function getMontoCredito($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 21731; //monto crédito
	    $data["Table"] = 1; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);		
	}


	public function autorizacionSolicitud($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 17670;  // autorización solicitud
	    $data["Table"] = 0; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);		
	}


	public function getMisCreditos($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 22090; //mis créditos
	    $data["Table"] = 1; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);		
	}

	public function setPreferenciaRegistro($datafield)
	{
		print_r($datafield);
		$query = \Drupal::database();
	    $respuesta = $query ->insert('preferenciasUsuarios')
	    ->fields($datafield)
	    ->execute();	

	    return $respuesta;
	}

	public function envioOTP($id_tipodoc,$num_id)
	{
	    

	    $dataField["tipoId"] = $id_tipodoc;
	    $dataField["numId"] = $num_id;
	    $respuestaArray = $this->consumoOTP($dataField);
	    //print_r($respuestaArray);
	    //exit();
	    if(array_key_exists('Success', $respuestaArray))
	    {
	      return $respuestaArray->Success;
	    }
	}


	public function consumoOTP($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 23388; //envío otp - sms
	    $data["Table"] = 1; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);		
	}

	/**
	** Funcion para ofuscar texto , utilizada para mostrar parte del celular o el email pero ocultar otra parte
	**/
	public function ofuscarTexto($texto)
	{
	    $strlen = strlen($texto);

	    $posIni = intval($strlen/4);

	    $posFin = $strlen - $posIni;

	    $arrayparts = str_split($texto);
	    $strresult = "";
	    for($i=0; $i < $strlen; $i++)
	    {
	    	if($i < $posIni)
	    	{
	    		$strresult.=$arrayparts[$i];
	    	}elseif($i >= $posIni && $i < $posFin)
	    	{
	    		$strresult.="*";
	    	}elseif($i >= $posFin)
	    	{
	    		$strresult.=$arrayparts[$i];
	    	}
	    }
	    return $strresult;
	}

	public function validaCambioContrasena($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 17676; //envío otp - sms
	    $data["Table"] = 0; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);		
	}
	
	public function getRecoveryPw($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 24159; //envío valida cambio contraseña
	    $data["Table"] = 0; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);		
	}


	public function getGeneraPagareBlanco($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 24636; //envío valida cambio contraseña
	    $data["Table"] = 0; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 
	    

	    return $this->requestService($urlws_backend,$data);		
	}

	public function getFirmarPagare($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 25501; //envío firmar pagaré
	    $data["Table"] = 0; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 

	    
	    

	    return $this->requestService($urlws_backend,$data);		
	}

	public function getPagareBase64($datafield)
	{
		$urlws_backend = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_backend');
		$applicationid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.applicationid_datascoring');
		$projectid_datascoring = \Drupal::config('linxecredit.settings')->get('linxecredit.projectid_datascoring');
		
		$data["ProjectId"] = $projectid_datascoring; 
	    $data["ApplicationId"] = $applicationid_datascoring; 
	    $data["ButtonId"] = 26880; //envío firmar pagaré
	    $data["Table"] = 1; 

	    $controls = [];
	    $i=0;
	    foreach ($datafield as $key => $value) {
	    	$controls[$i]["Id"] = $key;
	    	$controls[$i]["Valor"] = $value;
	    	$i++;
	    }
	    
	    $data["Controls"] = $controls; 

	    
	    //print_r($urlws_backend);
	    //print_r($data);

	    return $this->requestService($urlws_backend,$data);		
	}

	public function queryWebinar($datos)
	{
		$urlQueryWebinar = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_querywebinar');
		$webinar_apikey = \Drupal::config('linxecredit.settings')->get('linxecredit.webinar_apikey');

		

		$dataencode['api_key']=$webinar_apikey;
		$dataencode['webinar_id']=$datos["webinar_id"];
		

		
		
		
		$ch = curl_init($urlQueryWebinar."?".http_build_query($dataencode));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dataencode) );                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: text/html'                                                                      
		));  
		$result = curl_exec($ch);

		

		if (!curl_errno($ch)) {

			$obj = json_decode($result);
			return $obj;
		}else{
			return false;
		}
	}
	
	public function enviarWebinar($datos)
	{
		$urlWebinar = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_webinar');
		$webinar_apikey = \Drupal::config('linxecredit.settings')->get('linxecredit.webinar_apikey');

		$arrayName = explode(" ", $datos["name"]);
		$name = $arrayName[0];
		if(count($arrayName)>1)
			$lastname = $arrayName[1];
		else
			$lastname = $arrayName[0];

		$dataencode['api_key']=$webinar_apikey;
		$dataencode['webinar_id']=$datos["webinar_id"];
		$dataencode['first_name']=$name;
		$dataencode['last_name']=$lastname;
		$dataencode['email']=$datos["email"];
		$dataencode['schedule']=$datos["webinar_schedule"];
		
		//print_r($dataencode);

		//echo $urlWebinar."?".http_build_query($dataencode);

		$ch = curl_init($urlWebinar."?".http_build_query($dataencode));                                                                     
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dataencode);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			'Content-Type: text/html'                                                                      
		));  
		$result = curl_exec($ch);


		if (!curl_errno($ch)) {
			$obj = json_decode($result);

			if($obj->status=="success")
			{
				return true;
			}else{
			    return false;
			}
		}else{
			return false;
		}
	}

}
