<?php
namespace Drupal\conciliacionadelantos\Form;
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
class ConciliacionAdelantosPagados extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'conciliar_adelantos_pagados_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        $nitempresa = \Drupal::request()->query->get('emp'); 

        $db = \Drupal::database();
        
        // registro
        $query = $db->select('empresas', 'em');
        $result = $query->condition('em.convenio_tipoproducto', "adelanto", '=')
                    ->condition('em.estado_convenio', "aceptado" , '=')
                    ->fields('em')
                    ->execute();

        $arrayEmpresas = [];
        $arrayEmpresas[""] = "Seleccione una empresa";
        foreach ($result as $key => $record) {
            $arrayEmpresas[$record->identificacion]=$record->razon_social;
        }




        $form['empresa'] = array(
            '#type' => 'select',
            '#title' => t('Empresa:'),
            '#required' => TRUE,
            '#default_value' => $nitempresa,
            '#label_display' =>'after',
            '#options' => $arrayEmpresas,
            '#attributes' => array(
                'data-msj' => t("Empresa"),
                'onchange' =>"javascript: document.location.href='?emp='+this.value;"
            ),
            '#wrapper_attributes'=> array(
                'class' => "",
            )
            
        );

        $pagado = 0;

        $consulta = $db->select('liquidaciones_an','liq');
        $consulta->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
        $consulta->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
        $consulta->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
        $consulta->fields('liq');
        $consulta->fields('an',["idadelanto","estado_general_solicitud","cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
        $consulta->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"]);
        $consulta->condition('an.estado_general_solicitud',"en_proceso_liquidacion");
        $consulta->condition('liq.estado',"pagado");
        $consulta->condition('emp.identificacion',$nitempresa);
        
        $resultados = $consulta->execute();

        foreach($resultados as $datos){

            $pagado += $datos->valor_pagado;

        }

        $html = '<h3 style="text-align: right">Total Pagado: $ '.number_format(ceil($pagado),0,",",".").'</h3>';
        $html2 = '<h3>Total Conciliado: $ <span id="totalC">'.number_format(ceil($pagado),0,",",".").'</span></h3>';
        $form['pagado'] = array(
                '#type'
                    => 'markup',
                    '#markup'
                        => $html,
        );

        $form['conciliado'] = array(
            '#type'
            => 'markup',
            '#markup'
                => $html2,
        );
        
        
        // Add the headers.
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
            t('Valor Pagado'),
            t('Valor a Conciliar'),
        );
        $form['adelantos'] = array(
            '#type' => 'table',
            '#title' => 'Conciliar Adelantos de Salario Pagados',
            '#header' => $header_table,
        );

        $query = $db->select('liquidaciones_an','liq');
        $query->join('adelantos_nomina', 'an', 'liq.idsolicitud = an.idadelanto');
        $query->join('registrados_an', 'reg', 'an.idregistro = reg.idregistro');
        $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
        $query->fields('liq');
        $query->fields('an',["idadelanto","estado_general_solicitud","cuota_actual","cuotas_pactadas","estado_liquidacion","fecha_hora_liquidacion","fecha_hora_desembolso","saldo_pendiente","valor_solicitado","administracion","seguros","tecnologia","iva"]);
        $query->fields('reg',["tipodocumento","documento","nombre","primer_apellido","segundo_apellido"]);
        $query->condition('an.estado_general_solicitud',"en_proceso_liquidacion");
        $query->condition('liq.estado',"pagado");
        $query->condition('emp.identificacion',$nitempresa);
        
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
            'valor_pagado' => array('#plain_text' => "$".number_format(ceil($data->valor_pagado),0,",",".")),
            'valorconciliar_'.$data->idadelanto.'_'.$data->idliquidacion => array(
                    '#type' => 'textfield',
                    '#title' => t('Name'),
                    '#size' => 20,
                    '#title_display' => 'invisible',
                    '#default_value' => ceil($data->valor_pagado),
                    '#attributes' => array('onchange' => 'totalConciliado(this.value);'),

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
                if($arrayKey[0]=="valorconciliar")
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
            $form_state->setErrorByName('adelantos', $this->t('Todos los campos deben tener un valor numérico'));
        }
        

    }
 
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Find out what was submitted.
        $db = \Drupal::database();
        $values = $form_state->getValues();
        //drupal_set_message(print_r($values['adelantos'],true));
        //drupal_set_message(print_r($values['empresa'],true));
        
        foreach($values['adelantos'] as  $valueArray)
        {

            if(is_array($valueArray))
            foreach($valueArray as $key => $value)
            {
                $arrayKey = explode("_",$key);
                if($arrayKey[0]=="valorconciliar")
                {
                    $idadelanto = $arrayKey[1];
                    $saldo_pendiente = $db->select('adelantos_nomina', 'an')
											->fields('an', ['saldo_pendiente'])
											->condition('an.idadelanto', $idadelanto , '=')
											->execute()->fetchField();

                    $idliquidacion = $arrayKey[2];
                    $valor_conciliado = $value;
                    //debemos actualizar todas las solicitudes
                    $arrayFields=[];
                    $arrayFields["valor_conciliado"] = $valor_conciliado;
                    $arrayFields["fecha_hora_conciliacion"] = date("Y-m-d H:i:s");
                    $arrayFields["estado"] = "conciliado";

                    $update = $db->update('liquidaciones_an')
							->fields($arrayFields)
							->condition('idliquidacion', $idliquidacion , '=')
                            ->execute();
                    if($update > 0)
                    {
                        $message = "Liquidacion de AN #".$idadelanto." Conciliada : ".json_encode($arrayFields);
                        \Drupal::logger('conciliacionadelantos')->notice($message);

                        drupal_set_message($message);
                        
                        //actualizo el saldo del adelanto de nomina
                        $saldo_nuevo_pendiente = $saldo_pendiente - $valor_conciliado;
                        if($saldo_nuevo_pendiente <= 0)
                        {
                            $arrayAdelantoNomina["saldo_pendiente"] = 0;
                            $arrayAdelantoNomina["estado_liquidacion"] = "liquidado";
                            $arrayAdelantoNomina["fecha_hora_liquidacion"] = date("Y-m-d H:i:s");
                            $arrayAdelantoNomina["estado_general_solicitud"] = "liquidado";
                        }else{
                            $arrayAdelantoNomina["saldo_pendiente"] = $saldo_nuevo_pendiente;
                            $arrayAdelantoNomina["estado_liquidacion"] = "en_proceso_liquidacion";
                            $arrayAdelantoNomina["fecha_hora_liquidacion"] = date("Y-m-d H:i:s");
                            $arrayAdelantoNomina["estado_general_solicitud"] = "en_proceso_liquidacion";
                        }
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
                        }
                    }else{
                        $message = "Error Actualizacion Liquidacion de AN #".$idliquidacion." : ".json_encode($arrayFields);
                        \Drupal::logger('conciliacionadelantos')->error($message);

                        drupal_set_error($message);
                    }
                }
            }
            
        }


    }
}