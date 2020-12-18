<?php

namespace Drupal\dispersion\Libs;

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


class DispersionLibrary  {

	public function sendMail_success($title,$msg,$emailto)
	{
		$paththeme = base_path().drupal_get_path('theme', 'linxe');
		$dominioactual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            
		$template_str = '
		<div >
			<center>
				<img src="'.$dominioactual.$paththeme.'/images/dashboard/exito.jpg" alt="LINXE"  width="130" />
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
