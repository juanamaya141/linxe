<?php

namespace Drupal\linxecredit\Libs;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;

// Require the bundled autoload file - the path may need to change
// based on where you downloaded and unzipped the SDK
//require __DIR__ . '/twilio-php-master/src/Twilio/autoload.php';

// Use the REST API Client to make requests to the Twilio REST API
//use Twilio\Rest\Client;


class AdelantoLibrary  {

	

	public function getAccessTokenMareigua()
	{
		$session = \Drupal::request()->getSession();

	    $session->remove('tkn_access_mareigua');
	    $session->remove('tkn_expires_in_mareigua');

		$urlws_mareigua = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_mareigua');
		
		$username_mareigua = \Drupal::config('linxecredit.settings')->get('linxecredit.username_mareigua');
		$password_mareigua = \Drupal::config('linxecredit.settings')->get('linxecredit.password_mareigua');
		
		$data["username"] = $username_mareigua; 
	    $data["password"] = $password_mareigua; 
	    $data["grant_type"] = "password"; 

		$urlws_mareigua_token = $urlws_mareigua."token"; 

	    $arrayreturn = [];

		$ch = curl_init($urlws_mareigua_token);                                                                      
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));                                                                  
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);
		curl_setopt($ch, CURLOPT_TIMEOUT, 90);                                                                   
	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	        'Content-Type: application/x-www-form-urlencoded')                                                                       
	    );  
	    $result = curl_exec($ch);

	    if (!curl_errno($ch)) {
	    	$obj = json_decode($result);
	    	$session->set('tkn_access_mareigua',$obj->access_token);
      		$session->set('tkn_expires_in_mareigua',$obj->expires_in);
	      	
			return $obj;
	    }else{
	    	$session->remove('tkn_access_mareigua');
	   		$session->remove('tkn_expires_in_mareigua');
	    	$arrayreturn["error"] = "No se ha podido conectar al servicio de token mareigua";
	    	return json_encode($arrayreturn);
	    }
	    
	    // Cerrar el manejador
	    curl_close($ch);	
	}

	private function requestServiceMareigua($urlws_backend,$data)
	{
		$session = \Drupal::request()->getSession();
		set_time_limit(0);
		$respuestaOk = false;
		$dataencode = json_encode($data);
		//echo $dataencode;
		if(!$session->has('tkn_access_mareigua'))
	    {
	      $objAccessToken = $this->getAccessTokenMareigua();
	    }

	    try{
		    do{

				$token = $session->get('tkn_access_mareigua');
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
			    $result = curl_exec($ch);

			   
			    
			    if(curl_errno($ch)){
			    	//echo "exception";
				    throw new \Exception(curl_error($ch));
				    
				}else{
					$obj = json_decode($result);

			      	if($obj->Message=="Authorization has been denied for this request.")
			      	{
			      		
			      		//significa que hay que solicitar nuevamente un token de acceso ya que se venció el anterior
			      		$objAccessToken = $this->getAccessTokenMareigua();
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
                            $fecha = date("Y-m-d H:i:s");
                            $respuestaServicio = json_encode($obj);                        
                            $field  = array(
                                'fechahora_consulta'   => $fecha,
                                'cc_consultado'   => $data["documento"],
                                'respuesta_servicio'   => $respuestaServicio,
                            );
            
                            $addData = \Drupal::database();
                            $insertData = $addData->insert('mareigua_consultas')
                                    ->fields($field)
                                    ->execute();
                            //insert fechahora, cc ,respuesta
			      		$respuestaOk = true;
			      	}
				}
			    
			}while($respuestaOk == false);
		} catch(\Exception $e) {
		    // catch exception here
		    $obj["error"] = "No se ha podido conectar al servicio de mareigua";
			$arrayreturn = json_encode($obj);
		    return $arrayreturn;
		}

		curl_close($ch);
		return $arrayreturn;
	}


	public function mareiguaAnalyticsIE($data)
	{

		$urlws_mareigua = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_mareigua');
		$username_mareigua = \Drupal::config('linxecredit.settings')->get('linxecredit.username_mareigua');
		$password_mareigua = \Drupal::config('linxecredit.settings')->get('linxecredit.password_mareigua');
		
		$urlws_consultas_mareigua = $urlws_mareigua."consultas"; 
		$tipo_identificacion_id = $this->getIDTypeIdentification($data["tipodocumento"]);
		

		$data["tipo_identificacion_id"] = $tipo_identificacion_id; 
	    $data["NumeroEmpleado_id"] = $data["documento"]; 
		$data["producto_id"] = 12; // 12 meses
		
		

	    return $this->requestServiceMareigua($urlws_consultas_mareigua,$data);
	
	}

	public function getIDTypeIdentification($type)
	{
		switch($type){
			case "cc": $tipo_identificacion_id = 1; break;
			case "ce": $tipo_identificacion_id = 2; break;
			case "nit": $tipo_identificacion_id = 3; break;
			case "ti": $tipo_identificacion_id = 4; break;
			case "rc": $tipo_identificacion_id = 5; break;
			case "pa": $tipo_identificacion_id = 6; break;
			case "cd": $tipo_identificacion_id = 7; break;
			case "sc": $tipo_identificacion_id = 8; break;
			case "pe": $tipo_identificacion_id = 9; break;
			default : $tipo_identificacion_id = 1;
		}
		return $tipo_identificacion_id;
	}


	public function registerAdelantoNomina($arrayFields)
	{
		$arrayreturn = [];
		if(!empty($arrayFields))
		{	
			$database = \Drupal::database();

			// Create an object of type Select.
			$query = $database->select('registrados_an', 'an');
			
			// Data extra detail to this query object: a condition, fields and a range.
			$cantidad = $query->condition('an.tipodocumento', $arrayFields["tipodocumento"] , '=')
			->condition('an.documento', $arrayFields["documento"] , '=')
			->countQuery()->execute()->fetchField();
			if($cantidad > 0)
			{
				$update = $database->update('registrados_an')
							->fields($arrayFields)
							->condition('tipodocumento', $arrayFields["tipodocumento"] , '=')
							->condition('documento', $arrayFields["documento"] , '=')
							->execute();
				if($update > 0)
				{
					$query = $database->select('registrados_an', 'an');
					$idregistro = $query->fields('an', ['idregistro'])
										->condition('an.tipodocumento', $arrayFields["tipodocumento"] , '=')
										->condition('an.documento', $arrayFields["documento"] , '=')
										->execute()->fetchField();
					
					$obj["status"] = "ok";
					$obj["id"] = $idregistro;
					$obj["operation"] = "updated";
					$obj["msg"] = "Registro creado en la base de datos";
					$arrayreturn = json_encode($obj);
				}else{
					$obj["status"] = "fail";
					$obj["error"] = "Registro no ha podido ser actualizado en la base de datos";
					$arrayreturn = json_encode($obj);
				}
			}else{
				$insert = $database->insert('registrados_an')->fields($arrayFields)->execute();
				if($insert)
				{
					$obj["status"] = "ok";
					$obj["operation"] = "create";
					$obj["id"] = $insert;
					$obj["msg"] = "Registro creado en la base de datos";
					$arrayreturn = json_encode($obj);
				}else{
					$obj["status"] = "fail";
					$obj["error"] = "Registro no ha podido ser creado en la base de datos";
					$arrayreturn = json_encode($obj);
				}
			}
			
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "No se ha podido almacenar el registro de adelanto de nómina";
			$arrayreturn = json_encode($obj);
		}

		return $arrayreturn;
	}


	public function crearRegistroSolicitud($idregistro,$arrayRegistro,$mareiguaResponse,$montoMaximoAprobado,$empresaSel)
	{
		$arrayreturn = [];
		$arrayFields = [];
		if(!empty($arrayRegistro) and is_numeric($idregistro))
		{	
			$database = \Drupal::database();
			
			$arrayFields["idregistro"] = $idregistro;
			$arrayFields["fecha_solicitud"] = date("Y-m-d H:i:s");
			$arrayFields["estado_solicitud"] = "solicitada";
			$arrayFields["envio_notificacion_aprobacion"] = 0;
			$arrayFields["monto_maximo_aprobado"] = $montoMaximoAprobado;
			$arrayFields["valor_solicitado"] = 0;
			$arrayFields["seguros"] = 0;
			$arrayFields["tecnologia"] = 0;
			$arrayFields["iva"] = 0;
			$arrayFields["acepto_terminos"] = 0;

			$arrayFields["mareigua_consulta_id"] = $mareiguaResponse->consulta_id;
			$arrayFields["mareigua_respuesta_id"] = $mareiguaResponse->respuesta_id;
			$arrayFields["mareigua_eps"] = $mareiguaResponse->EPS;
			$arrayFields["mareigua_afp"] = $mareiguaResponse->AFP;
			$arrayFields["mareigua_empresa_tipo_identificacion"] = $empresaSel["tipo_identificacion_aportante_id"];
			$arrayFields["mareigua_empresa_identificacion"] = $empresaSel["numero_identificacion_aportante"];
			$arrayFields["mareigua_razon_social"] = $empresaSel["razón_social_aportante"];
			$arrayFields["mareigua_nivel_riesgo"] = $empresaSel["nivel_riesgo"];
			$arrayFields["mareigua_promedio_ingresos"] = $empresaSel["promedio_ingresos"];
			$arrayFields["mareigua_mediana_ingresos"] = $empresaSel["media_ingresos"];
			$arrayFields["mareigua_maximo"] = $empresaSel["maximo_ingresos"];
			$arrayFields["mareigua_minimo"] = $empresaSel["minimo_ingresos"];
			$arrayFields["mareigua_pendiente"] = $mareiguaResponse->resumen_general_ingresos->pendiente;
			$arrayFields["mareigua_meses_continuidad"] = $mareiguaResponse->resumen_general_ingresos->meses_continuidad;
			$arrayFields["mareigua_cantidad_aportantes"] = $mareiguaResponse->resumen_general_ingresos->cantidad_aportantes;
			$arrayFields["mareigua_respuesta"] = json_encode($mareiguaResponse);
			$arrayFields["estado_general_solicitud"] = "solicitada";

			// Create an object of type Select.
			$estados_solicitud_cerradas = ["rechazada","liquidado","rechazado_empresa"];
			$query = $database->select('adelantos_nomina', 'an');
			$cantidad = $query->condition('an.idregistro', $idregistro , '=')
						->condition('an.estado_general_solicitud', $estados_solicitud_cerradas , 'NOT IN')
						->countQuery()->execute()->fetchField();
			if($cantidad > 0)
			{
				
				$idsolicitud = $database->select('adelantos_nomina', 'an')
											->fields('an', ['idadelanto'])
											->condition('an.idregistro', $idregistro , '=')
											->condition('an.estado_general_solicitud', $estados_solicitud_cerradas , 'NOT IN')
											->execute()->fetchField();

				$obj["status"] = "ok";
				$obj["idsolicitud"] = $idsolicitud;
				$obj["operation"] = "exist";
				$obj["msg"] = "Solicitud existe en la base de datos";
				$arrayreturn = json_encode($obj);
			}else{
				$insert = $database->insert('adelantos_nomina')->fields($arrayFields)->execute();
				if($insert)
				{
					$obj["status"] = "ok";
					$obj["operation"] = "create";
					$obj["idsolicitud"] = $insert;
					$obj["msg"] = "Solicitud creada en la base de datos";
					$arrayreturn = json_encode($obj);
				}else{
					$obj["status"] = "fail";
					$obj["error"] = "Registro no ha podido ser creada en la base de datos";
					$arrayreturn = json_encode($obj);
				}
			}
			
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "No se ha podido almacenar la solicitud adelanto de nómina";
			$arrayreturn = json_encode($obj);
		}

		return $arrayreturn;
	}

	public function validationRules($arrayEmpresa,$valorminimo,$minMesesCotizados,$rangoSalario,$registraPagos6Meses,$eps_sel,$afp_sel,$usuario_preexistente=false,$idregistro=0)
	{
		$arrayRangoSalario = explode("-",$rangoSalario);

		if(count($arrayRangoSalario)==1)
		{
			$arrayRangoSalario[1]=99000000;
		}
		
		if($arrayEmpresa["salario_cotizado"] < $valorminimo)
		{
			$obj["status"] = "denied";
			$obj["msg"] = "Ingresos no pueden ser inferiores que el salario mínimo.";
			$mensaje = json_encode($obj["msg"])." ".json_encode($arrayEmpresa);
            \Drupal::logger('linxecredit')->notice($mensaje);
			$obj["msg"] = "En el proceso de validación uno o más datos no coincidieron, verifica tu información y vuelve a intentarlo.";
		
			$obj["error_code"] = 99;
			$obj["title"] = "¡ALGO PASÓ!";

			
		}else if($arrayEmpresa["num_meses_cotizados"] < $minMesesCotizados)
		{
			$obj["status"] = "denied";
			$obj["msg"] = "El número de meses cotizado no puede ser inferior a ".$minMesesCotizados;
			$mensaje = json_encode($obj["msg"])." ".json_encode($arrayEmpresa);
            \Drupal::logger('linxecredit')->notice($mensaje);
			$obj["msg"] = "En el proceso de validación uno o más datos no coincidieron, verifica tu información y vuelve a intentarlo.";
			$obj["error_code"] = 98;
			$obj["title"] = "¡ALGO PASÓ!";
		}else if( !($arrayEmpresa["salario_cotizado"] >= $arrayRangoSalario[0] && $arrayEmpresa["salario_cotizado"] <= $arrayRangoSalario[1]) )
		{
			$obj["status"] = "denied";
			$obj["msg"] = "Su rango salarial no es correcto.";
			$mensaje = json_encode($obj["msg"])." ".json_encode($arrayEmpresa);
            \Drupal::logger('linxecredit')->notice($mensaje);
			$obj["msg"] = "En el proceso de validación uno o más datos no coincidieron, verifica tu información y vuelve a intentarlo.";
			$obj["error_code"] = 97;
			$obj["title"] = "¡ALGO PASÓ!";
		}else if($registraPagos6Meses!="si")
		{
			$obj["status"] = "denied";
			$obj["msg"] = "Se requiere haber cotizado los últimos ".$minMesesCotizados." meses";
			$mensaje = json_encode($obj["msg"])." ".json_encode($arrayEmpresa);
            \Drupal::logger('linxecredit')->notice($mensaje);
			$obj["msg"] = "En el proceso de validación uno o más datos no coincidieron, verifica tu información y vuelve a intentarlo.";
			$obj["error_code"] = 96;
			$obj["title"] = "¡ALGO PASÓ!";
		}else if( $this->matchEPS($eps_sel,$arrayEmpresa["EPS"],$usuario_preexistente,$idregistro) == false)
		{
			$obj["status"] = "denied";
			$obj["msg"] = "EPS seleccionada no coincide con la reportada en su planilla de seguridad social";
			$mensaje = json_encode($obj["msg"])." ".json_encode($arrayEmpresa);
            \Drupal::logger('linxecredit')->notice($mensaje);
			$obj["msg"] = "En el proceso de validación uno o más datos no coincidieron, verifica tu información y vuelve a intentarlo.";
			$obj["error_code"] = 95;
			$obj["title"] = "¡ALGO PASÓ!";
			
		}else if( $this->matchAFP($afp_sel,$arrayEmpresa["AFP"],$usuario_preexistente,$idregistro) == false)
		{
			$obj["status"] = "denied";
			$obj["msg"] = "AFP seleccionada no coincide con la reportada en su planilla de seguridad social";
			$mensaje = json_encode($obj["msg"])." ".json_encode($arrayEmpresa);
            \Drupal::logger('linxecredit')->notice($mensaje);
			$obj["msg"] = "En el proceso de validación uno o más datos no coincidieron, verifica tu información y vuelve a intentarlo.";
			$obj["error_code"] = 95;
			$obj["title"] = "¡ALGO PASÓ!";
		}else{
			$obj["status"] = "accepted";
			$obj["msg"] = "El usuario ha cumplido con los requisitos mínimos para el Adelanto de Nómina";
			$obj["error_code"] = 0;
			$obj["title"] = "USUARIO ACEPTADO";
		}

		
		$arrayreturn = json_encode($obj);
		return $arrayreturn;
	}

	public function matchEPS($eps_sel,$eps_mareigua,$usuario_preexistente=false,$idregistro=0)
	{
		if($eps_mareigua!="" && $eps_mareigua!=null )
		{
			if($eps_sel!=$eps_mareigua)
			{
				if($usuario_preexistente){
					//se incluye la logica cuando el usuario ya existia entonces no se valida esto y se actualiza la eps y afp con los últimos que traiga mareigua
					if(is_numeric($idregistro) && $idregistro!=0  )
					{
						$arrayFields["eps"]=$eps_mareigua;
						$database = \Drupal::database();
						$update = $database->update('registrados_an')
									->fields($arrayFields)
									->condition('idregistro', $idregistro , '=')
									->execute();
					}
					//
					return true;
				}else{
					
					$len_str1= strlen($eps_sel);
					$len_str2= strlen($eps_mareigua);
					
					if($len_str1>=$len_str2)
					{
						$pos = strpos($eps_sel, $eps_mareigua);
					}else{
						$pos = strpos($eps_mareigua, $eps_sel);
					}
					if ($pos === false) {
						
						//si ninguna de las anteriores validaciones funcionó
						//verificamos ahora si la eps traida por mareigua coincide con alguno de los alias de la eps seleccionada por el usuario

						$database = \Drupal::database();
						$query = $database->select('linxe_seguridadsocial', 'iss');
						$resultISS = $query->condition('iss.tipo_entidad', "eps")
										->condition('iss.nombre', $eps_sel )
										->condition('iss.estado', 1 )
										->fields('iss')
										->execute();
						$bandeEncontrada = false;
						foreach ($resultISS as $key=>$record) {
							$arrayAlias = explode(",",$record->alias);
							foreach($arrayAlias as $alias)
							{
								if( $alias == $eps_mareigua )
								{
									$bandeEncontrada = true;
									break;
								}
							}
							if($bandeEncontrada)
								break;
						}

						return $bandeEncontrada;
					} else {
						return true;
					}

				}
				
			}else
				return true;
		}else
			return true;
		
	}

	public function matchAFP($afp_sel,$afp_mareigua,$usuario_preexistente=false,$idregistro=0)
	{
		if($afp_mareigua!="" && $afp_mareigua!=null )
		{
			if($afp_sel!=$afp_mareigua)
			{
				if($usuario_preexistente){
					//se incluye la logica cuando el usuario ya existia entonces no se valida esto y se actualiza la eps y afp con los últimos que traiga mareigua
					if(is_numeric($idregistro) && $idregistro!=0  )
					{
						$arrayFields["afp"]=$afp_mareigua;
						$database = \Drupal::database();
						$update = $database->update('registrados_an')
									->fields($arrayFields)
									->condition('idregistro', $idregistro , '=')
									->execute();
					}
					//
					return true;
				}else{
					$len_str1= strlen($afp_sel);
					$len_str2= strlen($afp_mareigua);
					
					if($len_str1>=$len_str2)
					{
						$pos = strpos($afp_sel, $afp_mareigua);
					}else{
						$pos = strpos($afp_mareigua, $afp_sel);
					}
					if ($pos === false) {
						//si ninguna de las anteriores validaciones funcionó
						//verificamos ahora si la eps traida por mareigua coincide con alguno de los alias de la eps seleccionada por el usuario

						$database = \Drupal::database();
						$query = $database->select('linxe_seguridadsocial', 'iss');
						$resultISS = $query->condition('iss.tipo_entidad', "afp")
										->condition('iss.nombre', $afp_sel )
										->condition('iss.estado', 1 )
										->fields('iss')
										->execute();
						$bandeEncontrada = false;
						foreach ($resultISS as $key=>$record) {
							$arrayAlias = explode(",",$record->alias);
							foreach($arrayAlias as $alias)
							{
								if( $alias == $afp_mareigua )
								{
									$bandeEncontrada = true;
									break;
								}
							}
							if($bandeEncontrada)
								break;
						}

						return $bandeEncontrada;
					} else {
						return true;
					}
				}
				
			}else
				return true;
		}else
			return true;
	}
	
	public function getMontoMaximo($monto)
	{
		$config = \Drupal::config('linxecredit.settings');
		$adelantonomina_rangos = $config->get('linxecredit.adelantonomina_rangos');
		$adelantonomina_montos_adelanto = $config->get('linxecredit.adelantonomina_montos_adelanto');
		$adelantonomina_montos_salario = $config->get('linxecredit.adelantonomina_montos_salario');
		$arrayOne = explode(",",$adelantonomina_rangos);
		$arrayDos = explode(",",$adelantonomina_montos_adelanto);
		$arrayTres = explode(",",$adelantonomina_montos_salario);

		$valor_min_an = number_format($arrayOne[0],0,",",".");
		$canti = count($arrayOne);
		$canti2 = count($arrayDos);
		$canti3 = count($arrayTres);
		$valor_max_an = number_format($arrayTres[$canti3-1],0,",",".") ;
		$valor_med_default_an = $arrayTres[1];
		$valor_adelanto_default_an = number_format($arrayDos[1],0,",",".");

		$valor_inicial = 0;
		$monto_maximo = 0;
		$bande = false;
		foreach($arrayOne as $key=>$value)
		{
			if($monto >= $valor_inicial && $monto < $value)
			{
				$bande = true;
				$monto_maximo = $arrayDos[$key];
			}
			$valor_inicial = $value;
		}

		if( $bande == false && $monto_maximo == 0 )
		{
			$monto_maximo = $arrayDos[$canti2 - 1];
		}

		return $monto_maximo;

	}

	public function createUser($idregistro, $arrayRegistro)
	{
		$language = \Drupal::languageManager()->getCurrentLanguage()->getId();
		
		//busco primero si el usuario ya existe
		$user = user_load_by_name($arrayRegistro["documento"]);
		//$uid = $user->id();

		if(!empty($user))
		{
			//el usuario ya existe
			return true;
		}else{
			$user = \Drupal\user\Entity\User::create();
			//Mandatory settings
			$passwordAleatorio = $this->generateRandomString(10);
			$user->setPassword($passwordAleatorio);
			$user->enforceIsNew();
			$user->setEmail($arrayRegistro["email"]);
			$user->setUsername($arrayRegistro["documento"]); //This username must be unique and accept only a-Z,0-9, - _ @ .

			//Optional settings
			$user->set("init", 'email');
			$user->set("langcode", $language);
			$user->set("preferred_langcode", $language);
			$user->set("preferred_admin_langcode", $language);
			//$user->set("setting_name", 'setting_value');
			$user->activate();
			$user->addRole('user_adelanto');

			//Save user
			$res = $user->save();
			//exit();
			if($res!=false)
			{
				$titleMsg = "LINXE - USUARIO CREADO";
				$mensaje = "<p>Hola ".$arrayRegistro["nombre"]." ".$arrayRegistro["primer_apellido"].", tu usuario fue creado satisfactoriamente.</p><br/>";
				$mensaje .= "<p>Usuario: ".$arrayRegistro["documento"]."</p>";
				$mensaje .= "<p>Contraseña: ".$passwordAleatorio."</p>";
				$emailto = $arrayRegistro["email"];
				$this->sendMail_success($titleMsg,$mensaje,$emailto);
				
				$arrayFields["iduser"]=$user->id();
				$database = \Drupal::database();
				$update = $database->update('registrados_an')
								->fields($arrayFields)
								->condition('idregistro', $idregistro , '=')
								->execute();
				return true;
			}else{
				return false;
			}
		}	
	}

	public function createUserEmpresa($idempresa, $arrayEmpresa)
	{
		$language = \Drupal::languageManager()->getCurrentLanguage()->getId();
		
		//busco primero si el usuario ya existe
		$user = user_load_by_name($arrayEmpresa["identificacion"]);
		//$uid = $user->id();

		if(!empty($user))
		{
			//el usuario ya existe
			return true;
		}else{
			$user = \Drupal\user\Entity\User::create();
			//Mandatory settings
			$passwordAleatorio = $this->generateRandomString(10);
			$user->setPassword($passwordAleatorio);
			$user->enforceIsNew();
			$user->setEmail($arrayEmpresa["email"]);
			$user->setUsername($arrayEmpresa["identificacion"]); //This username must be unique and accept only a-Z,0-9, - _ @ .

			//Optional settings
			$user->set("init", 'email');
			$user->set("langcode", $language);
			$user->set("preferred_langcode", $language);
			$user->set("preferred_admin_langcode", $language);
			//$user->set("setting_name", 'setting_value');
			$user->activate();
			$user->addRole('empresa_linxe');

			//Save user
			$res = $user->save();
			//exit();
			if($res!=false)
			{
				$titleMsg = "LINXE - USUARIO EMPRESA CREADO";
				$mensaje = "<p>Hola ".$arrayEmpresa["razon_social"]." , tu usuario fue creado satisfactoriamente.</p><br/>";
				$mensaje .= "<p>Usuario: ".$arrayEmpresa["identificacion"]."</p>";
				$mensaje .= "<p>Contraseña: ".$passwordAleatorio."</p>";
				$emailto = $arrayEmpresa["email"];
				$this->sendMail_success($titleMsg,$mensaje,$emailto);
				
				$arrayFields["iduser"]=$user->id();
				$database = \Drupal::database();
				$update = $database->update('empresas')
								->fields($arrayFields)
								->condition('idempresa', $idempresa , '=')
								->execute();
				return true;
			}else{
				return false;
			}
		}	
	}

	//Método con str_shuffle() 
	function generateRandomString($length = 10) { 
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
	} 

	public function getUserDataByID($uid){
		if(is_numeric($uid))
		{
			$arrayReturn = [];
			$database = \Drupal::database();
			// registro
			$query = $database->select('registrados_an', 'an');
			$result = $query->condition('an.iduser', $uid , '=')
						->fields('an')
						->execute();
			foreach ($result as $record) {
				foreach($record as $key => $value)
				{
					$arrayReturn[$key] = $value;
				}
			}
			//solicitud
			$estados_solicitud_abierta = ["rechazada","liquidado","rechazado_empresa"]; //estados finales de una solicitud
			$query = $database->select('adelantos_nomina', 'an');
			$result = $query->fields('an')->condition('an.idregistro', $arrayReturn["idregistro"], '=')
						->condition('an.estado_general_solicitud', $estados_solicitud_abierta , 'NOT IN')
						->execute();
			foreach ($result as $record) {
				foreach($record as $key => $value)
				{
					$arrayReturn[$key] = $value;
				}
			}
			$obj["status"] = "ok";
			$obj["msg"] = "Array return";
			$obj["userData"] = $arrayReturn;

			$arrayreturn = json_encode($obj);
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "UID not valid";
			$arrayreturn = json_encode($obj);
		}
		return $arrayreturn;
	}

	public function getUserDataByIdRegistro($idregistro){
		if(is_numeric($idregistro))
		{
			$arrayReturn = [];
			$database = \Drupal::database();
			// registro
			$query = $database->select('registrados_an', 'an');
			$result = $query->condition('an.idregistro', $idregistro , '=')
						->fields('an')
						->execute();
			foreach ($result as $record) {
				foreach($record as $key => $value)
				{
					$arrayReturn[$key] = $value;
				}
			}
			//solicitud
			//$estados_solicitud_abierta = ["solicitada","crear_girador","crear_pagare","generacion_otp"];
			//$estados_solicitud_abierta = ["desembolsado","en_proceso_liquidacion","rechazada","liquidado","rechazado_empresa"];
			$estados_solicitud_abierta = ["rechazada","liquidado","rechazado_empresa"]; //estados finales de una solicitud
			//$estados_solicitud_abierta = ["desembolso","cancelado","pagado"];
			$query = $database->select('adelantos_nomina', 'an');
			$result = $query->fields('an')->condition('an.idregistro', $arrayReturn["idregistro"], '=')
						->condition('an.estado_general_solicitud', $estados_solicitud_abierta , 'NOT IN')
						->execute();
			foreach ($result as $record) {
				foreach($record as $key => $value)
				{
					if($key!="idempresa")
						$arrayReturn[$key] = $value;
				}
			}
			$obj["status"] = "ok";
			$obj["msg"] = "Array return";
			$obj["userData"] = $arrayReturn;

			$arrayreturn = json_encode($obj);
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "idregistro not valid";
			$arrayreturn = json_encode($obj);
		}
		return $arrayreturn;
	}

	public function getUserDataByDocument($document){
		if(is_numeric($document))
		{
			$arrayReturn = [];
			$database = \Drupal::database();
			// registro
			$query = $database->select('registrados_an', 'an');
			$result = $query->condition('an.tipodocumento', 1 , '=')
						->condition('an.documento', $document , '=')
						->fields('an')
						->execute();
			foreach ($result as $record) {
				foreach($record as $key => $value)
				{
					$arrayReturn[$key] = $value;
				}
			}
			//solicitud
			//$estados_solicitud_abierta = ["solicitada","crear_girador","crear_pagare","generacion_otp"];
			//$estados_solicitud_abierta = ["desembolsado","en_proceso_liquidacion","rechazada","liquidado","rechazado_empresa"];
			$estados_solicitud_abierta = ["rechazada","liquidado","rechazado_empresa"]; //estados finales de una solicitud
			$query = $database->select('adelantos_nomina', 'an');
			$result = $query->fields('an')->condition('an.idregistro', $arrayReturn["idregistro"], '=')
						->condition('an.estado_general_solicitud', $estados_solicitud_abierta , 'NOT IN')
						->execute();
			foreach ($result as $record) {
				foreach($record as $key => $value)
				{
					$arrayReturn[$key] = $value;
				}
			}
			$obj["status"] = "ok";
			$obj["msg"] = "Array return";
			$obj["userData"] = $arrayReturn;

			$arrayreturn = json_encode($obj);
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "idregistro not valid";
			$arrayreturn = json_encode($obj);
		}
		return $arrayreturn;
	}


	public function validarEmpresaExistenteLibranza($objMareigua){

		$database = \Drupal::database();
		$arrayAportantes = $objMareigua->aportantes;
		// registro
		$query = $database->select('empresas', 'em');
		$arrayAct = [$actividad,"ambos"];
		$result = $query->condition('em.convenio_tipoproducto', "libranza", '=')
					->condition('em.estado_convenio', "aceptado" , '=')
					->fields('em')
					->execute();
		//print_r($result);
		foreach ($result as $key=>$record) {
			//echo $record->convenio_actividadecon."<br/>";
			$nitActual = $record->identificacion;
			
			foreach($arrayAportantes as $aportante)
			{
				if($aportante->tipo_identificacion_aportante_id == 3 && $aportante->numero_identificacion_aportante == $nitActual) //nit
				{
					return true;
				}
			}
		}
		/*
		
		foreach($arrayEmpresas as $key => $value)
        {
          if($key!="Otra"){
            $arrayKey = explode("|",$key);
            $nitActual = $arrayKey[1];
			
			foreach($arrayAportantes as $aportante)
			{
				if($aportante->tipo_identificacion_aportante_id == 3 && $aportante->numero_identificacion_aportante == $nitActual) //nit
				{
					return true;
				}
			}
			
          }
          
		}*/
		return false;
	}

	public function seleccionarMejorEmpresa($objMareigua){
		$arrayAportantes = $objMareigua->aportantes;
		$arrayEmpresa["tipo_identificacion_aportante_id"] = "";
		$arrayEmpresa["numero_identificacion_aportante"] = "";
		$arrayEmpresa["razón_social_aportante"] = "";
		$arrayEmpresa["nivel_riesgo"] = 4;
		$arrayEmpresa["num_meses_cotizados"] = 0;
		$arrayEmpresa["salario_cotizado"] = 0;
		$arrayEmpresa["promedio_ingresos"] = 0;
		$arrayEmpresa["media_ingresos"] = 0;
		$arrayEmpresa["ultimo_mes_cotizacion"] = "";
		$anioActual = date("Y");
		$mesActual = date("m");
		

		foreach($arrayAportantes as $aportante)
		{
			switch($aportante->nivel_riesgo){
				case "Alto": $nivel_riesgo=3; break;
				case "Medio": $nivel_riesgo=2; break;
				case "Bajo": $nivel_riesgo=1; break;
				default: $nivel_riesgo=1; 
			}
			$meses_cotizacion = count($aportante->resultado_pagos);
			$fechaEmpresaSeleccionada = strtotime($arrayEmpresa["ultimo_mes_cotizacion"]) ;

			//$fechaEmpresaActual = strtotime($aportante->resultado_pagos[0]->ano_periodo_validado."-".$aportante->resultado_pagos[0]->mes_periodo_validado."-01") ;
			$fechaEmpresaActual = "";
			foreach($aportante->resultado_pagos as $resultado_pago)
			{
				if($resultado_pago->realizo_pago==true)
				{
					$fechaEmpresaActual = strtotime($resultado_pago->ano_periodo_validado."-".$resultado_pago->mes_periodo_validado."-01") ;
					break;
				}
			}

			if($fechaEmpresaSeleccionada == "" || $fechaEmpresaSeleccionada < $fechaEmpresaActual)
			{
				$arrayEmpresa = $this->setEmpresa($aportante,$nivel_riesgo);
			}else if($fechaEmpresaSeleccionada == $fechaEmpresaActual){
				if($nivel_riesgo < $arrayEmpresa["nivel_riesgo"] )
				{
					$arrayEmpresa = $this->setEmpresa($aportante,$nivel_riesgo);
				}elseif($nivel_riesgo == $arrayEmpresa["nivel_riesgo"]){
					if( $meses_cotizacion > $arrayEmpresa["num_meses_cotizados"] )
					{
						$arrayEmpresa = $this->setEmpresa($aportante,$nivel_riesgo);
					}else if( $meses_cotizacion == $arrayEmpresa["num_meses_cotizados"] ){
						if( $aportante->minimo >= $arrayEmpresa["salario_cotizado"] )
						{
							$arrayEmpresa = $this->setEmpresa($aportante,$nivel_riesgo);
						}
					}
				}
			}
				
		}

		$arrayEmpresa["EPS"] = $objMareigua->EPS;
		$arrayEmpresa["AFP"] = $objMareigua->AFP;
		
		return $arrayEmpresa;
	}

	public function seleccionarMareiguaEmpresaByNIT($objMareigua,$nitCurrent){
		$arrayAportantes = $objMareigua->aportantes;
		$arrayEmpresa["tipo_identificacion_aportante_id"] = "";
		$arrayEmpresa["numero_identificacion_aportante"] = "";
		$arrayEmpresa["razón_social_aportante"] = "";
		$arrayEmpresa["nivel_riesgo"] = 4;
		$arrayEmpresa["num_meses_cotizados"] = 0;
		$arrayEmpresa["salario_cotizado"] = 0;
		$arrayEmpresa["promedio_ingresos"] = 0;
		$arrayEmpresa["media_ingresos"] = 0;
		$arrayEmpresa["ultimo_mes_cotizacion"] = "";
		

		$bandeExiste = false;

		foreach($arrayAportantes as $aportante)
		{
			switch($aportante->nivel_riesgo){
				case "Alto": $nivel_riesgo=3; break;
				case "Medio": $nivel_riesgo=2; break;
				case "Bajo": $nivel_riesgo=1; break;
				default: $nivel_riesgo=1; 
			}
			
			if($aportante->numero_identificacion_aportante == $nitCurrent)
			{
				$arrayEmpresa = $this->setEmpresa($aportante,$nivel_riesgo);
				
				$bandeExiste=true;
				break;
			}
		}

		$arrayEmpresa["EPS"] = $objMareigua->EPS;
		$arrayEmpresa["AFP"] = $objMareigua->AFP;

		if($bandeExiste){
			return $arrayEmpresa;
		}else
			return $bandeExiste;
	}

	public function setEmpresa($aportante,$nivel_riesgo){
		$arrayEmpresa["tipo_identificacion_aportante_id"] = $aportante->tipo_identificacion_aportante_id;
		$arrayEmpresa["numero_identificacion_aportante"] = $aportante->numero_identificacion_aportante;
		$arrayEmpresa["razón_social_aportante"] = $aportante->razón_social_aportante;
		$arrayEmpresa["nivel_riesgo"] = $nivel_riesgo ;
		$arrayEmpresa["num_meses_cotizados"] = count($aportante->resultado_pagos);
		//$arrayEmpresa["salario_cotizado"] = $aportante->resultado_pagos[0]->ingresos;
		$arrayEmpresa["salario_cotizado"] = $aportante->minimo;
		$arrayEmpresa["promedio_ingresos"] = $aportante->promedio_ingresos;
		$arrayEmpresa["media_ingresos"] = $aportante->media_ingresos;
		$arrayEmpresa["minimo_ingresos"] = $aportante->minimo;
		$arrayEmpresa["maximo_ingresos"] = $aportante->maximo;

		

		foreach($aportante->resultado_pagos as $resultado_pago)
		{
			if($resultado_pago->realizo_pago==true)
			{
				$arrayEmpresa["ultimo_mes_cotizacion"] = $resultado_pago->ano_periodo_validado."-".$resultado_pago->mes_periodo_validado."-01" ;
				break;
			}
		}
		
		return $arrayEmpresa;
	}

	public function getMisAdelantos($dataFields){
		
		$arrayReturn = [];
		$database = \Drupal::database();
		//registro
		$query = $database->select('registrados_an', 'an');
		$idregistro = $query->fields('an', ['idregistro'])
							->condition('an.tipodocumento', $dataFields["tipoId"] , '=')
							->condition('an.documento', $dataFields["numId"] , '=')
							->execute()->fetchField();
		
		//solicitud aprobadas
		$query = $database->select('adelantos_nomina', 'an');
		$result = $query->fields('an')->condition('an.idregistro', $idregistro , '=')
					->condition('an.estado_solicitud', "aprobada" , '=')
					->execute()->fetchAll();
		
		/*
		foreach ($result as $record) {
			//echo $record->estado_solicitud;
			$arrayReturn[] = $record;
		}
		*/
		//print_r($arrayReturn);
		//exit();
		return $result;
	}


	public function setSeleccionAdelanto($idadelanto,$montoSeleccionado,$configuration)
	{
		$arrayreturn = [];
		$arrayFields = [];
		if(is_numeric($montoSeleccionado) && $montoSeleccionado>0)
		{	
			$cargo_administracion_adelanto = $configuration["cargo_administracion_adelanto"] ;
			$cargo_tecnologia_adelanto = $configuration["cargo_tecnologia_adelanto"] ;
			$seguro_adelanto = $configuration["seguro_adelanto"] ;
			$iva_adelanto = $configuration["iva_adelanto"] ;

			$cuota_administracion = $cargo_administracion_adelanto;
			$cuota_tecnologia = $cargo_tecnologia_adelanto;
			$cargo_seguros = ($montoSeleccionado * $seguro_adelanto) / 100;
			$cargo_iva = ($cuota_tecnologia / 100) * $iva_adelanto;

			$database = \Drupal::database();
			
			//$arrayFields["idadelanto"] = $idadelanto;
			$arrayFields["valor_solicitado"] = $montoSeleccionado;
			$arrayFields["administracion"] = $cuota_administracion;
			$arrayFields["seguros"] = $cargo_seguros;
			$arrayFields["tecnologia"] = $cuota_tecnologia;
			$arrayFields["iva"] = $cargo_iva ;
			$arrayFields["acepto_terminos"] = 1;
			$arrayFields["estado_solicitud"] = "aprobada";
			$arrayFields["estado_general_solicitud"] = "seleccion_monto";
			$arrayFields["fecha_hora_acepta_terminos"] = date("Y-m-d H:i:s");
			$arrayFields["ip_address"] = $_SERVER['REMOTE_ADDR'];
			

			$update = $database->update('adelantos_nomina')
						->fields($arrayFields)
						->condition('idadelanto', $idadelanto, '=')
						->execute();
			if($update > 0)
			{
				$idsolicitud = $database->select('adelantos_nomina', 'an')
								->fields('an', ['idadelanto'])
								->condition('an.idadelanto', $idadelanto , '=')
								->condition('estado_general_solicitud', "solicitada", '=')
								->execute()->fetchField();
				
				$obj["status"] = "ok";
				$obj["idsolicitud"] = $idsolicitud;
				$obj["operation"] = "updated";
				$obj["msg"] = "Solicitud actualizada en la base de datos";
				$arrayreturn = json_encode($obj);
			}else{
				$obj["status"] = "fail";
				$obj["error"] = "Registro no ha podido ser actualizado en la base de datos";
				$arrayreturn = json_encode($obj);
			}
			
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "No se ha podido almacenar la solicitud adelanto de nómina";
			$arrayreturn = json_encode($obj);
		}

		return $arrayreturn;
	}

	public function setRespuestaGiradorDeceval($idadelanto,$respuestaDeceval,$cuentaGirador)
	{
		$arrayreturn = [];
		$arrayFields = [];
		if(is_numeric($cuentaGirador))
		{	
			$database = \Drupal::database();
			
			
			$arrayFields["respuesta_deceval_girador"] = $respuestaDeceval;
			$arrayFields["cuentaGirador"] = $cuentaGirador;
			$arrayFields["estado_solicitud"] = "aprobada";
			$arrayFields["estado_general_solicitud"] = "crear_girador";

			$update = $database->update('adelantos_nomina')
						->fields($arrayFields)
						->condition('idadelanto', $idadelanto, '=')
						->execute();
			if($update > 0)
			{
				$obj["status"] = "ok";
				$obj["idadelanto"] = $idadelanto;
				$obj["operation"] = "updated";
				$obj["msg"] = "Solicitud actualizada en la base de datos";
				$arrayreturn = json_encode($obj);
			}else{
				$obj["status"] = "fail";
				$obj["error"] = "Registro no ha podido ser actualizado en la base de datos";
				$arrayreturn = json_encode($obj);
			}
			
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "No se ha podido almacenar la solicitud adelanto de nómina";
			$arrayreturn = json_encode($obj);
		}

		return $arrayreturn;
	}


	public function setRespuestaPagareDeceval($idadelanto,$respuestaDeceval,$numpagareentidad,$iddocumentopagare)
	{
		$arrayreturn = [];
		$arrayFields = [];
		if(is_numeric($idadelanto))
		{	
			$database = \Drupal::database();
			
			
			$arrayFields["respuesta_deceval_creacion_pagare"] = $respuestaDeceval;
			$arrayFields["numpagareentidad"] = $numpagareentidad;
			$arrayFields["iddocumentopagare"] = $iddocumentopagare;
			$arrayFields["fecha_hora_creacion_pagare"] = date("Y-m-d H:i:s");
			$arrayFields["estado_general_solicitud"] = "crear_pagare";

			$update = $database->update('adelantos_nomina')
						->fields($arrayFields)
						->condition('idadelanto', $idadelanto, '=')
						->execute();
			if($update > 0)
			{
				$obj["status"] = "ok";
				$obj["idadelanto"] = $idadelanto;
				$obj["operation"] = "updated";
				$obj["msg"] = "Solicitud actualizada en la base de datos";
				$arrayreturn = json_encode($obj);
			}else{
				$obj["status"] = "fail";
				$obj["error"] = "Registro no ha podido ser actualizado en la base de datos";
				$arrayreturn = json_encode($obj);
			}
			
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "No se ha podido almacenar la solicitud adelanto de nómina";
			$arrayreturn = json_encode($obj);
		}

		return $arrayreturn;
	}

	public function sendOTP($idregistro,$idadelanto,$twilio_sid,$twilio_token,$twilio_phonenumber)
	{
		
		$arrayreturn = [];
		$arrayFields = [];
		$database = \Drupal::database();

		if(is_numeric($idadelanto))
		{	
			// registro
			$query = $database->select('registrados_an', 'an');
			$result = $query->condition('an.idregistro', $idregistro , '=')
						->fields('an')
						->execute();

			$celular = $database->select('registrados_an', 'an')
						->fields('an', ['celular'])
						->condition('an.idregistro', $idregistro , '=')
						->execute()->fetchField();



			$database = \Drupal::database();
			$digits = 8;
			$otp_code = str_pad(Rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
			$date_otp_code = date("Y-m-d H:i:s");
			
			$arrayFields["otp"] = $otp_code;
			$arrayFields["fecha_hora_generacion_otp"] = $date_otp_code;
			$arrayFields["estado_general_solicitud"] = "generacion_otp";

			$update = $database->update('adelantos_nomina')
						->fields($arrayFields)
						->condition('idadelanto', $idadelanto, '=')
						->execute();
			if($update > 0)
			{
				//Envío de otp al usuario
				$client = new Client($twilio_sid, $twilio_token);

				// Use the client to do fun stuff like send text messages!
				$message = $client->messages->create(
					// the number you'd like to send the message to
					'+57'.$celular,
					[
						// A Twilio phone number you purchased at twilio.com/console
						//'from' => '+15005550006',
						'from' => '+'.$twilio_phonenumber,
						// the body of the text message you'd like to send
						'body' => "Linxe Adelanto Nómina - Digita el siguiente código ".$otp_code." para continuar el proceso de firmar tu pagaré electrónicamente"
					]
				);

				//print($message->sid);

				$obj["status"] = "ok";
				$obj["operation"] = "updated";
				$obj["sid"] = $message->sid;
				$obj["msg"] = "Solicitud actualizada en la base de datos";
				$arrayreturn = json_encode($obj);
			}else{
				$obj["status"] = "fail";
				$obj["error"] = "Registro no ha podido ser actualizado en la base de datos";
				$arrayreturn = json_encode($obj);
			}
			
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "No se ha podido almacenar la solicitud adelanto de nómina";
			$arrayreturn = json_encode($obj);
		}

		return $arrayreturn;
	}

	public function setRespuestaFirmaPagareDeceval($idadelanto,$respuestaDeceval)
	{
		$arrayreturn = [];
		$arrayFields = [];
		if(is_numeric($idadelanto))
		{	
			$database = \Drupal::database();
			
			
			$arrayFields["respuesta_deceval_firmar_pagare"] = $respuestaDeceval;
			$arrayFields["fecha_hora_firma_pagare"] = date("Y-m-d H:i:s");
			$arrayFields["estado_general_solicitud"] = "firma_pagare";

			$update = $database->update('adelantos_nomina')
						->fields($arrayFields)
						->condition('idadelanto', $idadelanto, '=')
						->execute();
			if($update > 0)
			{
				$obj["status"] = "ok";
				$obj["idadelanto"] = $idadelanto;
				$obj["operation"] = "updated";
				$obj["msg"] = "Solicitud actualizada en la base de datos";
				$arrayreturn = json_encode($obj);
			}else{
				$obj["status"] = "fail";
				$obj["error"] = "Registro no ha podido ser actualizado en la base de datos";
				$arrayreturn = json_encode($obj);
			}
			
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "No se ha podido almacenar la solicitud adelanto de nómina";
			$arrayreturn = json_encode($obj);
		}

		return $arrayreturn;
	}

	public function setDatosEmpresa($idadelanto,$nombre,$apellido,$telefono,$email,$idempresa,$ciudad="")
	{
		$arrayreturn = [];
		$arrayFields = [];
		$arrayEmpresa = [];
		if(is_numeric($idadelanto))
		{	
			$database = \Drupal::database();
			//actualización contacto en la información de la empresa
			$arrayEmpresa["contacto_nombres"]= $nombre;
			$arrayEmpresa["contacto_apellidos"]= $apellido;
			$arrayEmpresa["telefono"]= $telefono;
			$arrayEmpresa["email"]= $email;
			$arrayEmpresa["ciudad"]= $ciudad;

			$update = $database->update('empresas')
						->fields($arrayEmpresa)
						->condition('idempresa', $idempresa, '=')
						->execute();
			if($update > 0)
			{
				//actualización contacto en la solicitud de adelanto
				$arrayFields["contacto_empresa_nombre"] = $nombre;
				$arrayFields["contacto_empresa_apellido"] = $apellido;
				$arrayFields["contacto_empresa_telefono"] = $telefono;
				$arrayFields["contacto_empresa_email"] = $email;
				$arrayFields["contacto_empresa_ciudad"] = $ciudad;
				$arrayFields["estado_general_solicitud"] = "validacion_desembolso";
				$arrayFields["fecha_hora_datosempresa"] = date("Y-m-d H:i:s");

				$update = $database->update('adelantos_nomina')
							->fields($arrayFields)
							->condition('idadelanto', $idadelanto, '=')
							->execute();
				if($update > 0)
				{
					$obj["status"] = "ok";
					$obj["idadelanto"] = $idadelanto;
					$obj["operation"] = "updated";
					$obj["msg"] = "Solicitud actualizada en la base de datos";
					$arrayreturn = json_encode($obj);
				}else{
					$obj["status"] = "fail";
					$obj["error"] = "Los datos de tu empresa no han podido ser guardados";
					$arrayreturn = json_encode($obj);
				}
			}else{
				$obj["status"] = "fail";
				$obj["error"] = "Los datos de tu empresa no han podido ser guardados.";
				$arrayreturn = json_encode($obj);
			}			
			
			
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "No se ha podido almacenar la solicitud adelanto de nómina";
			$arrayreturn = json_encode($obj);
		}

		return $arrayreturn;
	}

	public function setDatosBancarios($idadelanto,$banco,$tipocta,$numcta)
	{	
		$arrayreturn = [];
		$arrayFields = [];
		$arrayEmpresa = [];
		if(is_numeric($idadelanto))
		{	
			$database = \Drupal::database();
			//actualización contacto en la información de la empresa
			$arrayBco["tipo_cuenta"]= $tipocta;
			$arrayBco["numero_cuenta"]= $numcta;
			$arrayBco["banco"]= $banco;
			$arrayBco["estado_general_solicitud"] = "validacion_desembolso";
			$arrayBco["fecha_hora_datosempresa"] = date("Y-m-d H:i:s");

			$update = $database->update('adelantos_nomina')
						->fields($arrayBco)
						->condition('idadelanto', $idadelanto, '=')
						->condition('estado_general_solicitud', "seleccion_monto", '=')
						->execute();

			if($update > 0)
			{
				$obj["status"] = "ok";
				$obj["idadelanto"] = $idadelanto;
				$obj["operation"] = "updated";
				$obj["msg"] = "Solicitud actualizada en la base de datos";
				$arrayreturn = json_encode($obj);
			}else{
				$obj["status"] = "fail";
				$obj["error"] = "Los datos bancarios no han podido ser guardados";
				$arrayreturn = json_encode($obj);
			}
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "No se ha podido guardar la información bancaria";
			$arrayreturn = json_encode($obj);
		}

		return $arrayreturn;
	}

	public function setActualizaFechaNac($idregistro,$fecha_nacimiento)
	{
		$arrayreturn = [];
		if(is_numeric($idregistro))
		{	
			$database = \Drupal::database();
			//actualización contacto en la información de la empresa
			$arrayFields["fecha_nacimiento"]= $fecha_nacimiento;

			$update = $database->update('registrados_an')
						->fields($arrayFields)
						->condition('idregistro', $idregistro, '=')
						->execute();

			if($update > 0)
			{
				$obj["status"] = "ok";
				$obj["idregistro"] = $idregistro;
				$obj["operation"] = "updated";
				$obj["msg"] = "Registro actualizado en la base de datos";
				$arrayreturn = json_encode($obj);
			}else{
				$obj["status"] = "fail";
				$obj["error"] = "La fecha de nacimiento no han podido ser guardada";
				$arrayreturn = json_encode($obj);
			}
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "La fecha de nacimiento no han podido ser guardada";
			$arrayreturn = json_encode($obj);
		}

		return $arrayreturn;
	}

	public function getEmpresasGeneral(){
		
		$arrayReturn = [];
		$database = \Drupal::database();
		// registro
		$query = $database->select('empresas', 'em');
		$result = $query->condition('em.estado_convenio', 'aceptado', '=')
					->fields('em')
					->execute();

		return $result;
	}


	public function validarCrearEmpresaAN($empresaSel){
		
		$arrayReturn = [];
		$database = \Drupal::database();
		// registro
		$query = $database->select('empresas', 'em');
		$result = $query->condition('em.tipo_identificacion', 1, '=')
					->condition('em.identificacion', $empresaSel["numero_identificacion_aportante"], '=')
					->condition('em.convenio_tipoproducto', "adelanto" , '=')
					->fields('em')
					->execute();

		$bandeRespuesta = "crear";

		foreach ($result as $key=>$record) {
			//echo $record->convenio_actividadecon."<br/>";
			$nitActual = $record->identificacion;
			$estado_convenio = $record->estado_convenio;
			$convenio_tipoproducto = $record->convenio_tipoproducto;
			$idempresa = $record->idempresa;
			if($estado_convenio=="aceptado" )
			{
				$bandeRespuesta = "ya_creada_convenio";
				break;
			}if($estado_convenio=="pendiente")
			{
				$bandeRespuesta = "ya_creada_pendiente";
				break;
			}else if($estado_convenio=="rechazado"){
				$bandeRespuesta = "convenio_rechazado";
				break;
			}
			
		}

		switch($bandeRespuesta)
		{
			case "convenio_rechazado":
				$arrayReturn["status"]="fail";
				$arrayReturn["msg"]="convenio_rechazado";
				break;
			case "ya_creada_convenio":
				$arrayReturn["status"]="ok";
				$arrayReturn["msg"]="ya_creada_convenio";
				$arrayReturn["idempresa"]=$idempresa;
				break;
			case "ya_creada_pendiente":
				$arrayReturn["status"]="ok";
				$arrayReturn["msg"]="ya_creada_pendiente";
				$arrayReturn["idempresa"]=$idempresa;
				break;
			default:
				//se debe crear la empresa
				$arrayFields = [];
				$arrayFields["tipo_identificacion"]=1;
				$arrayFields["identificacion"]=$empresaSel["numero_identificacion_aportante"];
				$arrayFields["razon_social"]=$empresaSel["razón_social_aportante"];
				$arrayFields["convenio_tipoproducto"]="adelanto";
				$arrayFields["convenio_actividadecon"]="empleado"; //cuando es adelanto solo es permitido para empleados
				$arrayFields["estado_convenio"]="pendiente";
				$arrayFields["fecha_creacion"]=date("Y-m-d H:i:s");
				$arrayFields["fecha_actualizacion"]=date("Y-m-d H:i:s");
				
				$insert = $database->insert('empresas')->fields($arrayFields)->execute();
				if($insert)
				{
					$arrayReturn["status"]="ok";
					$arrayReturn["msg"]="empresa_creada";
					$arrayReturn["idempresa"]=$insert;
				}else{
					$arrayReturn["status"] = "fail";
					$arrayReturn["msg"] = "empresa_no_creada";
				}
		}

		return $arrayReturn;
	}

	public function sendMail_error($title,$msg,$emailto)
	{
		$paththeme = base_path().drupal_get_path('theme', 'linxe');
		$dominioactual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            
		$template_str = '
		<div >
			<center>
				<img src="'.$dominioactual.$paththeme.'/images/forms/error.jpg" alt="LINXE" width="130" />
				<h2 class="text1">'.$title.'</h2>
				<div class="mensaje">
				'.$msg.'
				</div>
			</center>
		</div> ';

		$mailManager = \Drupal::service('plugin.manager.mail');
		$module = 'linxecredit';
		$key = 'error_msg';
		$to = $emailto;
		//$params['Bcc'] = $config->get('contactopermodule.emailcc');;
		$params['body'] = $template_str;
		$params['subject'] = $title;
		$langcode = \Drupal::currentUser()->getPreferredLangcode();
		$send = true;
		$result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
		if ($result['result'] !== true) {
			return false;
		}else {
			return true;
		}

	}

	public function sendMail_success($title,$msg,$emailto)
	{
		$paththeme = base_path().drupal_get_path('theme', 'linxe');
		$dominioactual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            
		$template_str = '
		<div >
			<center>
				<img src="'.$dominioactual.$paththeme.'/images/forms/exito.jpg" alt="LINXE"  width="130" />
				<h2 class="text1">'.$title.'</h2>
				<div class="mensaje">
				'.$msg.'
				</div>
			</center>
		</div> ';

		$mailManager = \Drupal::service('plugin.manager.mail');
		$module = 'linxecredit';
		$key = 'success_msg';
		$to = $emailto;
		//$params['Bcc'] = $config->get('contactopermodule.emailcc');;
		$params['body'] = $template_str;
		$params['subject'] = $title;
		$langcode = \Drupal::currentUser()->getPreferredLangcode();
		$send = true;
		$result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
		if ($result['result'] !== true) {
			return false;
		}else {
			return true;
		}

	}

	public function sendMail_acceptTerms($title,$msg,$emailto,$filepath,$filename)
	{
		$paththeme = base_path().drupal_get_path('theme', 'linxe');
		$dominioactual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            
		$template_str = '
		<div >
			<center>
				<img src="'.$dominioactual.$paththeme.'/images/forms/exito.jpg" alt="LINXE"  width="130" />
				<h2 class="text1">'.$title.'</h2>
				<div class="mensaje">
				'.$msg.'
				</div>
			</center>
		</div> ';

		$mailManager = \Drupal::service('plugin.manager.mail');
		$module = 'linxecredit';
		$key = 'success_msg';
		$to = $emailto;
		//$params['Bcc'] = $config->get('contactopermodule.emailcc');;
		$params['body'] = $template_str;
		$params['subject'] = $title;

		//Attaching a file to the email
		$attachment = array(
			'filepath' => $filepath,
			'filename' => $filename,
			'filemime' => 'application/pdf'
		);
		$params['attachments'][] = $attachment;


		$langcode = \Drupal::currentUser()->getPreferredLangcode();
		$send = true;
		$result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
		if ($result['result'] !== true) {
			return false;
		}else {
			return true;
		}

	}

	public function getTermsConditionsHTML($arrayData)
	{
		$html = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
		<HTML>
		
		<HEAD>
			<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
			<META name="generator" content="BCL easyConverter SDK 5.0.210">
			<STYLE type="text/css">
				body {
					margin-top: 0px;
					margin-left: 0px;
					background-image: url(themes/custom/linxe/images/image.png);
				}    
				
				#page_1 {
					position: relative;
					overflow: hidden;
					margin: 0px 0px 12px 0px;
					padding: 0px;
					border: none;
					width: 816px;
				}
				
				#page_1 #p1dimg1 {
					position: absolute;
					top: 0px;
					left: 0px;
					z-index: -1;
					width: 816px;
					height: 1044px;
				}
				
				#page_1 #p1dimg1 #p1img1 {
					width: 816px;
					height: 1044px;
				}
				
				#page_2 {
					position: relative;
					overflow: hidden;
					margin: 0px 0px 12px 0px;
					padding: 0px;
					border: none;
					width: 816px;
				}
				
				#page_2 #p2dimg1 {
					position: absolute;
					top: 0px;
					left: 0px;
					z-index: -1;
					width: 816px;
					height: 1044px;
				}
				
				#page_2 #p2dimg1 #p2img1 {
					width: 816px;
					height: 1044px;
				}
				
				.dclr {
					clear: both;
					float: none;
					height: 1px;
					margin: 0px;
					padding: 0px;
					overflow: hidden;
				}
				
				.ft0 {
					font: bold 19px;
					line-height: 22px;
				}
				
				.ft1 {
					font: bold 16px;
					line-height: 19px;
				}
				
				.ft2 {
					font: 16px;
					line-height: 18px;
				}
				
				.ft3 {
					font: 1px;
					line-height: 1px;
				}
				
				.ft4 {
					font: 13px;
					line-height: 16px;
				}
				
				.ft5 {
					font: 11px;
					line-height: 14px;
				}
				
				.ft6 {
					font: 13px;
					margin-left: 15px;
					line-height: 16px;
				}
				
				.ft7 {
					font: 13px;
					margin-left: 13px;
					line-height: 16px;
				}
				
				.ft8 {
					font: 8px;
					line-height: 10px;
				}
				
				.ft9 {
					font: 13px;
					margin-left: 12px;
					line-height: 16px;
				}
				
				.p0 {
					text-align: left;
					padding-left: 133px;
					margin-top: 119px;
					margin-bottom: 0px;
				}
				
				.p1 {
					text-align: left;
					margin-top: 0px;
					margin-bottom: 0px;
					white-space: nowrap;
				}
				
				.p2 {
					text-align: left;
					padding-left: 288px;
					margin-top: 24px;
					margin-bottom: 0px;
				}
				
				.p3 {
					text-align: justify;
					padding-left: 106px;
					padding-right: 106px;
					margin-top: 20px;
					margin-bottom: 0px;
				}
				
				.p4 {
					text-align: left;
					padding-left: 298px;
					margin-top: 38px;
					margin-bottom: 0px;
				}
				
				.p5 {
					text-align: justify;
					padding-left: 154px;
					padding-right: 106px;
					margin-top: 20px;
					margin-bottom: 0px;
					text-indent: -24px;
				}
				
				.p6 {
					text-align: justify;
					padding-left: 154px;
					padding-right: 106px;
					margin-top: 1px;
					margin-bottom: 0px;
					text-indent: -24px;
				}
				
				.p7 {
					text-align: left;
					padding-left: 272px;
					margin-top: 128px;
					margin-bottom: 0px;
				}
				
				.p8 {
					text-align: justify;
					padding-left: 320px;
					padding-right: 106px;
					margin-top: 96px;
					margin-bottom: 0px;
				}
				
				.p9 {
					text-align: justify;
					padding-left: 154px;
					padding-right: 105px;
					margin-top: 0px;
					margin-bottom: 0px;
				}
				
				.p10 {
					text-align: justify;
					padding-left: 154px;
					padding-right: 106px;
					margin-top: 18px;
					margin-bottom: 0px;
					text-indent: -24px;
				}
				
				.p11 {
					text-align: justify;
					padding-left: 154px;
					padding-right: 106px;
					margin-top: 0px;
					margin-bottom: 0px;
					text-indent: -24px;
				}
				
				.p12 {
					text-align: justify;
					padding-left: 154px;
					padding-right: 106px;
					margin-top: 17px;
					margin-bottom: 0px;
					text-indent: -24px;
				}
				
				.p13 {
					text-align: left;
					padding-left: 196px;
					margin-top: 32px;
					margin-bottom: 0px;
				}
				
				.p14 {
					text-align: justify;
					padding-left: 106px;
					padding-right: 106px;
					margin-top: 17px;
					margin-bottom: 0px;
				}
				
				.p15 {
					text-align: justify;
					padding-left: 106px;
					padding-right: 106px;
					margin-top: 33px;
					margin-bottom: 0px;
				}
				
				.p16 {
					text-align: left;
					padding-left: 272px;
					margin-top: 289px;
					margin-bottom: 0px;
				}
				
				.td0 {
					padding: 0px;
					margin: 0px;
					width: 302px;
					vertical-align: bottom;
				}
				
				.td1 {
					padding: 0px;
					margin: 0px;
					width: 289px;
					vertical-align: bottom;
				}
				
				.tr0 {
					height: 23px;
				}
				
				.tr1 {
					height: 20px;
				}
				
				.tr2 {
					height: 25px;
				}
				
				.tr3 {
					height: 19px;
				}
				
				.tr4 {
					height: 22px;
				}
				
				.tr5 {
					height: 27px;
				}
				
				.t0 {
					width: 591px;
					margin-left: 113px;
					margin-top: 19px;
					font: 16px;
				}

				
				@page {
					margin: 0px 0px 0px 0px !important;
					padding: 0px 0px 0px 0px !important;
				}


			</STYLE>
		</HEAD>
		
		<BODY>
			<DIV id="page_1">
				<DIV id="p1dimg1">
					<IMG src="" id="p1dimg1">
				</DIV>
		
		
				<DIV class="dclr"></DIV>
				<P class="p0 ft0">TÉRMINOS Y CONDICIONES DEL PRODUCTO SOLICITADO</P>
				<TABLE cellpadding=0 cellspacing=0 class="t0">
					<TR>
						<TD class="tr0 td0">
							<P class="p1 ft1">Nombre del Cliente:</P>
						</TD>
						<TD class="tr0 td1">
							<P class="p1 ft1">Tipo Documento:</P>
						</TD>
					</TR>
					<TR>
						<TD class="tr1 td0">
							<P class="p1 ft2">'.$arrayData['nombres'].' '.$arrayData['apellidos'].'</P>
						</TD>
						<TD class="tr1 td1">
							<P class="p1 ft2">'.$arrayData['tipo_identificacion'].'</P>
						</TD>
					</TR>
					<TR>
						<TD class="tr2 td0">
							<P class="p1 ft1">Número de Documento:</P>
						</TD>
						<TD class="tr2 td1">
							<P class="p1 ft1">Tipo de Producto Solicitado:</P>
						</TD>
					</TR>
					<TR>
						<TD class="tr3 td0">
							<P class="p1 ft2">'.$arrayData['identificacion'].'</P>
						</TD>
						<TD class="tr3 td1">
							<P class="p1 ft2">'.$arrayData['tipo_producto'].'</P>
						</TD>
					</TR>
					<TR>
						<TD class="tr2 td0">
							<P class="p1 ft2">
							<SPAN class="ft1">Valor Solicitado: </SPAN>$ '.number_format($arrayData['valor_solicitado'],0,",",".").'</P>
						</TD>
						<TD class="tr2 td1">
							<P class="p1 ft2">
							<SPAN class="ft1">Valor a Pagar: </SPAN>$ '.number_format($arrayData['valor_cuota'],0,",",".").'</P>
						</TD>
					</TR>
					<TR>
						<TD class="tr4 td0">
							<P class="p1 ft2">
							<SPAN class="ft1">Tipo Tasa de Interés: </SPAN>'.$arrayData['tipo_tasa'].'</P>
						</TD>
						<TD class="tr4 td1">
							<P class="p1 ft2">
								<SPAN class="ft1">Tasa Mora: </SPAN>'.$arrayData['tasa_mora'].'</P>
						</TD>
					</TR>
					<TR>
						<TD class="tr4 td0">
							<P class="p1 ft2">
							<SPAN class="ft1">Periodicidad Pago: </SPAN>'.$arrayData['periodicidad_pago'].'</P>
						</TD>
						<TD class="tr4 td1">
							<P class="p1 ft2">
							<SPAN class="ft1">Número Cuotas: </SPAN>'.$arrayData['num_cuotas'].'</P>
						</TD>
					</TR>
					<TR>
						<TD class="tr3 td0">
							<P class="p1 ft2">
							<SPAN class="ft1">Administración: </SPAN>$ '.number_format($arrayData['costo_administracion_linxe'],0,",",".").'</P>
						</TD>
						<TD class="tr3 td1">
							<P class="p1 ft2">
							<SPAN class="ft1">Tecnologia: </SPAN>$ '.number_format($arrayData['costo_tecnologia_linxe'],0,",",".").'</P>

						</TD>
					</TR>
					<TR>
						<TD class="tr3 td0">
							<P class="p1 ft2">
							<SPAN class="ft1">Monto Cuota: </SPAN></P>
						</TD>
						<TD class="tr3 td1">
							<P class="p1 ft2">
							<SPAN class="ft1">Empresa Pagadora:</P>
						</TD>
					</TR>
					<TR>
						<TD class="tr1 td0">
							<P class="p1 ft2">$ '.number_format($arrayData['monto_cuota'],0,",",".").'</P>
						</TD>
						<TD class="tr1 td1">
							<P class="p1 ft2">'.$arrayData['razon_social'].'</P>
						</TD>
					</TR>
				</TABLE>
				<P class="p2 ft1">Acuerdo de Firma Electrónica</P>
				<P class="p3 ft4">En adelante acepto realizar transacciones, firmar contratos, pactos, documentos, títulos valores y acuerdos con la entidad de forma electrónica. El método de firma electrónica que utilizaré podrá ser un nombre de usuario y una contraseña. Luego
					de haberme registrado en la plataforma web de la entidad, todo lo que acepte se entenderá consentido y firmado electrónica y/o digitalmente; de igual forma la entidad podrá identificarme mediante preguntas de seguridad, un código enviado mediante
					un mensaje a mi teléfono móvil registrado en la entidad, un código enviado a mi correo electrónico registrado en la entidad, a través de mi ubicación, mi dirección IP, los datos de mi ordenador, los datos de mi teléfono móvil, o mediante un
					cálculo sobre cualquiera de mis datos, un clic en una casilla o la mezcla de dos o más de ellas.</P>
				<P class="p4 ft1">Autorización de Descuento</P>
				<P class="p5 ft4">
					<SPAN class="ft5">1.</SPAN>
					<SPAN class="ft6">Autorizo a la Empresa Pagadora para que cuando terminen los contratos que tenga con ella, de la liquidación correspondiente se descuenten los saldos pendientes a favor de Firmus S.A.S., con Nit. </SPAN>
					<NOBR>901.260.610–6</NOBR> (en adelante, “FIRMUS”).</P>
				<P class="p6 ft4">
					<SPAN class="ft4">2.</SPAN>
					<SPAN class="ft7">Autorizo expresa e irrevocablemente para descontar de mi salario, prestaciones, cesantías, pensiones y/o cualquier pago derivado de la relación contractual laboral, de servicios o cualquier otra índole las cuotas mensuales de las obligaciones generadas y/o cualquier suma que adeude a FIRMUS, aún en el evento, si es del caso, de encontrarme disfrutando de vacaciones o licencias.</SPAN>
				</P>
				<P class="p6 ft4">
					<SPAN class="ft4">3.</SPAN>
					<SPAN class="ft7">Autorizo expresa e irrevocablemente a la Empresa Pagadora para que las sumas descontadas mensualmente en los términos aquí establecidos, sean giradas directamente y entregadas a FIRMUS, o a cualquier persona que se encuentre legitimada para recibir dichos pagos, dentro del término fijado para tal efecto. Si la Empresa Pagadora no descuenta y no paga a FIRMUS el valor de la(s) cuota(s) mensual(es) del(los) respectivo(s) producto(s), a través de los medios transaccionales de FIRMUS, no quedo exonerado de la responsabilidad por el pago de las mismas y los intereses de mora a que haya lugar.</SPAN>
				</P>
				<br>
				<br>
				<br>
				<br>

			</DIV>
			<DIV id="page_2">
				<DIV id="p2dimg1">
					<IMG src="" id="p2img1">
				</DIV>
		
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>

				<DIV class="dclr"></DIV>
				<P class="p10 ft4">
					<SPAN class="ft4">4.</SPAN>
					<SPAN class="ft7">Autorizo irrevocablemente a FIRMUS o a quien represente sus derechos u ostente en el futuro la calidad de acreedor para reportar la suscripción de la presente obligación a los bancos de información financiera, crediticia, comercial y de servicios, de acuerdo con lo establecido en la Ley 1266 de 2008, y las normas que la adicionen, sustituyan o modifiquen.</SPAN>
				</P>
				<P class="p10 ft4">
					<SPAN class="ft4">5.</SPAN>
					<SPAN class="ft7">Declaro que, en caso de mora o incumplimiento de las obligaciones a mi cargo, FIRMUS me hará exigible su cumplimiento de manera inmediata, sin necesidad de requerimiento judicial o extrajudicial para su cumplimiento, es decir, renuncio al requerimiento para constituirme en mora.</SPAN>
				</P>
				<P class="p11 ft4">
					<SPAN class="ft4">6.</SPAN>
					<SPAN class="ft9">Declaro que conozco que el producto que he adquirido no es un crédito ni puede ser entendido como tal, razón por la cual no me es cobrado ningún tipo de interés corriente y en su lugar solo se cobran una serie de contraprestaciones por administración del producto, entre otras.</SPAN>
				</P>
				<P class="p12 ft4">
					<SPAN class="ft5">7.</SPAN>
					<SPAN class="ft6">Expresamente declaro que la presente Autorización de Libranza o Descuento Directo no perderá su validez y permanecerá vigente mientras existan saldos a favor de FIRMUS, aún en el evento de cambio de entidad pagadora, toda vez que la simple autorización de descuento por mí dada, facultará a FIRMUS para solicitar a cualquier entidad pagadora con la que yo mantenga una relación laboral o contractual, el giro correspondiente de los recursos a que tenga derecho, para la debida atención de la(s) obligación(es) adquiridas bajo la modalidad de libranza o descuento directo; en cuyo caso, me obligo con FIRMUS a informar sobre dicha situación de manera inmediata al momento que se produzca el cambio.</SPAN>
				</P>
				<P class="p12 ft4">
					<SPAN class="ft5">8.</SPAN>
					<SPAN class="ft6">El Valor a Pagar se descontará en las cuotas de acuerdo a la periodicidad de pago definida por la Empresa Pagadora.</SPAN>
				</P>
				<P class="p13 ft1">Mérito ejecutivo de las obligaciones aquí contenidas</P>
				<P class="p14 ft4">Entiendo y acepto de manera expresa e irrevocable que el presente acuerdo prestará mérito ejecutivo por contener una obligación clara, expresa y exigible, y por ende, se me podrá exigir el cumplimientos de todas las obligaciones descritas en este
					documento ante cualquier juez de la república.</P>
				<P class="p15 ft4">Entiendo que debo pagar los intereses de mora correspondientes a los días adicionales que se causen para realizar el pago del Valor Solicitado, a la tasa moratoria máxima permitida en Colombia.</P>
				<P class="p15 ft4">Adicionalmente, declaro que conozco y acepto que a partir del quinto día de mora debo cancelar los gastos de cobranza, que podrán causarse hasta por un 20% sobre el valor total adeudado. Este valor me será cargado según las gestiones de cobro
					realizadas con el fin de cubrir los costos en que incurra FIRMUS por la realización de la gestión de cobranza a través de firmas externas especializadas y contratadas para tal fin.</P>
				<P class="p6 ft4"></p>
				<br/>
				<P class="p15 ft4">Firmado electrónicamente por:<br/>
				'.$arrayData['nombres'].' '.$arrayData['apellidos'].'<br/>
				'.$arrayData['tipo_identificacion'].' '.$arrayData['identificacion'].'<br/>
				'.date("Y-m-d H:i:s").'<br/>
				_______________________________________________________________________<br/>
				<b>Nombre:</b>			'.$arrayData['nombres'].' '.$arrayData['apellidos'].'<br/>
				<b>Tipo Identificación:</b>			'.$arrayData['tipo_identificacion'].'<br/>
				<b>Número Identificación:</b>			'.$arrayData['identificacion'].'
				</P>
				
				<P class="p6 ft4"></p>
			</DIV>
		</BODY>
		
		</HTML>';
		return $html;
	}

	public function getEmpresaByNit($nit)
    {

        $arrayEmpresas = [];

        $database = \Drupal::database();

        // registro
        $query = $database->select('empresas', 'em');
        $arrayAct = [$actividad,"ambos"];
        $result = $query->condition('em.identificacion', $nit, '=')
                    ->fields('em')
                    ->execute();
        //print_r($result);
        foreach ($result as $key=>$record) {
            //echo $record->convenio_actividadecon."<br/>";
            foreach($record as $key2 => $value)
            {
                $arrayEmpresas[$key][$key2] = $value;
            }
        }
		
        return $arrayEmpresas;
	}
	
	public function getEmpresaByID($idempresa)
    {

        $arrayEmpresas = [];

        $database = \Drupal::database();

        // registro
        $query = $database->select('empresas', 'em');
        $result = $query->condition('em.idempresa', $idempresa, '=')
                    ->fields('em')
                    ->execute();
        //print_r($result);
        foreach ($result as $key=>$record) {
            //echo $record->convenio_actividadecon."<br/>";
            foreach($record as $key2 => $value)
            {
                $arrayEmpresas[$key][$key2] = $value;
            }
        }
		
        return $arrayEmpresas;
    }

}
