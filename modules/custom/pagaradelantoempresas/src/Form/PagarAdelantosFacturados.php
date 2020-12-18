<?php
namespace Drupal\pagaradelantoempresas\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class DeleteForm.
 *
 * @package Drupal\mydata\Form
 */
class PagarAdelantosFacturados extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'pagar_adelanto_facturado_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $uid = \Drupal::currentUser()->id();  //obtenemos el uid del usuario logueado
        // Add the headers.
        $facturado = 0;

        $db = \Drupal::database();
        $consultar = $db->select('liquidaciones_an','liq');
        $consultar->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
        $consultar->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
        $consultar->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
        $consultar->fields('liq');
        $consultar->fields('an',["idadelanto","estado_general_solicitud","cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
        $consultar->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"]);
        $consultar->condition('an.estado_general_solicitud',"en_proceso_liquidacion");
        $consultar->condition('liq.estado',"facturado");
        $consultar->condition('emp.iduser',$uid);
    
        
        $resultado = $consultar->execute();
        foreach($resultado as $datos){

            $facturado += $datos->valor;

        }

        $html = '<h3 style="text-align: right">Total Facturado: $ '.number_format(ceil($facturado),0,",",".").'</h3>';
        $html2 = '<h3>Total Pagado: $ <span id="total">'.number_format(ceil($facturado),0,",",".").'</span></h3>';
        $form['facturado'] = array(
                '#type'
                    => 'markup',
                    '#markup'
                        => $html,
        );

        $form['resultado'] = array(
            '#type'
            => 'markup',
            '#markup'
                => $html2,
        );

        $header_table = array(
            t('Num. Adelanto'),
            t('Tipo Documento'),
            t('Documento'),
            t('Nombre'),
            t('Valor Adelanto'),
            t('Saldo al Corte'),
            t('Num. Cuota'),
            t('Saldo en Mora'),
            t('Intereses Mora'),
            t('Valor Facturado'),
            t('Valor a Pagar'),
        );
        $form['adelantos'] = array(
            '#type' => 'table',
            '#title' => 'Pagar Adelantos de Salario Facturados',
            '#header' => $header_table,
        );


        $db = \Drupal::database();
        $query = $db->select('liquidaciones_an','liq');
        $query->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
        $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
        $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
        $query->fields('liq');
        $query->fields('an',["idadelanto","estado_general_solicitud","cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
        $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"]);
        $query->condition('an.estado_general_solicitud',"en_proceso_liquidacion");
        $query->condition('liq.estado',"facturado");
        $query->condition('emp.iduser',$uid);
    
        
        $result = $query->execute();
        
        // Populate the rows.
        $rows = array();
        
        
        $i=0;
        foreach($result as $data){
            
            $valor_adelanto = $data->valor_solicitado + $data->administracion + $data->seguros + $data->tecnologia + $data->iva;
            if($data->intereses_mora==null)
            {
            $data->intereses_mora = 0;
            }
            $saldo_mora = 0;
            if($data->intereses_mora > 0)
            {
            $saldo_mora = $data->valor - $data->intereses_mora;
            }
    
            $form['adelantos'][$i] = array(
            'idadelanto'=>   array('#plain_text' => $data->idadelanto) ,
            'tipo_documento' => array('#plain_text' => $data->tipodocumento),
            'documento' => array('#plain_text' => $data->documento),
            'nombre'=>    array('#plain_text' => $data->nombre." ".$data->primer_apellido),
            'valor_adelanto' => array('#plain_text' => "$".number_format($valor_adelanto,0,",",".") ),
            'saldo_corte' => array('#plain_text' => "$".number_format($data->saldo_pendiente,0,",",".") ),
            'num_cuota' => array('#plain_text' => $data->num_cuota),
            'saldo_mora' => array('#plain_text' => "$".number_format($saldo_mora,0,",",".")),
            'intereses_mora' => array('#plain_text' =>  "$".number_format($data->intereses_mora,0,",",".")),
            'valor_facturado' => array('#plain_text' => "$".number_format(ceil($data->valor),0,",",".")),
            'valorpagar_'.$data->idadelanto.'_'.$data->idliquidacion => array(
                    '#type' => 'textfield',
                    '#title' => t('Name'),
                    '#size' => 20,
                    '#title_display' => 'invisible',
                    '#default_value' => ceil($data->valor),
                    '#attributes' => array('class' => array('monto'),'onchange' => 'sumar(this.value);'),
                )
            );
            $i++;
    
        }
        
 
 
        // Add a submit button that handles the submission of the form.
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Conciliar Pagos'),
        ];
 
        return $form;
    }

    /**
    * {@inheritdoc}
    */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $bande = true;
        $keyError = "";
        $values = $form_state->getValues();
        foreach($values['adelantos'] as  $valueArray)
        {

            if(is_array($valueArray))
            foreach($valueArray as $key => $value)
            {
                $arrayKey = explode("_",$key);
                if($arrayKey[0]=="valorpagar")
                {
                    if(!is_numeric($value))
                    {
                        $bande = false;
                        $keyError = $key;
                        break;
                    }
                }
            }
            if($bande==false)
            {
                break;
            }
        
            if($bande==false)
            {
                break;
            }
        }

        if($bande == false) {
            $form_state->setErrorByName($keyError, $this->t('Todos los campos deben tener un valor numérico'));
        }

    }
 
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Find out what was submitted.
        $db = \Drupal::database();
        $values = $form_state->getValues();
        //drupal_set_message(print_r($values['adelantos'],true));

        foreach($values['adelantos'] as  $valueArray)
        {

            if(is_array($valueArray))
            foreach($valueArray as $key => $value)
            {
                $arrayKey = explode("_",$key);
                if($arrayKey[0]=="valorpagar")
                {
                    $idadelanto = $arrayKey[1];
                    $saldo_pendiente = $db->select('adelantos_nomina', 'an')
											->fields('an', ['saldo_pendiente'])
											->condition('an.idadelanto', $idadelanto , '=')
											->execute()->fetchField();

                    $idliquidacion = $arrayKey[2];
                    $valor_pagado = $value;
                    //debemos actualizar todas las solicitudes
                    $arrayFields=[];
                    $arrayFields["valor_pagado"] = $valor_pagado;
                    $arrayFields["fecha_hora_pago"] = date("Y-m-d H:i:s");
                    $arrayFields["estado"] = "pagado";

                    $update = $db->update('liquidaciones_an')
							->fields($arrayFields)
							->condition('idliquidacion', $idliquidacion , '=')
                            ->execute();
                    if($update > 0)
                    {
                        $message = "Liquidacion de AN #".$idliquidacion." Actualizada : ".json_encode($arrayFields);
                        \Drupal::logger('pagaradelantoempresas')->notice($message);

                        drupal_set_message($message);
                        $url = \Drupal\Core\Url::fromRoute('adelantospagados.results');
                        $form_state->setRedirectUrl($url); 
                        /*
                        //actualizo el saldo del adelanto de nomina
                        $saldo_nuevo_pendiente = $saldo_pendiente - $valor_pagado;
                        $arrayAdelantoNomina["saldo_pendiente"] = $saldo_pendiente;
                        $updateAN  = $db->update('adelantos_nomina')
                                                ->fields($arrayAdelantoNomina)
                                                ->condition('idadelanto', $idadelanto , '=')
                                                ->execute();
                        if($updateAN > 0)
                        {
                            $message = "Adelanto de nomina Actualizado # ".$idadelanto." Correctamente : ".json_encode($arrayAdelantoNomina);
                            \Drupal::logger('facturacionadelantos')->notice($message);
                            

                            
                        }else{
                            $message = "Error Actualizacion Adelanto de nomina # ".$idadelanto." : ".json_encode($arrayAdelantoNomina);
                            \Drupal::logger('facturacionadelantos')->error($message);
                        }*/
                    }else{
                        $message = "Error Actualizacion Liquidacion de AN #".$idliquidacion." : ".json_encode($arrayFields);
                        \Drupal::logger('pagaradelantoempresas')->error($message);

                        drupal_set_error($message);
                    }
                }
            }
            
        }


    }
}