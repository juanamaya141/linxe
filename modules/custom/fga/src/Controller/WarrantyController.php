<?php

	namespace Drupal\fga\Controller;

	use Drupal\Core\Form\FormBase;
	use Drupal\Core\Controller\ControllerBase;
	use Drupal\Core\Url;
	use Drupal\Core\Routing;
	use Drupal\Core\Form\FormStateInterface;
	/**
	 * An example class controller.
	 */
	class WarrantyController extends ControllerBase {

		/**
		* An example method.
		*/
		public function listWarranties() {
			//Get parameter value while submitting filter form  
			$estado = \Drupal::request()->query->get('estado');
			$form['form'] = $this->formBuilder()->getForm('Drupal\fga\Form\FGAFilterForm');
			// Create table header.
			$header = array(
				'id' => $this->t('Id'),
				'tipodocumento' => $this->t('Tipo de Doc'),
				'documento' => $this->t('Documento'),
				'nombre' => $this->t('Nombre'),
				'primer apellido' => $this->t('Primer apellido'),
				'segundo apellido' => $this->t('Segundo apellido'),
				'Estado de la garantía' => $this->t('Estado de la garantía'),
				'opt' =>$this->t('Acciones')
			);
			
			if($estado == ""){
				$form['table'] = [
					'#type' => 'table',
					'#header' => $header,
					'#rows' => get_warranties("All",""),
					'#empty' => $this->t('No se han encontrado garantias pendientes'),
				];
			}else{
				$form['table'] = [
					'#type' => 'table',
					'#header' => $header,
					'#rows' => get_warranties("",$estado),
					'#empty' => $this->t('No se han encontrado garantias pendientes'),
				];
			}
			$form['pager'] = [
				'#type' => 'pager'
			];
			return $form;
		}

		public function claimWarranty(){
			drupal_set_message('Se ha hecho la reclamación de la garantía');
    		return $this->redirect('fga.list');
		}
	}
	 
	?>