<?php

namespace Drupal\empresas\Libs;

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


class EmpresasLibrary  {

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

	public function sendRejectionsAN($idempresa){
		
		$db = \Drupal::database();
		$query = $db->select('adelantos_nomina','an');
		$query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
		$query->fields('an');
		$query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido","email"]);
		$arrayEstados = ["solicitada","seleccion_monto","validacion_desembolso"];
		$query->condition('an.estado_general_solicitud',$arrayEstados,"in");
		$query->condition('reg.idempresa',$idempresa);

		$result = $query->execute();

		
		foreach($result as $data){
			$titleMsg = "LO SENTIMOS";
			$mensaje = "<p>Hola ".$data->nombre." ".$data->primer_apellido.", no hemos podido <b>firmar el convenio</b> con tu empresa, <b>sin este requisito no podemos hacer el desembolso</b>, por favor debes contactarte con el área de recursos humanos para <b>que nos permitan firmar el convenio,</b> solo así podremos continuar con el proceso.</p><br/>";
			$mensaje .= "<p>Si tu empresa quiere firmar el convenio con Linxe, nos puedes informar vía mail para que los volvamos a contactar.</p>";
			$mensaje .= "<p>Gracias.</p>";
			$mensaje .= "<p>Equipo Linxe.</p>";
			$emailto = $data->email;
			$this->sendMail_error($titleMsg,$mensaje,$emailto);

			//ahora actualizamos el estado de la solicitud a rechazada
			
			$arrayFields["estado_solicitud"]="rechazada";
			$arrayFields["estado_general_solicitud"]="rechazada";
			$update = $db->update('adelantos_nomina')
							->fields($arrayFields)
							->condition('idadelanto', $data->idadelanto , '=')
							->execute();
			

		}
		
	}

	//Método con str_shuffle() 
	function generateRandomString($length = 10) { 
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length); 
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
}
