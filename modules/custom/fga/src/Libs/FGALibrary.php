<?php
namespace Drupal\fga\Libs;

// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
//require __DIR__ . '/twilio-php-master/src/Twilio/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
//use Twilio\Rest\Client;


class FGALibrary  {

	

	public function getAccessTokenFGA()
	{
		$session = \Drupal::request()->getSession();

	    $session->remove('tkn_access_fga');
	    $session->remove('tkn_expires_in_fga');

		$urlws_fga_token = 'https://sig3test.fga.com.co/api/v1/login';
		$username_fga = "901260610-01";
		//$username_fga = \Drupal::config('linxecredit.settings')->get('linxecredit.username_fga');
		//$password_fga = \Drupal::config('linxecredit.settings')->get('linxecredit.password_fga');
		$password_fga = "FGALINXE2021";

		//$user_basic = \Drupal::config('linxecredit.settings')->get('linxecredit.username_basic_fga');
		//$pw_basic = \Drupal::config('linxecredit.settings')->get('linxecredit.password_basic_fga');
		
		$data["username"] = $username_fga; 
	    $data["password"] = $password_fga; 

		


	    $arrayreturn = [];

        $ch = curl_init($urlws_fga_token);  

	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));                                                                  
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);
		curl_setopt($ch, CURLOPT_TIMEOUT, 90);   
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
		//curl_setopt($ch, CURLOPT_USERPWD, "$user_basic:$pw_basic");                                                               
	     
		$result = curl_exec($ch);
		
		

	    if (!curl_errno($ch)) {
			
	    	$obj = json_decode($result);
			if($obj->error){
				return false;
			}
	    	$session->set('tkn_access_fga',$obj->access_token);
      		$session->set('tkn_expires_in_fga',$obj->expires_in);
	      	
			return $obj;
	    }else{
	    	$session->remove('tkn_access_fga');
	   		$session->remove('tkn_expires_in_fga');
	    	$arrayreturn["error"] = "No se ha podido conectar al servicio de token fga";
	    	return json_encode($arrayreturn);
	    }
	    
	    // Cerrar el manejador
        curl_close($ch);	
        return $result ;
	}

	private function requestServiceFGA($urlws_backend,$data)
	{
		$session = \Drupal::request()->getSession();
		set_time_limit(0);
		$respuestaOk = false;
		$dataencode = json_encode($data);

		//echo $dataencode;
		
		if(!$session->has('tkn_access_fga'))
	    {
	      $objAccessToken = $this->getAccessTokenFGA();
	    }

	    try{
		    do{

				$token = $session->get('tkn_access_fga');
				//
				if(!$token){
					$getToken = $this->getAccessTokenFGA();
					if(!$getToken){
						return ['status' => false, 'msg' => 'No se ha podido generar el token'];
					}
				};

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
			    $result = curl_exec($ch);
			    
			    if(curl_errno($ch)){
			    	//echo "exception";
				    throw new \Exception(curl_error($ch));
				    
				}else{
					$obj = json_decode($result);

					

			      	if($obj->error=="invalid_token")
			      	{
			      		//significa que hay que solicitar nuevamente un token de acceso ya que se venció el anterior
			      		$objAccessToken = $this->getAccessTokenFGA();
			      		if(array_key_exists('access_token', $objAccessToken))
			      		{
			      			//obtengo nuevo token y vuelvo a iterar
			      			$respuestaOk = false;
			      		}else{
			      			//responder con error de generacion de token y no itera
			      			$respuestaOk = true;
							$arrayreturn ['status']= false;
			      			$arrayreturn ['msg'] = 'No se ha podido generar el token';
			      		}
			      	}else{
			      		//retornar respuesta del servicio
						$arrayreturn ['status'] = true;
						$arrayreturn['obj'] =  $obj;
			      		$respuestaOk = true;
			      	}
				}
			    
			}while($respuestaOk == false);
		} catch(\Exception $e) {
		    // catch exception here
			$arrayreturn ['status']= false;
			$arrayreturn ['msg']= "No se ha podido conectar al servicio de fga";
		    return $arrayreturn;
		}

		curl_close($ch);
		return $arrayreturn;
	}


	public function fgaClaimGarantiaWS($dataWS, $id)
	{
        $database = \Drupal::database();
		//$urlws_fga = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_fga');
		
		$urlws_garantias_ws = "https://sig3test.fga.com.co/api/v1/portal/cargues/reclamacion/totales";

		$respuestaFGA = $this->requestServiceFGA($urlws_garantias_ws,$dataWS);
		if($respuestaFGA['status'])
        {		
			$data = array();	
			$data["servicio_request"] = json_encode($dataWS);
            $data["servicio_response"] = json_encode($respuestaFGA['obj']);
			if(!$respuestaFGA['obj']->errors){
				$data['estado_garantia'] = 'reclamacion';
			}
			
			
			$update = $database->update('garantias_fga')
				->fields($data)
				->condition('idgarantia', $id, '=')
				->execute();
			
            if($update && !$respuestaFGA['obj']->errors)
            {
                //actualizamos en la tabla de adelanto_nomina que ya se radico la garantia
                $obj["status"] = true;
                $obj["msg"] = "Se ha enviado la reclamación correctamente";
                //$arrayreturn = json_encode($obj);
				return $obj;             
            }else{
                $obj["status"] = false;
                $obj["msg"] = "Se ha presentado un error al intentar hacer la reclamación. Por favor contactar con soporte";
                return $obj;
            }
        }else{
            return $respuestaFGA;
            //$arrayreturn = json_encode($obj);
        }
	
    }

	public function fgaUpdateGarantiaWS($dataWS, $id)
	{
        $database = \Drupal::database();
		//$urlws_fga = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_fga');
		
		$urlws_garantias_ws = "https://sig3test.fga.com.co/api/v1/portal/cargues/cartera";

		$respuestaFGA = $this->requestServiceFGA($urlws_garantias_ws,$dataWS);
		
        if($respuestaFGA['status'])
        {		
			$data = array();	
			$data["servicio_request"] = json_encode($dataWS);
            $data["servicio_response"] = json_encode($respuestaFGA['obj']);
			$data['garantia_monto_faltante'] = $dataWS['saldo_pendiente'];
			$update = $database->update('garantias_fga')
				->fields($data)
				->condition('idgarantia', $id, '=')
				->execute();
			
            if($update)
            {
                //actualizamos en la tabla de adelanto_nomina que ya se radico la garantia
                $obj["status"] = "ok";
                $obj["operation"] = "create";
                $obj["msg"] = "Se ha enviado la reclamación correctamente";
                $arrayreturn = json_encode($obj);                
            }else{
                $obj["status"] = "fail";
                $obj["error"] = "Se ha enviado la reclamación pero no se ha podido guardar la respuesta";
                $arrayreturn = json_encode($obj);
            }
        }else{
            $obj["status"] = "fail";
            $obj["error"] = "No se pudo actualizar el estado de la garantía";
            $arrayreturn = json_encode($obj);
        }
        return $arrayreturn;
	
    }
    
}