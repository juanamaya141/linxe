<?php

namespace Drupal\fga\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * An example class form.
 */
class FGAFilterForm extends FormBase
{

	/**
	 * {@inheritdoc}
	 */
	public function getFormId()
	{
		return 'fga_form';
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildForm(array $form, FormStateInterface $form_state)
	{
		//$form['#attached']['library'][] = 'fga/fga-libraries';
		$form['filters'] = [
			'#type'  => 'fieldset',
			'#title' => $this->t('Filtrar'),
			'#open'  => true,
		];

		$form['filters']['estado'] = [
			'#type' => 'select',
			'#title' => 'Estado',
			'#required' => TRUE,
			'#name' => 'estado',
			'#default_value' => ($_GET['estado']) ? $_GET['estado'] : '',
			'#options' => array(
				'' => t('Seleccione un estado'),
				'desembolsado' => t('desembolsado'),
				'en_proceso_liquidacion' => t('en_proceso_liquidacion'),
				'liquidado' => t('liquidado'),
			),
		];

		$form['filters']['actions'] = [
			'#type'       => 'actions'
		];
	
		$form['filters']['actions']['submit'] = [
			'#type'  => 'submit',
			'#value' => $this->t('Filter')
			
		];

		return $form;
	}


	/**
	 * {@inheritdoc}
	 */
	public function validateForm(array &$form, FormStateInterface $form_state)
	{
		var_dump($form_state->getValue('estado'));
		if ($form_state->getValue('estado') == "") {
			$form_state->setErrorByName('from', $this->t('You must enter a valid first name.'));
		}
	}
	/**
	 * {@inheritdoc}
	 */
	public function submitForm(array &$form, FormStateInterface $form_state)
	{
		$field = $form_state->getValues();
		$estado = $field["estado"];

		$url = \Drupal\Core\Url::fromRoute('fga.list')
			->setRouteParameters(array('estado' => $estado));
		$form_state->setRedirectUrl($url);
	}
}
