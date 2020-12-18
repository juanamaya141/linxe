<?php
namespace Drupal\linxecredit\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\node\Entity\Node;
use Drupal\UtilitiesModule\Controller\UtilitiesController;


use Drupal\linxecredit\Libs\LinxeLibrary as LinxeLibrary;
use Drupal\linxecredit\Libs\AdelantoLibrary as AdelantoLibrary;
use Drupal\linxecredit\Libs\DecevalLibrary as DecevalLibrary;


/**
 * Class DisplayTableController.
 *
 * @package Drupal\mydata\Controller
 */
class DashboardAdelantoController extends ControllerBase
{
 
    public function getSeleccionAdelanto(){
        $session = \Drupal::request()->getSession();
        $config = $this->config('linxecredit.settings');
        $paththeme = base_path().drupal_get_path('theme', 'linxe');
        $basepath = base_path();

        $adelantonomina_rangos = $config->get('linxecredit.adelantonomina_rangos');
        $adelantonomina_montos_adelanto = $config->get('linxecredit.adelantonomina_montos_adelanto');
        $adelantonomina_montos_salario = $config->get('linxecredit.adelantonomina_montos_salario');
        $arrayOne = explode(",",$adelantonomina_rangos);
        $arrayDos = explode(",",$adelantonomina_montos_adelanto);
        $arrayTres = explode(",",$adelantonomina_montos_salario);

        $valor_min_an = $arrayDos[1];
        $canti = count($arrayOne);
        $canti2 = count($arrayDos);
        $canti3 = count($arrayTres);
        $valor_max_an = number_format($arrayDos[$canti2-1],0,",",".") ;
        $valor_med_default_an = $arrayTres[1];
        $valor_adelanto_default_an = number_format($arrayDos[1],0,",",".");
        $instruccionmsg = "";
        $linxelib = new LinxeLibrary();
        $adelantolib = new AdelantoLibrary();

        //validar sesion
        if($linxelib->validaVigenciaSesion()==false)
        {
            $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
            return new RedirectResponse($url);
        }


        
        
        $cargo_administracion_adelanto = $config->get('linxecredit.cargo_administracion_adelanto');
        $cargo_tecnologia_adelanto = $config->get('linxecredit.cargo_tecnologia_adelanto');
        $seguro_adelanto = $config->get('linxecredit.seguro_adelanto');
        $iva_adelanto = $config->get('linxecredit.iva_adelanto');
        

        $userObj = (object) array();
        

        if($session->has('tipodocumento') && $session->has('numerodocumento'))
        {
            $userObj->name = $session->get('nombres');
            $id_tipodoc = $session->get('tipodocumento');
            $num_id = $session->get('numerodocumento');
            $idregistro = $session->get('idregistro');
            
            $valormontoaprobado = $session->get('montoaprobado');
            $valormontoseleccionado = $session->get('montoseleccionado');
            $plazo = $session->get('plazo');
            $nombreempresa = $session->get('nombreempresa');
            $plazo = $session->get('plazo');
            $creditovigente = $session->get('creditovigente');
            $estatus = $session->get('estatus');
            $validaempresa = $session->get('validaempresa');
            $email = $session->get('email');
            $celular = $session->get('celular');
            $modificarSeleccion = $session->get('modificarSeleccion');

            $plazos=[];
            for($i=0;$i<3;$i++)
            {
              $plazos[$i] = $plazo;
            }
            $montos=[];

            $arr = []; //quito el 0 del array de adelantos de nomina
            foreach($arrayDos as $key=>$valor){
              if($valor!="0")
              {
                $arr[] = $valor;
              }
            }
            $cant = count($arr);
            

            for($i=0;$i<$cant;$i++)
            {
              if($valormontoaprobado == $arr[$i])
              {
                if($i==0){
                  $montos[0] = $arr[$i];
                  $montos[1] = $arr[$i+1];
                  $montos[2] = $arr[$i+2];
                }elseif($i==1){
                  $montos[0] = $arr[$i-1];
                  $montos[1] = $arr[$i];
                  $montos[2] = $arr[$i+1];
                }else{
                  $montos[0] = $arr[$i-2];
                  $montos[1] = $arr[$i-1];
                  $montos[2] = $arr[$i];
                }
                break;
              }
            }
            $returnArray = json_decode($adelantolib->getUserDataByIdRegistro($idregistro));
            $dataUser = $returnArray->userData;

            //en caso de que se tenga un crédito vigente que ya haya pasado previamente por el paso de selección y autorización hay que redireccionar hacia la pantalla de contrato
            if($creditovigente=="SI" && $modificarSeleccion!="SI")
            {
                /*$url = \Drupal\Core\Url::fromRoute('linxecredit.datosempresa')->toString();
                return new RedirectResponse($url);*/
                
            }else{
                //para que el modificarSeleccion = SI solo sirva por una sola vez
                $session->set('modificarSeleccion',"NO");
            }

            switch( $dataUser->estado_general_solicitud )
            {
              case "seleccion_monto": 
                  $url = \Drupal\Core\Url::fromRoute('linxecredit.datosempresa')->toString();
                  return new RedirectResponse($url);
                  break;
              case "validacion_desembolso": 
                  $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-desembolsoadelanto')->toString();
                  return new RedirectResponse($url);
                  break;
              case "solicitada": 
                  break;
              default: 
                $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-desembolsoadelanto')->toString();
                return new RedirectResponse($url);
              
             // default: 
             //     $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-desembolsoadelanto')->toString();
             //     return new RedirectResponse($url);
            }

            
        }else{
            $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
            return new RedirectResponse($url);
        }

        

        
        
       

        return [
            '#theme' => 'dashboard_adelanto',
            '#name' => "Selección de Adelanto de Nómina",
            '#userObj' => $userObj,
            '#paththeme' => $paththeme,
            '#basepath' => $basepath,
            '#valormontoaprobado' => $valormontoaprobado,
            '#instruccionmsg' => $instruccionmsg,
            '#attached' => [
              'library' => [
                'linxecredit/librarydashboard_adelanto', //include our custom library for this response
              ],
              'drupalSettings' => [
                'linxecredit' => [
                  'librarydashboard' => [
                    'cargo_administracion_adelanto'=> $cargo_administracion_adelanto,
                    'cargo_tecnologia_adelanto'=> $cargo_tecnologia_adelanto,
                    'seguro_adelanto'=> $seguro_adelanto,
                    'iva_adelanto'=> $iva_adelanto,
                    'cantidad_min'=> $valor_min_an,
                    'valormontoaprobado'=> $valormontoaprobado,
                    'plazos'=> implode(",",$plazos),
                    'montos'=> implode(",",$montos),
                    'adelantonomina_rangos'=> $adelantonomina_rangos,
                    'adelantonomina_montos_adelanto'=> $adelantonomina_montos_adelanto,
                    'adelantonomina_montos_salario'=> $adelantonomina_montos_salario,
                    'tipoid'=> $id_tipodoc,
                    'numid'=> $num_id,
                    'idregistro'=> $idregistro
                  ],
                ], 
              ]
            ]
          ];
    }


    public function getMisAdelantos(){
      $session = \Drupal::request()->getSession();
      $config = $this->config('linxecredit.settings');

      $paththeme = base_path().drupal_get_path('theme', 'linxe');
      $basepath = base_path();
      $misAdelantosArray = [];

      $adelantonomina_rangos = $config->get('linxecredit.adelantonomina_rangos');
      $adelantonomina_montos_adelanto = $config->get('linxecredit.adelantonomina_montos_adelanto');
      $adelantonomina_montos_salario = $config->get('linxecredit.adelantonomina_montos_salario');
      $arrayOne = explode(",",$adelantonomina_rangos);
      $arrayDos = explode(",",$adelantonomina_montos_adelanto);
      $arrayTres = explode(",",$adelantonomina_montos_salario);

      $valor_min_an = $arrayDos[1];
      $canti = count($arrayOne);
      $canti2 = count($arrayDos);
      $canti3 = count($arrayTres);
      $valor_max_an = number_format($arrayDos[$canti2-1],0,",",".") ;
      $valor_med_default_an = $arrayTres[1];
      $valor_adelanto_default_an = number_format($arrayDos[1],0,",",".");
      $instruccionmsg = "";
      $linxelib = new LinxeLibrary();
      $adelantolib = new AdelantoLibrary();

      //validar sesion
      if($linxelib->validaVigenciaSesion()==false)
      {
          $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
          return new RedirectResponse($url);
      }


      
      
      $cargo_administracion_adelanto = $config->get('linxecredit.cargo_administracion_adelanto');
      $cargo_tecnologia_adelanto = $config->get('linxecredit.cargo_tecnologia_adelanto');
      $seguro_adelanto = $config->get('linxecredit.seguro_adelanto');
      $iva_adelanto = $config->get('linxecredit.iva_adelanto');
      

      $userObj = (object) array();

      if($session->has('tipodocumento') && $session->has('numerodocumento'))
      {
        $userObj->name = $session->get('nombres');
        $id_tipodoc = $session->get('tipodocumento');
        $num_id = $session->get('numerodocumento');
        $valormontoaprobado = $session->get('montoaprobado');
        $valormontoseleccionado = $session->get('montoseleccionado');
        $plazo = $session->get('plazo');
        $nombreempresa = $session->get('nombreempresa');
        $plazo = $session->get('plazo');
        $creditovigente = $session->get('creditovigente');
        $estatus = $session->get('estatus');
        $validaempresa = $session->get('validaempresa');
        $email = $session->get('email');
        $celular = $session->get('celular');
        $modificarSeleccion = $session->get('modificarSeleccion');
      }else{
        $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
        return new RedirectResponse($url);
      }


      $dataField["tipoId"] = $id_tipodoc;
      $dataField["numId"] = $num_id;
      $respuestaArray = $adelantolib->getMisAdelantos($dataField);
      
      //print_r($respuestaArray);

      //if(is_array($respuestaArray))
      $i=0;
      foreach ($respuestaArray as $value) {
          
          $misAdelantosArray[$i]["numero_operacion"] = $value->idadelanto;
          
          
          
          $misAdelantosArray[$i]["valor_solicitado"] = number_format($value->valor_solicitado,0,",",".");
          $misAdelantosArray[$i]["administracion"] = number_format($value->administracion,0,",",".");
          $misAdelantosArray[$i]["tecnologia"] = number_format($value->tecnologia,0,",",".");
          $misAdelantosArray[$i]["seguros"] = number_format($value->seguros,0,",",".");
          $misAdelantosArray[$i]["iva"] = number_format($value->iva,0,",",".");
          $valorTotal = $value->valor_solicitado+$value->administracion+$value->tecnologia+$value->seguros+$value->iva;
          $misAdelantosArray[$i]["total_pagar"] = number_format($valorTotal,0,",",".");
          if($value->estado_general_solicitud!="pagado")
          {
            $misAdelantosArray[$i]["estado"] = "vigente";
            $misAdelantosArray[$i]["saldo"] = number_format($valorTotal,0,",",".");
          }else{
            $misAdelantosArray[$i]["estado"] = "pagado";
            $misAdelantosArray[$i]["saldo"] = number_format($value->saldo_pendiente,0,",",".");
          }
          if($value->estado_desembolso=="desembolsado")
          {
            $misAdelantosArray[$i]["tipo_fecha"] = "desembolso";
            $misAdelantosArray[$i]["fecha_solicitud"] = date("Y-m-d",strtotime($value->fecha_hora_desembolso));
          }else{
            $misAdelantosArray[$i]["tipo_fecha"] = "solicitud";
            $misAdelantosArray[$i]["fecha_solicitud"] = date("Y-m-d",strtotime($value->fecha_solicitud));
          }
          $i++;
      }
      
      
      return [
          '#theme' => 'dashboard_misadelantos',
          '#name' => "Dashboard Mis Adelantos",
          '#userObj' => $userObj,
          '#misAdelantosArray' => $misAdelantosArray,
          '#paththeme' => $paththeme,
          '#basepath' => $basepath,
          '#valormontoaprobado' => $valormontoaprobado,
          '#attached' => [
            'library' => [
              'linxecredit/librarymisadelantos', //include our custom library for this response
            ],
            'drupalSettings' => [
              'linxecredit' => [
                'librarymisadelantos' => [
                  'misAdelantosArray'=> $misAdelantosArray
                ],
              ], 
            ]
            
          ]
        ];
    }

    public function getConsultaPagare(){
      $config = $this->config('linxecredit.settings');
      $urlws_deceval = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_deceval') ; 
      $deceval_codigodepositante = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_codigodepositante');
      $deceval_usuario = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_usuario');
      $deceval_identificacionemisor = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_identificacionemisor');
        
      $decevallib = new DecevalLibrary();

      //$decevallib->getconsultarPagares();

      $correoElectronico = "yuosbo0811@gmail.com";
      $fechaExpedicion = "2004-03-16";
      $idTipoDocumento = 1;
      $numeroDocumento = "1012318544";
      $nombres = "Yurley";
      $primer_apellido = "Osorio";
      $segundo_apellido = "Botero";
      $telefono = "3174603758";

      $arrayCrearGirador = [];
      //params
      $arrayCrearGirador["urlws_deceval"] = $urlws_deceval;
      $arrayCrearGirador["deceval_codigodepositante"] = $deceval_codigodepositante;
      $arrayCrearGirador["deceval_usuario"] = $deceval_usuario;
      $arrayCrearGirador["deceval_identificacionemisor"] = $deceval_identificacionemisor;
      //fields
      $arrayCrearGirador["correoElectronico"] = "yuosbo0811@gmail.com";
      $arrayCrearGirador["fechaExpedicion"] = "2004-03-16";
      $arrayCrearGirador["idTipoDocumento"] = 1;
      $arrayCrearGirador["numeroDocumento"] = "1012318544";
      $arrayCrearGirador["nombres"] = "Yurley";
      $arrayCrearGirador["primer_apellido"] = "Osorio";
      $arrayCrearGirador["segundo_apellido"] = "Botero";
      $arrayCrearGirador["telefono"] = "3174603758";


      $arrayCreateGirador = $decevallib->crearGirador($arrayCrearGirador);
      //$arrayCreateGirador = $decevallib->crearGirador($correoElectronico,$fechaExpedicion,$idTipoDocumento,$numeroDocumento,$nombres,$primer_apellido,$segundo_apellido,$telefono);
      print_r($arrayCreateGirador);

      echo $arrayCreateGirador->return->codigoError;
      exit();
    }

    public function getSendOTP(){
      $config = $this->config('linxecredit.settings');
      $twilio_sid = $config->get('linxecredit.twilio_sid');
      $twilio_token = $config->get('linxecredit.twilio_token');
      $twilio_phonenumber = $config->get('linxecredit.twilio_phonenumber');
      $adelantolib = new AdelantoLibrary();
      $respuestaArray = $adelantolib->sendOTP(33,16,$twilio_sid,$twilio_token,$twilio_phonenumber);

      print_r($respuestaArray);
      exit();
    }


    public function getContratoAdelanto(){
      $session = \Drupal::request()->getSession();
      $config = $this->config('linxecredit.settings');
      $paththeme = base_path().drupal_get_path('theme', 'linxe');
      $basepath = base_path();

      $linxelib = new LinxeLibrary();
      $adelantolib = new AdelantoLibrary();
      //validar sesion
      if($linxelib->validaVigenciaSesion()==false)
      {
          $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
          return new RedirectResponse($url);
      }

      $userObj = (object) array();
      
      if($session->has('tipodocumento') && $session->has('numerodocumento'))
      {
        $userObj->name = $session->get('nombres');
        $id_tipodoc = intval($session->get('tipodocumento'));
        $num_id = intval($session->get('numerodocumento'));
        
        $creditovigente = $session->get('creditovigente');
        $estatus = $session->get('estatus');
        $validaempresa = $session->get('validaempresa');
        $email = $session->get('email');
        $celular = $session->get('celular');
        $modificarSeleccion = $session->get('modificarSeleccion');

        $idregistro = $session->get('idregistro');

        $url_pdf = "/servicios/getpdfpagare";

        $returnArray = json_decode($adelantolib->getUserDataByIdRegistro($idregistro));
        $dataUser = $returnArray->userData;
        $valormontoaprobado = $dataUser->monto_maximo_aprobado;
        $valormontoseleccionado = $dataUser->valor_solicitado;
        $numPagareEntidad = $dataUser->numpagareentidad;

        $token_gen = substr(md5("fdfDSR32".$num_id."fVe".$id_tipodoc),0,6) ;


        //url con visor de google. 
        $dominioactual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        
        $useragent=$_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
        { 
            //echo "mobile";
            $url_escape = urlencode($dominioactual."/servicios/getpdfpagare?t=".$id_tipodoc."&n=".$num_id."&tk=".$token_gen."&pag=".$numPagareEntidad);
            $url_pdf = str_replace(" ", "", "https://drive.google.com/viewerng/viewer?embedded=true&url=".$url_escape);   
        }else{
            //echo "desktop";
            $url_pdf = str_replace(" ", "", "/servicios/getpdfpagare?t=".$id_tipodoc."&n=".$num_id."&tk=".$token_gen."&pag=".$numPagareEntidad);
        }
      }else{
          $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
          return new RedirectResponse($url);
      }

      $instruccionmsg = "A continuación te mostramos que vas a firmar de forma electrónica, lee detenidamente el contenido, y si estas de acuerdo da clic en el botón HE LEÍDO y FIRMO.";

      return [
          '#theme' => 'dashboard_contrato_adelanto',
          '#name' => "Dashboard Pagaré Adelanto",
          '#userObj' => $userObj,
          '#paththeme' => $paththeme,
          '#basepath' => $basepath,
          '#valorseleccionado' => $valormontoseleccionado,
          '#urlpdf' => $url_pdf,
          '#num_pagare' => $numPagareEntidad,
          '#idregistro' => $idregistro,
          '#instruccionmsg' => $instruccionmsg,
          '#attached' => [
            'library' => [
              'linxecredit/librarypagare', //include our custom library for this response
            ],
            'drupalSettings' => [
              'linxecredit' => [
                'librarypagare' => [
                  'document_type'=> $id_tipodoc,
                  'document_number'=> $num_id,
                  'num_pagare'=> $numPagareEntidad,
                  'idregistro'=> $idregistro,
                  'urlpdf'=> $url_pdf
                ],
              ], 
            ]
            
          ]
        ];
    }


    public function getDesembolsoAdelanto(){
      $session = \Drupal::request()->getSession();
      $config = $this->config('linxecredit.settings');
      $paththeme = base_path().drupal_get_path('theme', 'linxe');
      $basepath = base_path();

      $linxelib = new LinxeLibrary();
      $adelantolib = new AdelantoLibrary();
      //validar sesion
      if($linxelib->validaVigenciaSesion()==false)
      {
          $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
          return new RedirectResponse($url);
      }

      $userObj = (object) array();
      
      if($session->has('tipodocumento') && $session->has('numerodocumento'))
      {
        $userObj->name = $session->get('nombres');
        $id_tipodoc = intval($session->get('tipodocumento'));
        $num_id = intval($session->get('numerodocumento'));
        
        $creditovigente = $session->get('creditovigente');
        $estatus = $session->get('estatus');
        $validaempresa = $session->get('validaempresa');
        $email = $session->get('email');
        $celular = $session->get('celular');
        $modificarSeleccion = $session->get('modificarSeleccion');

        $idregistro = $session->get('idregistro');
        $convenio_empresa = $session->get('convenio_empresa');

        
        $returnArray = json_decode($adelantolib->getUserDataByIdRegistro($idregistro));
        $dataUser = $returnArray->userData;
        $idadelanto = $dataUser->idadelanto;
        $valormontoaprobado = $dataUser->monto_maximo_aprobado;
        $valormontoseleccionado = $dataUser->valor_solicitado;

        switch( $dataUser->estado_general_solicitud )
        {
          case "solicitada": 
            $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-adelanto')->toString();
            return new RedirectResponse($url);
            break;
          case "seleccion_monto": 
              $url = \Drupal\Core\Url::fromRoute('linxecredit.datosempresa')->toString();
              return new RedirectResponse($url);
              break;
          
        }
        
      }else{
          $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
          return new RedirectResponse($url);
      }
      
      if($convenio_empresa != "si")
      {
        $imagenmsg = "/themes/custom/linxe/images/dashboard/exito.jpg";
        $textomsg = html_entity_decode(str_replace("{{username}}", $userObj->name, "Gracias {{username}}. <br/>"));
        $textomsg2 = html_entity_decode( "Tu solicitud de Adelanto de Salario ha sido preaprobada , ahora debemos firmar el convenio con tu empresa, pronto nos comunicaremos contigo." );
        
        $instruccionmsg = ""; 
      }else{
        $imagenmsg = "/themes/custom/linxe/images/dashboard/exito.jpg";
        $textomsg = html_entity_decode(str_replace("{{username}}", $userObj->name, "Felicitaciones {{username}}.<br/>"));
        $textomsg2 = html_entity_decode("Tu solicitud de Adelanto de Salario ha sido aprobada.");
        $instruccionmsg = ""; 
      }
      
      


      return [
          '#theme' => 'dashboard_desembolsoadelanto',
          '#name' => "Dashboard Desembolso Adelanto",
          '#userObj' => $userObj,
          '#paththeme' => $paththeme,
          '#basepath' => $basepath,
          '#valorseleccionado' => $valormontoseleccionado,
          '#imagenmsg' => $imagenmsg,
          '#textomsg' => $textomsg,
          '#textomsg2' => $textomsg2,
          '#instruccionmsg' => $instruccionmsg,
          '#attached' => [
            'library' => [
              'linxecredit/librarydesembolsoadelanto', //include our custom library for this response
            ]
            
          ]
        ];
    }


    public function creacionMasivaEmpresasDataScoring() {

      $arrayEmpresas = [];
      $linxelib = new LinxeLibrary();

      $database = \Drupal::database();

      // registro
      $db_or = db_or();
      $db_or->condition('em.estado_creacion_datascoring', "no_creado", "=");
      $db_or->isNull('em.estado_creacion_datascoring');

      $query = $database->select('empresas', 'em');
      $result = $query->condition('em.convenio_tipoproducto', "adelanto" , '=')
                  ->condition('em.estado_convenio', "aceptado" , '=')
                  ->condition($db_or)
                  ->fields('em')
                  ->execute();
      //print_r($result);
      // Populate the rows.
      $rows = array();

      foreach ($result as $empresa) {

        //creacion de la empresa seleccionada en datascoring
        $dataFieldEmpresa = [];
        $dataFieldEmpresa["NitEmpresa"] = $empresa->identificacion;
        $dataFieldEmpresa["RazonSocial"] = $empresa->razon_social;
        $objEmpresaDatascoring = $linxelib->createEmpresaDatascoring($dataFieldEmpresa);

        
        $estado = "";
        if($objEmpresaDatascoring->Success==true )//1->creada satisfactorimente ;  4-> La empresa ya se encuentra registrada
        {
          $arraySpResultEmpresa = explode(";",$objEmpresaDatascoring->SpResult);
          if( $arraySpResultEmpresa[0]==1   )
          {
            $estado = "creado";
          }else if( $arraySpResultEmpresa[0]==4 ){
            $estado = "ya_existe";
          }else{
            $estado = "no_creado";
          }
        }else{
          $estado = "no_creado";
        } 
        $rows[] = array(
          'razon_social'=>    $empresa->razon_social,
          'nit' => $empresa->identificacion,
          'respuesta' => json_encode($objEmpresaDatascoring),
          'estado' => $estado
        );

        //una vez procesada la facturación debemos actualizar el adelanto de nomina correspondiente
        $arrayUpdate["estado_creacion_datascoring"] = $estado;
        $arrayUpdate["respuesta_creacion_datascoring"] = json_encode($objEmpresaDatascoring);
        $arrayUpdate["fecha_creacion_datascoring"] = date("Y-m-d H:i:s");

        $update  = $database->update('empresas')
                              ->fields($arrayUpdate)
                              ->condition('idempresa', $empresa->idempresa , '=')
                              ->execute();
        
      }
      
      $header_table = array(
        'razon_social'=>    t('Razon Social'),
        'nit' => t('NIT'),
        'respuesta' => t('RESPUESTA'),
        'estado' => t('Estado Creación')
      );
   
      // The table description.
      $build = array(
        //'#markup' => t('Listar todas')
      );
   
      // Generate the table.
      $build['config_table'] = array(
        '#theme' => 'table',
        '#prefix' => '<div id="people">'."<h1>Empresas de Adelanto de Salario Creadas en Datascoring</h1>",
        '#suffix' => '</div>',
        '#header' => $header_table,
        '#rows' => $rows,
      );
   
      // Finally add the pager.
      $build['pager'] = array(
        '#type' => 'pager'
      );
   
      return $build;
    }


    public function creacionMasivaUsuariosDataScoring() {

      $arrayEmpresas = [];
      $linxelib = new LinxeLibrary();

      $database = \Drupal::database();

      // registro
      $db_or = db_or();
      $db_or->condition('reg.estado_creacion_datascoring', "no_creado", "=");
      $db_or->isNull('reg.estado_creacion_datascoring');

      $query = $database->select('registrados_an','reg');
      $query->join('empresas', 'emp', 'reg.idempresa = emp.idempresa');
      $query->fields('reg');
      $query->fields('emp',["tipo_identificacion","identificacion","razon_social"]);
      //$query->condition('emp.estado_convenio', "aceptado" , '=');
      $query->condition($db_or);
      $query->range(0, 100);
      
      $result = $query->execute();
      //print_r($result);
      // Populate the rows.
      $rows = array();

      foreach ($result as $registrado) {

        //creacion del usuario en datascoring
        $dataField = [];
        $dataField["PrimerNombre"] = $registrado->nombre;
        $dataField["PrimerApellido"] = $registrado->primer_apellido;
        $dataField["SegundoApellido"] = $registrado->segundo_apellido;
        $dataField["TipoId"] = $registrado->tipodocumento;
        $dataField["Numid"] = $registrado->documento;
        $dataField["Celular"] = $registrado->celular;
        $dataField["Mail"] = $registrado->email;
        $newDate2 = date("d/m/Y", strtotime($registrado->fecha_expedicion));
        $dataField["FechaExp"] = $newDate2;
        $dataField["Acteconomic"] = $registrado->actividad_economica;
        $dataField["NitEmp"] = $registrado->identificacion;
        $dataField["Origen"] = "Página Web";
        
        $objRegisterDatascoring = $linxelib->getRegisterWithoutValidation($dataField);



        $estado = "";
        if($objRegisterDatascoring->Success==true )//1->creada satisfactorimente ;  4-> La empresa ya se encuentra registrada
        {
          $arraySpResultEmpleado = explode(";",$objRegisterDatascoring->SpResult);
          if( $arraySpResultEmpleado[0]==1   )
          {
            $estado = "creado";
          }else if( $arraySpResultEmpleado[0]==4 ){
            $estado = "ya_existe";
          }else{
            $estado = "no_creado";
          }
        }else{
          $estado = "no_creado";
        } 
        $rows[] = array(
          'nombre'=>    $registrado->nombre,
          'primer_apellido'=>    $registrado->primer_apellido,
          'segundo_apellido'=>    $registrado->segundo_apellido,
          'tipodocumento' => $registrado->tipodocumento,
          'documento' => $registrado->documento,
          'estado' => $estado
        );

        //una vez procesada la facturación debemos actualizar el adelanto de nomina correspondiente
        $arrayUpdate["estado_creacion_datascoring"] = $estado;
        $arrayUpdate["respuesta_creacion_datascoring"] = json_encode($objRegisterDatascoring);
        $arrayUpdate["fecha_creacion_datascoring"] = date("Y-m-d H:i:s");

        $update  = $database->update('registrados_an')
                              ->fields($arrayUpdate)
                              ->condition('idregistro', $registrado->idregistro , '=')
                              ->execute();
        
      }
      
      $header_table = array(
        'nombre'=>    t('Nombre'),
        'primer_apellido'=>  t('Primer Apellido'),
        'segundo_apellido'=>  t('Segundo Apellido'),
        'tipodocumento' => t('Tipo Documento'),
        'documento' => t('Documento'),
        'estado' => t('Estado Creación')
      );
   
      // The table description.
      $build = array(
        //'#markup' => t('Listar todas')
      );
   
      // Generate the table.
      $build['config_table'] = array(
        '#theme' => 'table',
        '#prefix' => '<div id="people">'."<h1>Usuarios de Adelanto de Salario Creados en Datascoring</h1>",
        '#suffix' => '</div>',
        '#header' => $header_table,
        '#rows' => $rows,
      );
   
      // Finally add the pager.
      $build['pager'] = array(
        '#type' => 'pager'
      );
   
      return $build;
    }

}
