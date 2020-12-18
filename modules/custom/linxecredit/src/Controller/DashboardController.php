<?php
namespace Drupal\linxecredit\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Drupal\node\Entity\Node;
use Drupal\UtilitiesModule\Controller\UtilitiesController;


use Drupal\linxecredit\Libs\LinxeLibrary as LinxeLibrary;


/**
 * Class DisplayTableController.
 *
 * @package Drupal\mydata\Controller
 */
class DashboardController extends ControllerBase
{
 
    public function getSeleccionCredito(){
        $session = \Drupal::request()->getSession();
        $config = $this->config('linxecredit.settings');
        $paththeme = base_path().drupal_get_path('theme', 'linxe');
        $basepath = base_path();

        $linxelib = new LinxeLibrary();


        //validar sesion
        if($linxelib->validaVigenciaSesion()==false)
        {
            $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
            return new RedirectResponse($url);
        }


        $gl_cantidad_min = $config->get('linxecredit.cantidad_min');
        $gl_cantidad_max = $config->get('linxecredit.cantidad_max');
        $gl_meses_min = $config->get('linxecredit.meses_min');
        $gl_meses_max = $config->get('linxecredit.meses_max');
        
        $gl_seguro = $config->get('linxecredit.seguro');
        $gl_cuota = 0;
        $iva = $config->get('linxecredit.iva');
        $cargo_tecnologia = $config->get('linxecredit.cargo_tecnologia');
        $plazos = $config->get('linxecredit.plazos');
        $rangomontos = $config->get('linxecredit.rangomontos');

        $userObj = (object) array();
        

        if($session->has('tipodocumento') && $session->has('numerodocumento'))
        {
            if($session->get('tipoproducto')=="adelanto")
            {
              $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-adelanto')->toString();
              return new RedirectResponse($url);
            }

            $userObj->name = $session->get('nombres');
            $id_tipodoc = $session->get('tipodocumento');
            $num_id = $session->get('numerodocumento');
            $valormontoaprobado = $session->get('montoaprobado');
            $valormontoseleccionado = $session->get('montoseleccionado');
            $plazo = $session->get('plazo');
            $creditovigente = $session->get('creditovigente');
            $estatus = $session->get('estatus');
            $validaempresa = $session->get('validaempresa');
            $email = $session->get('email');
            $celular = $session->get('celular');
            $modificarSeleccion = $session->get('modificarSeleccion');

            if($session->has('tasa') && $session->get('tasa')!="")
              $gl_tasa = $session->get('tasa');
            else
              $gl_tasa = $config->get('linxecredit.tasa');

            //en caso de que se tenga un crédito vigente que ya haya pasado previamente por el paso de selección y autorización hay que redireccionar hacia la pantalla de contrato
            if($creditovigente=="SI" && $modificarSeleccion!="SI")
            {
                $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-contrato')->toString();
                return new RedirectResponse($url);
            }else{
                //para que el modificarSeleccion = SI solo sirva por una sola vez
                $session->set('modificarSeleccion',"NO");
            }

            
        }else{
            $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
            return new RedirectResponse($url);
        }

        

        
        
       

        return [
            '#theme' => 'dashboard_seleccion',
            '#name' => "Selección de Crédito",
            '#userObj' => $userObj,
            '#paththeme' => $paththeme,
            '#basepath' => $basepath,
            '#valormontoaprobado' => $valormontoaprobado,
            '#instruccionmsg' => $instruccionmsg,
            '#attached' => [
              'library' => [
                'linxecredit/librarydashboard', //include our custom library for this response
              ],
              'drupalSettings' => [
                'linxecredit' => [
                  'librarydashboard' => [
                    'gl_cantidad_min'=> $gl_cantidad_min,
                    'gl_cantidad_max'=> $gl_cantidad_max,
                    'gl_meses_min'=> $gl_meses_min,
                    'gl_meses_max'=> $gl_meses_max,
                    'gl_tasa'=> $gl_tasa,
                    'gl_seguro'=> $gl_seguro,
                    'gl_cuota'=> $gl_cuota,
                    'iva'=> $iva,
                    'cargo_tecnologia'=> $cargo_tecnologia,
                    'plazos'=> $plazos,
                    'rangomontos'=> $rangomontos,
                    'valormontoaprobado'=> $valormontoaprobado,
                    'tipoid'=> $id_tipodoc,
                    'numid'=> $num_id
                  ],
                ], 
              ]
            ]
          ];
    }

    public function getRegresarSeleccion(){
        $session = \Drupal::request()->getSession();
        if($session->has('tipodocumento') && $session->has('numerodocumento'))
        {
            $session->set('modificarSeleccion',"SI");
            //
            $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-seleccion')->toString();
        }else{
            $url = \Drupal\Core\Url::fromRoute('<front>')->toString(); 
        }
        return new RedirectResponse($url);
    }


    public function getContrato(){
        $session = \Drupal::request()->getSession();
        $config = $this->config('linxecredit.settings');

        $url_pdf_contrato = $config->get('linxecredit.url_pdf_contrato');
        
        $file = \Drupal\file\Entity\File::load($url_pdf_contrato[0]);
        $uri = $file->getFileUri();
        //$url_pdf = \Drupal\Core\Url::fromUri(file_create_url($uri))->toString();

        $linxelib = new LinxeLibrary();
        
        //validar sesion
        if($linxelib->validaVigenciaSesion()==false)
        {
            $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
            return new RedirectResponse($url);
        }
        
        
        $paththeme = base_path().drupal_get_path('theme', 'linxe');
        $basepath = base_path();

        $gl_cantidad_min = $config->get('linxecredit.cantidad_min');
        $gl_cantidad_max = $config->get('linxecredit.cantidad_max');
        $gl_meses_min = $config->get('linxecredit.meses_min');
        $gl_meses_max = $config->get('linxecredit.meses_max');
        
        $gl_seguro = $config->get('linxecredit.seguro');
        $gl_cuota = 0;
        $iva = $config->get('linxecredit.iva');
        $cargo_tecnologia = $config->get('linxecredit.cargo_tecnologia');
        $plazos = $config->get('linxecredit.plazos');

        $userObj = (object) array();

        if($session->has('tipodocumento') && $session->has('numerodocumento'))
        {
            if($session->get('tipoproducto')=="adelanto")
            {
              $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-adelanto')->toString();
              return new RedirectResponse($url);
            }
            $userObj->name = $session->get('nombres');
            $id_tipodoc = intval($session->get('tipodocumento'));
            $num_id = intval($session->get('numerodocumento'));
            $valormontoaprobado = $session->get('montoaprobado');
            $valormontoseleccionado = $session->get('montoseleccionado');
            $valorcantidadseleccionada = $session->get('cantidadseleccionada');
            $plazo = $session->get('plazo');
            $creditovigente = $session->get('creditovigente');
            $estatus = $session->get('estatus');
            $validaempresa = $session->get('validaempresa');
            $email = $session->get('email');
            $celular = $session->get('celular');
            $modificarSeleccion = $session->get('modificarSeleccion');

            if($session->has('tasa') && $session->get('tasa')!="")
              $gl_tasa = $session->get('tasa');
            else
              $gl_tasa = $config->get('linxecredit.tasa');

            $url_pdf = "/servicios/getpdfcontrato";

            $token_gen = substr(md5("fdfDSR32".$num_id."fVe".$id_tipodoc),0,6) ;


            //url con visor de google. 
            $dominioactual = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
            
            $useragent=$_SERVER['HTTP_USER_AGENT'];
        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))

            { 
                //echo "mobile";
                $url_escape = urlencode($dominioactual."/servicios/getpdfcontrato?t=".$id_tipodoc."&n=".$num_id."&tk=".$token_gen);
                
                $url_pdf = str_replace(" ", "", "https://drive.google.com/viewerng/viewer?embedded=true&url=".$url_escape);
                //echo $url_pdf;
            }
            else{
                //echo "desktop";

                $url_pdf = str_replace(" ", "", "/servicios/getpdfcontrato?t=".$id_tipodoc."&n=".$num_id."&tk=".$token_gen);
                //$url_escape = urlencode($dominioactual."/servicios/getpdfcontrato?t=".intval($id_tipodoc)."&n=".intval($num_id)."&tk=".$token_gen);
                //echo $token_gen;
                //$url_pdf = str_replace(" ", "", "https://drive.google.com/viewerng/viewer?embedded=true&url=".$url_escape);
                //echo $url_pdf;
            }

        }else{
            $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
            return new RedirectResponse($url);
        }


        


        $valorseleccionado = $valorcantidadseleccionada ;
        $plazoseleccionado = $plazo;
        $instruccionmsg = "A continuación te mostramos que vas a firmar de forma electrónica, lee detenidamente el contenido, y si estas de acuerdo da clic en el botón HE LEÍDO y FIRMO.";

        return [
            '#theme' => 'dashboard_contrato',
            '#name' => "Dashboard Contrato",
            '#userObj' => $userObj,
            '#paththeme' => $paththeme,
            '#basepath' => $basepath,
            '#valorseleccionado' => $valorseleccionado,
            '#plazoseleccionado' => $plazoseleccionado,
            '#urlpdf' => $url_pdf,
            '#instruccionmsg' => $instruccionmsg,
            '#attached' => [
              'library' => [
                'linxecredit/librarycontrato', //include our custom library for this response
              ],
              'drupalSettings' => [
                'linxecredit' => [
                  'librarycontrato' => [
                    'document_type'=> $id_tipodoc,
                    'document_number'=> $num_id,
                    'urlpdf'=> $url_pdf
                  ],
                ], 
              ]
              
            ]
          ];
    }


    public function getDesembolso(){
        $session = \Drupal::request()->getSession();
        $config = $this->config('linxecredit.settings');

        $paththeme = base_path().drupal_get_path('theme', 'linxe');
        $basepath = base_path();

        $linxelib = new LinxeLibrary();
        
        //validar sesion
        if($linxelib->validaVigenciaSesion()==false)
        {
            $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
            return new RedirectResponse($url);
        }

        $gl_cantidad_min = $config->get('linxecredit.cantidad_min');
        $gl_cantidad_max = $config->get('linxecredit.cantidad_max');
        $gl_meses_min = $config->get('linxecredit.meses_min');
        $gl_meses_max = $config->get('linxecredit.meses_max');
        
        $gl_seguro = $config->get('linxecredit.seguro');
        $gl_cuota = 0;
        $iva = $config->get('linxecredit.iva');
        $cargo_tecnologia = $config->get('linxecredit.cargo_tecnologia');
        $plazos = $config->get('linxecredit.plazos');

        $userObj = (object) array();

        if($session->has('tipodocumento') && $session->has('numerodocumento'))
        {
            if($session->get('tipoproducto')=="adelanto")
            {
              $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-adelanto')->toString();
              return new RedirectResponse($url);
            }
            $userObj->name = $session->get('nombres');
            $id_tipodoc = $session->get('tipodocumento');
            $num_id = $session->get('numerodocumento');
            $valormontoaprobado = $session->get('montoaprobado');
            $valormontoseleccionado = $session->get('montoseleccionado');
            $valorcantidadseleccionada = $session->get('cantidadseleccionada');
            $plazo = $session->get('plazo');
            $creditovigente = $session->get('creditovigente');
            $estatus = $session->get('estatus');
            $validaempresa = $session->get('validaempresa');
            $email = $session->get('email');
            $celular = $session->get('celular');
            $modificarSeleccion = $session->get('modificarSeleccion');
            $tipo_desembolso = $session->get('tipo_desembolso');

            if($session->has('tasa') && $session->get('tasa')!="")
              $gl_tasa = $session->get('tasa');
            else
              $gl_tasa = $config->get('linxecredit.tasa');

        }else{
            $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
            return new RedirectResponse($url);
        }
        

        $valorseleccionado = $valorcantidadseleccionada;
        $plazoseleccionado = $plazo;

        if($tipo_desembolso == "Movii")
        {
            if($validaempresa == "SI")
            {
                $textomsg = str_replace("{{username}}", $userObj->name, $config->get('linxecredit.endtitlemsg_con_validacion_empresa_movi'));
                $instruccionmsg = str_replace("{{username}}", $userObj->name, $config->get('linxecredit.endmsg_con_validacion_empresa_movi'));
            }else{
                $textomsg = str_replace("{{username}}", $userObj->name, $config->get('linxecredit.endtitlemsg_sin_validacion_empresa_movi'));
                $instruccionmsg = str_replace("{{username}}", $userObj->name, $config->get('linxecredit.endmsg_sin_validacion_empresa_movi')); 
            }
        }else if($tipo_desembolso == "Cta Nomina"){ //nomina
            if($validaempresa == "SI")
            {
                $textomsg = str_replace("{{username}}", $userObj->name, $config->get('linxecredit.endtitlemsg_con_validacion_empresa'));
                $instruccionmsg = str_replace("{{username}}", $userObj->name, $config->get('linxecredit.endmsg_con_validacion_empresa'));
            }else{
                $textomsg = str_replace("{{username}}", $userObj->name, $config->get('linxecredit.endtitlemsg_sin_validacion_empresa'));
                $instruccionmsg = str_replace("{{username}}", $userObj->name, $config->get('linxecredit.endmsg_sin_validacion_empresa')); 
            }
        }
        

        $imagenmsg = "/themes/custom/linxe/images/dashboard/exito.jpg";

        /* Ahora como finalizamos el proceso la idea es actualizar las variables para que vuelva a comenzar el flujo*/
        $nuevo_montoaprobado = 0;
        $nuevo_montoseleccionado = 0;
        $nuevo_plazo = 0;
        $nuevo_creditovigente = "NO";
        $nuevo_modificarSeleccion = "NO";
        $nuevo_tipo_desembolso = "";

        $session->set('montoaprobado', $nuevo_montoaprobado);
        $session->set('montoseleccionado', $nuevo_montoseleccionado);
        $session->set('plazo', $nuevo_plazo);
        $session->set('creditovigente', $nuevo_creditovigente);
        $session->set('modificarSeleccion', $nuevo_modificarSeleccion);
        $session->set('tipo_desembolso', $nuevo_tipo_desembolso);


        return [
            '#theme' => 'dashboard_desembolso',
            '#name' => "Dashboard Desembolso",
            '#userObj' => $userObj,
            '#paththeme' => $paththeme,
            '#basepath' => $basepath,
            '#valorseleccionado' => $valorseleccionado,
            '#plazoseleccionado' => $plazoseleccionado,
            '#imagenmsg' => $imagenmsg,
            '#textomsg' => $textomsg,
            '#instruccionmsg' => $instruccionmsg,
            '#attached' => [
              'library' => [
                'linxecredit/librarydesembolso', //include our custom library for this response
              ]
              
            ]
          ];
    }

    public function getMisCreditos(){
        $session = \Drupal::request()->getSession();
        $config = $this->config('linxecredit.settings');

        $paththeme = base_path().drupal_get_path('theme', 'linxe');
        $basepath = base_path();
        $misCreditosArray = [];

        $linxelib = new LinxeLibrary();
        
        //validar sesion
        if($linxelib->validaVigenciaSesion()==false)
        {
            $url = \Drupal\Core\Url::fromRoute('linxecredit.cerrar-sesion')->toString();
            return new RedirectResponse($url);
        }

        $gl_cantidad_min = $config->get('linxecredit.cantidad_min');
        $gl_cantidad_max = $config->get('linxecredit.cantidad_max');
        $gl_meses_min = $config->get('linxecredit.meses_min');
        $gl_meses_max = $config->get('linxecredit.meses_max');
        
        $gl_seguro = $config->get('linxecredit.seguro');
        $gl_cuota = 0;
        $iva = $config->get('linxecredit.iva');
        $cargo_tecnologia = $config->get('linxecredit.cargo_tecnologia');
        $plazos = $config->get('linxecredit.plazos');

        $userObj = (object) array();

        if($session->has('tipodocumento') && $session->has('numerodocumento'))
        {
            if($session->get('tipoproducto')=="adelanto")
            {
              $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-adelanto')->toString();
              return new RedirectResponse($url);
            }
            $userObj->name = $session->get('nombres');
            $id_tipodoc = $session->get('tipodocumento');
            $num_id = $session->get('numerodocumento');
            $valormontoaprobado = $session->get('montoaprobado');
            $valormontoseleccionado = $session->get('montoseleccionado');
            $plazo = $session->get('plazo');
            $creditovigente = $session->get('creditovigente');
            $estatus = $session->get('estatus');
            $validaempresa = $session->get('validaempresa');
            $email = $session->get('email');
            $celular = $session->get('celular');
            $modificarSeleccion = $session->get('modificarSeleccion');

            if($session->has('tasa') && $session->get('tasa')!="")
              $gl_tasa = $session->get('tasa');
            else
              $gl_tasa = $config->get('linxecredit.tasa');

        }else{
            $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
            return new RedirectResponse($url);
        }
        $valormontoaprobado = $valormontoaprobado;


        $dataField["tipoId"] = $id_tipodoc;
        $dataField["numId"] = $num_id;
        $respuestaArray = $linxelib->getMisCreditos($dataField);
        


        if(array_key_exists('Success', $respuestaArray))
        {
            if($respuestaArray->Success==true){

                $creditosArray = $respuestaArray->SpResult->Table;
                
                $i=0;
                //print_r($creditosArray);
                if(is_array($creditosArray))
                foreach ($creditosArray as $key => $value) {
                    
                    $misCreditosArray[$i]["numero_operacion"] = $value->NumeroOperacion;
                    if($value->Estado=="CERRADA")
                        $misCreditosArray[$i]["estado"] = "pagado";
                    else
                        $misCreditosArray[$i]["estado"] = "vigente";
                    $misCreditosArray[$i]["fecha_desembolso"] = $value->FechaDesembolso;
                    $misCreditosArray[$i]["tasa"] = $value->TasaNA;
                    $misCreditosArray[$i]["cuota"] = number_format($value->Cuota,0,",",".");
                    $misCreditosArray[$i]["total_desembolso"] = number_format($value->TotalDesembolsado,0,",",".");
                    $misCreditosArray[$i]["total_pagar"] = number_format($value->TotalPagado,0,",",".");
                    $misCreditosArray[$i]["cuotas_total"] = $value->NumeroCuotas;
                    $misCreditosArray[$i]["cuotas_pendientes"] = $value->CuotasPendientes;
                    $misCreditosArray[$i]["saldo"] = number_format($value->SaldoCapital,0,",",".");
                    $i++;
                }


            }
        }
        
        
        return [
            '#theme' => 'dashboard_miscreditos',
            '#name' => "Dashboard Mis Créditos",
            '#userObj' => $userObj,
            '#misCreditosArray' => $misCreditosArray,
            '#paththeme' => $paththeme,
            '#basepath' => $basepath,
            '#valormontoaprobado' => $valormontoaprobado,
            '#attached' => [
              'library' => [
                'linxecredit/librarymiscreditos', //include our custom library for this response
              ],
              'drupalSettings' => [
                'linxecredit' => [
                  'librarymiscreditos' => [
                    'misCreditosArray'=> $misCreditosArray
                  ],
                ], 
              ]
              
            ]
          ];
    }


    public function cerrarSesion(){
        $session = \Drupal::request()->getSession();
        
        $session->remove('tipodocumento');
        $session->remove('numerodocumento');
        $session->remove('nombres');
        $session->remove('montoaprobado');
        $session->remove('montoseleccionado');
        $session->remove('plazo');
        $session->remove('creditovigente');
        $session->remove('estatus');
        $session->remove('validaempresa');
        $session->remove('email');
        $session->remove('celular');
        $session->remove('nombreempresa');
        $session->remove('modificarSeleccion');
        $session->remove('showPreferenciaForm');
        $session->remove('monto_temp');
        $session->remove('plazo_temp');
        $session->remove('last_activity');
        $session->remove('expire_time');
        $session->remove('tkn_access');
        
        $url = \Drupal\Core\Url::fromRoute('<front>')->toString();
        return new RedirectResponse($url);

    }

    public function volverPass(){
        $session = \Drupal::request()->getSession();
        
        $session->set('showInfoPersonalForm',true);
        
        $url = \Drupal\Core\Url::fromRoute('linxecredit.dashboard-informacionpersonal')->toString();
        return new RedirectResponse($url);

    }


    


}
