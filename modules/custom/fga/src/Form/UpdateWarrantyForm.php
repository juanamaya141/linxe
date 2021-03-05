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
class UpdateWarrantyForm extends ConfirmFormBase
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
        return t('quiere proceder a hacer la actualización de la garantía %cid?', [
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
        return t('Enviar Actualización!');
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
        //validar si ha cambiado el valor de la garantía con el de la solicitud
        $res = $results->execute()->fetchAll();
        $res = (array)$res[0];
        $fechaInicioMora = date("Y-m-d", strtotime('+1 day',strtotime($res['garantia_fechavencimiento'])));
        if(floatval($res['saldo_pendiente']) != floatval($res['garantia_monto_faltante'])){
            $data = array(
                "nit"               => \Drupal::config('linxecredit.settings')->get('linxecredit.nit_fga'),
                'cedula'            => $res['documento'],
                "pagare"            => $res['garantia_numpagare'],
                "saldo_capital"     => $res['monto_maximo_aprobado'],
                "saldo_total"       => $res['saldo_pendiente'],
                "fecha_corte"       => $res['fecha_hora_liquidacion'],
                "num_cuotas_mora"   => "",
                "fec_inicio_mora"   => $fechaInicioMora,
                "fecha_cancelacion" => "",
                "estado_operacion"  => "V",
            );
            
            $fgalib = new FGALibrary();
            $respuestaFGA = $fgalib->fgaClaimGarantiaWS($data, $id);
            //hacer reclamación FGA y actualizar los datos en la tabla
            drupal_set_message($respuestaFGA['msg'], !$respuestaFGA['status']?'error':'status');
            return true;
        }else{
            drupal_set_message('La garantía ya se encuentra actualizada', 'warning');
            return true;
        }
        
    }
}
