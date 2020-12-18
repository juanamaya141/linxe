<?php

namespace Drupal\linxecredit\Libs;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Url;


class DecevalLibrary  {

	public function getconsultarPagares($arrayData)
	{
		$urlws_deceval = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_deceval') ; 
		
		$deceval_codigodepositante = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_codigodepositante');
		$deceval_usuario = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_usuario');
		$deceval_identificacionemisor = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_identificacionemisor');

		$params = new \stdClass();
		
		$params->arg0->header->codigoDepositante = $deceval_codigodepositante ;
		$params->arg0->header->fecha = date("Y-m-d\TH:i:s") ;
		$params->arg0->header->hora = date("H:i") ;
		$params->arg0->header->usuario = $deceval_usuario ;
		if(array_key_exists('codigoDeceval', $arrayData))
			$params->arg0->consultaPagareServiceDTO->codigoDeceval = $arrayData["codigoDecevalPagare"] ;
		if(array_key_exists('idEstadoPagare', $arrayData))
			$params->arg0->consultaPagareServiceDTO->idEstadoPagare = $arrayData["idEstadoPagare"] ;
		if(array_key_exists('numPagareEntidad', $arrayData))
			$params->arg0->consultaPagareServiceDTO->numPagareEntidad = $arrayData["numPagareEntidad"] ;
		$params->arg0->consultaPagareServiceDTO->idTipoIdentificacionFirmante = $arrayData["idTipoIdentificacionFirmante"]  ;
		$params->arg0->consultaPagareServiceDTO->numIdentificacionFirmante = $arrayData["numIdentificacionFirmante"]  ;


		$client = new \SoapClient($urlws_deceval, array('trace' => 1));
		$array = json_decode(json_encode($params),true);

		$result = $client->__SoapCall(
			'consultarPagares',
			array($array)
		);
		
		return $result;
	}
	
	public function crearGirador($arrayCrearGirador)
	{
		

		$params = new \stdClass();
		
		$params->arg0->header->codigoDepositante = $arrayCrearGirador["deceval_codigodepositante"] ;
		$params->arg0->header->fecha = date("Y-m-d\TH:i:s") ;
		$params->arg0->header->hora = date("H:i") ;
		$params->arg0->header->usuario = $arrayCrearGirador["deceval_usuario"] ;

		$params->arg0->crearGiradorDTO->identificacionEmisor = $arrayCrearGirador["deceval_identificacionemisor"] ;
		$params->arg0->crearGiradorDTO->correoElectronico = $arrayCrearGirador["correoElectronico"] ;
		$params->arg0->crearGiradorDTO->fechaExpedicion_Nat = $arrayCrearGirador["fechaExpedicion"] ;
		$params->arg0->crearGiradorDTO->fkIdClasePersona = 1 ;
		$params->arg0->crearGiradorDTO->fkIdTipoDocumento = $arrayCrearGirador["idTipoDocumento"] ;
		$params->arg0->crearGiradorDTO->numeroDocumento = $arrayCrearGirador["numeroDocumento"] ;
		
		$params->arg0->crearGiradorDTO->nombresNat_Nat = $arrayCrearGirador["nombres"] ;
		$params->arg0->crearGiradorDTO->primerApellido_Nat = $arrayCrearGirador["primer_apellido"] ;
		$params->arg0->crearGiradorDTO->segundoApellido_Nat = $arrayCrearGirador["segundo_apellido"] ;
		$params->arg0->crearGiradorDTO->telefono1PersonaGrupo_PGP = $arrayCrearGirador["telefono"] ;
		$params->arg0->crearGiradorDTO->cuentaGirador = "?" ;

		try{
			$client_params = array(
				'soap_version' => SOAP_1_1,
				'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
				'encoding' => 'ISO-8859-1',
				'trace' => 1,
				'exceptions' => true,
				'cache_wsdl' => WSDL_CACHE_NONE,
				'features' => SOAP_SINGLE_ELEMENT_ARRAYS
			);

			$client = new \SoapClient($arrayCrearGirador["urlws_deceval"], $client_params );
			$array = array(json_decode(json_encode($params),true));
			//print_r($array);

			$result = $client->__SoapCall(
				'creacionGiradores',
				$array 
			);
		} catch (Exception $exc) {
			echo htmlspecialchars(print_r($exc, true));
	   	}
		/*$result = $client->creacionGiradoresCodificados(
			array($params)
		);*/
		
		return $result;
	}

	public function crearPagare($arrayData)
	{
		$urlws_deceval = $arrayData["urlws_deceval"]; 
		
		$deceval_codigodepositante = $arrayData["deceval_codigodepositante"];
		$deceval_usuario = $arrayData["deceval_usuario"];
		$deceval_identificacionemisor = $arrayData["deceval_identificacionemisor"];
		$deceval_idclasedocumento = $arrayData["deceval_idclasedocumento"];

		$digits = 4;
		$end_numpagare = str_pad(Rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
		$numPagareEntidad = date("YmdHi").$end_numpagare;

		$params = new \stdClass();
		
		$params->arg0->header->codigoDepositante = $deceval_codigodepositante ;
		$params->arg0->header->fecha = date("Y-m-d\TH:i:s") ;
		$params->arg0->header->hora = date("H:i") ;
		$params->arg0->header->usuario = $deceval_usuario ;

		$params->arg0->documentoPagareServiceDTO->nitEmisor = $deceval_identificacionemisor ;

		$params->arg0->documentoPagareServiceDTO->idClaseDefinicionDocumento = $deceval_idclasedocumento ; //?

		$params->arg0->documentoPagareServiceDTO->fechaGrabacionPagare = date("Y-m-d") ;
		$params->arg0->documentoPagareServiceDTO->tipoPagare = 2 ;
		$params->arg0->documentoPagareServiceDTO->numPagareEntidad = $numPagareEntidad ; //?
		$params->arg0->documentoPagareServiceDTO->otorganteTipoId = $arrayData["otorganteTipoId"] ;
		$params->arg0->documentoPagareServiceDTO->otorganteNumId = $arrayData["otorganteNumId"]  ;
		$params->arg0->documentoPagareServiceDTO->mensajeRespuesta = 1 ;
		$params->arg0->documentoPagareServiceDTO->creditoReembolsableEn = 2 ;

		$client = new \SoapClient($urlws_deceval, array('trace' => 1));
		$array = json_decode(json_encode($params),true);

		$result = $client->__SoapCall(
			'creacionPagaresCodificado',
			array($array)
		);
		
		return $result;
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

	public function firmarPagare($arrayData)
	{
		$urlws_deceval = $arrayData["urlws_deceval"]; 
		
		$deceval_codigodepositante = $arrayData["deceval_codigodepositante"];
		$deceval_usuario = $arrayData["deceval_usuario"];
		$deceval_identificacionemisor = $arrayData["deceval_identificacionemisor"];
		$deceval_idclasedocumento = $arrayData["deceval_idclasedocumento"];

		$params = new \stdClass();
		
		$params->arg0->header->codigoDepositante = $deceval_codigodepositante ;
		$params->arg0->header->fecha = date("Y-m-d\TH:i:s") ;
		$params->arg0->header->hora = date("H:i") ;
		$params->arg0->header->usuario = $deceval_usuario ;

		$params->arg0->informacionFirmaPagareCaracteresDTO->OTPPagare = $arrayData["OTPPagare"] ; //?
		$params->arg0->informacionFirmaPagareCaracteresDTO->OTPProcedimiento = $arrayData["OTPProcedimiento"] ; //?
		$params->arg0->informacionFirmaPagareCaracteresDTO->clave = $arrayData["clave"] ; //?
		$params->arg0->informacionFirmaPagareCaracteresDTO->idDocumentoPagare = $arrayData["idDocumentoPagare"] ;
		$params->arg0->informacionFirmaPagareCaracteresDTO->idRolFirmante = $arrayData["idRolFirmante"] ; //?
		$params->arg0->informacionFirmaPagareCaracteresDTO->motivo = $arrayData["motivo"] ;
		$params->arg0->informacionFirmaPagareCaracteresDTO->numeroDocumento = $arrayData["numeroDocumento"]  ;
		$params->arg0->informacionFirmaPagareCaracteresDTO->tipoDocumento = $arrayData["tipoDocumento"]  ;

		$client = new \SoapClient($urlws_deceval, array('trace' => 1));
		$array = json_decode(json_encode($params),true);

		$result = $client->__SoapCall(
			'firmarPagareCaracteres',
			array($array)
		);
		
		return $result;
	}

}
