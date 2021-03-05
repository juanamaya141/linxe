<?php

namespace Drupal\fga\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\fga\Libs\FGALibrary as FGALibrary;
/**
 * Class StudentDeleteForm.
 *
 * @package Drupal\outlook_calendar\Form
 */
class ClaimWarrantyForm extends ConfirmFormBase
{

    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'fga_claim_form';
    }

    public $cid;

    /**
     * {@inheritdoc}
     */
    public function getQuestion()
    {
        return t('quiere proceder a hacer la reclamación de la garantía %cid?', [
            '%cid' => $this->cid,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelUrl()
    {
        return new Url('fga.list');
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return t('Esta acción no se puede revertir');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmText()
    {
        return t('Enviar reclamación!');
    }

    /**
     * {@inheritdoc}
     */
    public function getCancelText()
    {
        return t('Cancelar');
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL)
    {
        $this->cid = $cid;
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $id = $this->cid;
        $results = db_select('garantias_fga', 'gf');
        $results->join('adelantos_nomina', 'an', 'gf.idsolicitud = an.idadelanto');
        $results->join('registrados_an', 're', 're.idregistro = an.idregistro ');
        $results->fields('gf');
        $results->fields('an');
        $results->fields('re');
        $results->condition('gf.idgarantia', $id);
        $results->orderBy('gf.idgarantia', 'DESC');
        $res = $results->execute()->fetchAll();
        $res = (array)$res[0];
        $nombre = explode(" ", $res['nombre']);
        $fechaInicioMora = date("Y-m-d", strtotime('+1 day',strtotime($res['garantia_fechavencimiento'])));
        $data = array(
            'nit' => \Drupal::config('linxecredit.settings')->get('linxecredit.nit_fga'),
            'cedula' => $res['documento'],
            "pagare" => $res['garantia_numpagare'],
			"fecha_inicio_mora" => $fechaInicioMora,
			"saldo_capital" => $res['monto_maximo_aprobado'],
			"intereses" => $res['intereses_mora']? $res['intereses_mora']: '0',
			"otros_gastos" => (floatval($res['monto_maximo_aprobado']) *0.0188) + floatval($res['administracion']) + floatval($res['seguros']) + floatval($res['tecnologia']) + floatval($res['iva']),
			"total_reclamacion" => $res['garantia_monto_faltante'],
			"primer_nombre" => $nombre[0],// separar los nombres
			"segundo_nombre" => $nombre[1],
			"primer_apellido" => $res['primer_apellido'],
			"segundo_apellido" => $res['segundo_apellido'],
			"fecha_vencimiento" => $res['garantia_fechavencimiento'],
			"medidas_cautelares" => "",
			"nombre_apoderado" => "",
			"cedula_apoderado" => "",
			"tp_apoderado" => "",
			"telefono_apoderado" => "",
			"juzgado" => "",
			"radicado" => "",
			"fecha_demanda" => ""
        );
        $fgalib = new FGALibrary();
        $respuestaFGA = $fgalib->fgaClaimGarantiaWS($data, $id);
        //hacer reclamación FGA y actualizar los datos en la tabla
        drupal_set_message($respuestaFGA['msg'], !$respuestaFGA['status']?'error':'status');
        return true;
    }
}
