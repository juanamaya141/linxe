<?php
namespace Drupal\linxecredit\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Response;
use \Drupal\node\Entity\Node;
use Drupal\UtilitiesModule\Controller\UtilitiesController;

use Drupal\linxecredit\Libs\LinxeLibrary as LinxeLibrary;
use Drupal\linxecredit\Libs\AdelantoLibrary as AdelantoLibrary;
use Drupal\linxecredit\Libs\DecevalLibrary as DecevalLibrary;



use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\mydata\Controller
 */
class ServiciosController extends ControllerBase
{
    

    //Metodo para SERVICE JSON
    public function getCargosTiposContrato($idempresa)
    {

        $session = \Drupal::request()->getSession();

        $linxelib = new LinxeLibrary();

        $arrayCargosContratos = [] ;
        $objCargosContratos = $linxelib->getCargosYTiposContrato($idempresa);

        
        $arrayCargosContratos["TiposContrato"] = $objCargosContratos->SpResult->Table;
        $arrayCargosContratos["Cargos"] = $objCargosContratos->SpResult->Table1;
        $response = new Response();
        $response->setContent(json_encode($arrayCargosContratos));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    //Metodo para SERVICE JSON
    public function setSeleccionCredito()
    {

        $session = \Drupal::request()->getSession();
        $linxelib = new LinxeLibrary();
        $request = Request::createFromGlobals();

        if($request->request->get('tipoid')!=="")
            $tipoid = intval($request->request->get('tipoid'));
        else
            $tipoid = "";

        if($request->request->get('numid')!=="")
            $numid = intval($request->request->get('numid'));
        else
            $numid = "";

        if($request->request->get('monto')!=="")
            $monto = $request->request->get('monto');
        else
            $monto = "";

        if($request->request->get('plazo')!=="")
            $plazo = intval($request->request->get('plazo'));
        else
            $plazo = "";

        if($request->request->get('cantidad')!=="")
            $cantidad = intval($request->request->get('cantidad'));
        else
            $cantidad = "";

        $dataField["TipoId"] = $tipoid;
        $dataField["Numid"] = $numid;
        //$dataField["Monto"] = $monto;
        $dataField["Monto"] = $cantidad;
        $dataField["Plazo"] = $plazo;
        $dataField["ipAutoriza"] = $_SERVER['REMOTE_ADDR'];
        $dataField["fechaHora"] = date("d/m/Y H:i:s");

        $objAutorizacion = $linxelib->autorizacionSolicitud($dataField);

        if($objAutorizacion->Success == true)
        {
            $session->set('montoseleccionado',$monto);
            $session->set('cantidadseleccionada',$cantidad);
            $session->set('creditovigente',"SI");
            $session->set('plazo',$plazo);
            $arrayReturn["status"] = $objAutorizacion->Success;
            $arrayReturn["msg"] = $objAutorizacion->SpResult;
        }else{
            $arrayReturn["status"] = false;
            $arrayReturn["msg"] = "Error";
            $arrayReturn["error"] = $objAutorizacion;
        }
        

        $response = new Response();
        $response->setContent(json_encode($arrayReturn));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    //metodo para mostrar un mensaje de respuesta proveniente de datascoring

    public function showResponseMessage(){
        $session = \Drupal::request()->getSession();

        $titlemsg = $session->get('titlemsg'); 
        if($titlemsg == "")
        {
            $titlemsg = "LO SENTIMOS";
        }
        $message = $session->get('message'); 
        $labelbutton = $session->get('labelbutton'); 

        $paththeme = base_path().drupal_get_path('theme', 'linxe');
        $basepath = base_path();
        
        return [
          '#theme' => 'message_response',
          '#titlemsg' => $titlemsg,
          '#message' => $message,
          '#labelbutton' => $labelbutton,
          '#paththeme' => $paththeme,
          '#basepath' => $basepath,
          '#attached' => [
            'library' => [
              'linxecredit/linxecreditstyles', //include our custom library for this response
            ]
          ]
        ];

    }

    public function lanzarOTP(){
        $session = \Drupal::request()->getSession();
        $linxelib = new LinxeLibrary();
        $request = Request::createFromGlobals();

        if($request->request->get('tipoid')!=="")
            $tipoid = intval($request->request->get('tipoid'));
        else
            $tipoid = "";

        if($request->request->get('numid')!=="")
            $numid = intval($request->request->get('numid'));
        else
            $numid = "";

        $respuesta = $linxelib->envioOTP($tipoid,$numid);

        if($respuesta == true)
        {
            
            $arrayReturn["status"] = "Ok";
        }else{
            $arrayReturn["status"] = "Fail";
        }
        

        
        $response = new Response();
        $response->setContent(json_encode($arrayReturn));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    
    public function getPDFContract(){
        $session = \Drupal::request()->getSession();
        $linxelib = new LinxeLibrary();
        $request = Request::createFromGlobals();

        
        if(\Drupal::request()->query->get('t')!=="")
            $tipoid = intval(\Drupal::request()->query->get('t'));
        else
            $tipoid = 0;

        if(\Drupal::request()->query->get('n')!=="")
            $numid = intval(\Drupal::request()->query->get('n'));
        else
            $numid = 0;

        if(\Drupal::request()->query->get('tk')!=="")
            $tok = \Drupal::request()->query->get('tk');
        else
            $tok = 0;

        //$tipoid = $session->get('tipodocumento'); 
        //$numid = $session->get('numerodocumento'); 
        

        $dataField["tipoId"] = intval($tipoid);
        $dataField["numId"] = intval($numid);  

        $tokengen = substr(md5("fdfDSR32".$numid."fVe".$tipoid),0,6);
        if($tokengen != $tok)
        {
            exit();
        }
        

         
        
        //print_r($dataField);
        $objPagareB64 = $linxelib->getPagareBase64($dataField);
        //print_r($objPagareB64);
        //exit();

        if($objPagareB64->Success == 1)
        {
            $base64 = $objPagareB64->SpResult->Table[0]->Pagare;
        }else{
            $base64 = "";
        }

        $data = base64_decode($base64);
        

        
        $response = new Response();
        $response->setContent($data);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }


    //Metodo para SERVICE JSON
    public function setPagareBlanco()
    {

        $session = \Drupal::request()->getSession();
        $linxelib = new LinxeLibrary();
        $request = Request::createFromGlobals();

        if($request->request->get('tipoid')!=="")
            $tipoid = intval($request->request->get('tipoid'));
        else
            $tipoid = "";

        if($request->request->get('numid')!=="")
            $numid = intval($request->request->get('numid'));
        else
            $numid = "";


        $dataField["tipoId"] = $tipoid;
        $dataField["numId"] = $numid;

        $objAutorizacion = $linxelib->getGeneraPagareBlanco($dataField);

        if($objAutorizacion->Success == true)
        {
            $arrayReturn["status"] = $objAutorizacion->Success;
            $arrayReturn["msg"] = $objAutorizacion->SpResult;
        }else{
            $arrayReturn["status"] = false;
            $arrayReturn["msg"] = "Error";
            $arrayReturn["error"] = $objAutorizacion;
        }
        

        $response = new Response();
        $response->setContent(json_encode($arrayReturn));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    //Metodo para SERVICE JSON
    public function setSeleccionAdelanto()
    {
        $config = $this->config('linxecredit.settings');
        $twilio_sid = $config->get('linxecredit.twilio_sid');
        $twilio_token = $config->get('linxecredit.twilio_token');
        $twilio_phonenumber = $config->get('linxecredit.twilio_phonenumber');
        $urlws_deceval = \Drupal::config('linxecredit.settings')->get('linxecredit.urlws_deceval') ; 
		$deceval_codigodepositante = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_codigodepositante');
		$deceval_usuario = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_usuario');
        $deceval_identificacionemisor = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_identificacionemisor');
        $deceval_idclasedocumento = \Drupal::config('linxecredit.settings')->get('linxecredit.deceval_idclasedocumento');
      
        $session = \Drupal::request()->getSession();
        $adelantolib = new AdelantoLibrary();
        $decevallib = new DecevalLibrary();

        //$decevallib->getconsultarPagares();
        $request = Request::createFromGlobals();

        if($request->request->get('tipoid')!=="")
            $tipoid = intval($request->request->get('tipoid'));
        else
            $tipoid = "";

        if($request->request->get('numid')!=="")
            $numid = intval($request->request->get('numid'));
        else
            $numid = "";
        
        if($request->request->get('idregistro')!=="")
            $idregistro = intval($request->request->get('idregistro'));
        else
            $idregistro = "";

        if($request->request->get('monto')!=="")
            $monto = $request->request->get('monto');
        else
            $monto = "";
        
        $returnArray = json_decode($adelantolib->getUserDataByIdRegistro($idregistro));
        $dataUser = $returnArray->userData;
        $idadelanto = $dataUser->idadelanto;

        //echo "idadelanto: ".$idadelanto;

        $configuration = [];
        $configuration["cargo_administracion_adelanto"] = $config->get('linxecredit.cargo_administracion_adelanto');
        $configuration["cargo_tecnologia_adelanto"] = $config->get('linxecredit.cargo_tecnologia_adelanto');
        $configuration["seguro_adelanto"] = $config->get('linxecredit.seguro_adelanto');
        $configuration["iva_adelanto"] = $config->get('linxecredit.iva_adelanto');
        
        $objAutorizacion = json_decode($adelantolib->setSeleccionAdelanto($idadelanto,$monto,$configuration));

        if($objAutorizacion->status == "ok")
        {
            //refresco la solicitud de adelanto
            $returnArray = json_decode($adelantolib->getUserDataByIdRegistro($idregistro));
            
            $dataUser = $returnArray->userData;
            //

            $arrayReturn["status"] = true;
            $arrayReturn["msg"] = $objAutorizacion->msg;
            //acá se debería lanzar la generación del pdf de términos y condiciones
            /*
            $empresa = $adelantolib->getEmpresaByNit($dataUser->nit_empresa);
            
            $arrayData = [];
            $arrayData["nombres"] = $dataUser->nombre;
            $arrayData["apellidos"] = $dataUser->primer_apellido." ".$dataUser->segundo_apellido;
            $arrayData["tipo_identificacion"] = "Cédula Ciudadanía";
            $arrayData["identificacion"] = $dataUser->documento;
            $arrayData["tipo_producto"] = "Adelanto de Salario";
            $arrayData["valor_solicitado"] = $dataUser->valor_solicitado;
            $valorcuota = $dataUser->valor_solicitado + $dataUser->administracion + $dataUser->seguros + $dataUser->tecnologia + $dataUser->iva;
            $arrayData["valor_cuota"] = $valorcuota;
            $arrayData["tipo_tasa"] = "No Aplica";
            $arrayData["tasa_mora"] = "Máxima permitida por ley";
            $arrayData["costo_administracion_linxe"] = $configuration["cargo_administracion_adelanto"];
            $arrayData["costo_tecnologia_linxe"] = $configuration["cargo_tecnologia_adelanto"];
            $arrayData["razon_social"] = mb_strimwidth($empresa[0]["razon_social"], 0, 25, "...");
            $arrayData["periodicidad_pago"] = $empresa[0]["periodicidad_pago"];
            if($empresa[0]["periodicidad_pago"] == "quincenal"){
                $arrayData["num_cuotas"] = 2;
                $arrayData["monto_cuota"] = round($valorcuota/2);
            }else{
                $arrayData["num_cuotas"] = 1;
                $arrayData["monto_cuota"] = $valorcuota;
            }


            $htmlTerms = $adelantolib->getTermsConditionsHTML($arrayData);
            $today = date("Y-m-d");

            $dompdf = new Dompdf();
            $dompdf->load_html($htmlTerms);
            $dompdf->render();
            $pdfoutput = $dompdf->output();
            $filename = 'terminos-condiciones-'.$arrayData['identificacion'].'_'.$today.'.pdf';
            $filepath = 'sites/default/files/pdfterminos/'.$filename;
            $fp = fopen($filepath, "w+");
            if(fwrite($fp, $pdfoutput) !==  false)
            {
                //envio de mensaje de confirmación de los términos y condiciones
                if($dataUser->convenio_empresa=="si")
                {
                    $titleMsg = "LINXE - TÉRMINOS Y CONDICIONES ADELANTO DE SALARIO / ".$dataUser->nombre." ".$dataUser->primer_apellido;
                    $mensaje = "<h2>TÉRMINOS Y CONDICIONES<h2><br/>";
                    $mensaje .= "<p><b>ADELANTO DE SALARIO / ".$dataUser->nombre." ".$dataUser->primer_apellido."</b></p><br/>";
                    $mensaje = "<p>Podrás contar con el dinero en tu cuenta de nómina, a más tardar en el transcurso de las próximas 24 horas.</p><br/>";
                    $mensaje .= "<p>Siempre que necesites ayuda, puedes contar con nosotros ingresando a <a href='https://www.linxe.com' target='_blank'>www.linxe.com</a></p><br/>";
                    $mensaje .= "<p>Nos interesa seguir mejorando, cuéntanos como podemos hacerlo. Click aquí <a href='https://www.linxe.com/form/nps' target='_blank'>www.linxe.com/form/nps</a>.</p>";
                    $mensaje .= "<br><br><p>Equipo Linxe</p>";
                }else{
                    $titleMsg = "LINXE - TÉRMINOS Y CONDICIONES ADELANTO DE SALARIO / ".$dataUser->nombre." ".$dataUser->primer_apellido;
                    $mensaje = "<h2>TÉRMINOS Y CONDICIONES<h2><br/>";
                    $mensaje .= "<p><b>ADELANTO DE SALARIO / ".$dataUser->nombre." ".$dataUser->primer_apellido."</b></p><br/>";
                    $mensaje = "<p>Una vez esté firmado y legalizado el convenio con tu empresa, podrémos desembolsar el dinero en tu cuenta de nómina.</p><br/>";
                    $mensaje .= "<p>Siempre que necesites ayuda, puedes contar con nosotros ingresando a <a href='https://www.linxe.com' target='_blank'>www.linxe.com</a></p><br/>";
                    $mensaje .= "<p>Nos interesa seguir mejorando, cuéntanos como podemos hacerlo. Click aquí <a href='https://www.linxe.com/form/nps' target='_blank'>www.linxe.com/form/nps</a>.</p>";
                    $mensaje .= "<br><br><p>Equipo Linxe</p>";
                }
                
                $emailto = $dataUser->email;
                $resultmail = $adelantolib->sendMail_acceptTerms($titleMsg,$mensaje,$emailto,$filepath,$filename);

            }else{
                $arrayReturn["status"] = false;
                $arrayReturn["msg"] = "Error send terms email";
            } 
            fclose($fp);
            */
            

            //

            //ahora hay que crear el girador en deceval
            /*
            $arrayCrearGirador = [];
            //params
            $arrayCrearGirador["urlws_deceval"] = $urlws_deceval;
            $arrayCrearGirador["deceval_codigodepositante"] = $deceval_codigodepositante;
            $arrayCrearGirador["deceval_usuario"] = $deceval_usuario;
            $arrayCrearGirador["deceval_identificacionemisor"] = $deceval_identificacionemisor;
            //fields
            $arrayCrearGirador["correoElectronico"] = $dataUser->email;
            $arrayCrearGirador["fechaExpedicion"] = $dataUser->fecha_expedicion;
            $arrayCrearGirador["idTipoDocumento"] = $dataUser->tipodocumento;
            $arrayCrearGirador["numeroDocumento"] = $dataUser->documento;
            $arrayCrearGirador["nombres"] = $dataUser->nombre;
            $arrayCrearGirador["primer_apellido"] = $dataUser->primer_apellido;
            $arrayCrearGirador["segundo_apellido"] = $dataUser->segundo_apellido;
            $arrayCrearGirador["telefono"] = $dataUser->celular;

        
            $arrayCreateGirador = $decevallib->crearGirador($arrayCrearGirador);

            //print_r($arrayCreateGirador);
            //exit();

            if( ($arrayCreateGirador->return->codigoError == "SDL.SE.0000" || $arrayCreateGirador->return->codigoError == "SDL.SE.0169") && $arrayCreateGirador->return->exitoso == 1)
            {
                $mensajeRespuesta = $arrayCreateGirador->return->listaRespusta[0]->mensajeRespuesta;
                $arrayMensajeRespuesta = explode(":",$mensajeRespuesta);
                $cuentaGirador = $arrayCreateGirador->return->listaRespusta[0]->cuentaGirador;
                $respuestaDeceval = json_encode($arrayCreateGirador);
                //actualizar el regitro de adelanto de nómina con la información del girador
                $actualizarGirador = $adelantolib->setRespuestaGiradorDeceval($idadelanto,$respuestaDeceval,$cuentaGirador);

                //print_r($actualizarGirador);
                //exit();
                if($actualizarGirador->status=="ok"){
                    $arrayCrearPagare = [];
                    //params
                    $arrayCrearPagare["urlws_deceval"] = $urlws_deceval;
                    $arrayCrearPagare["deceval_codigodepositante"] = $deceval_codigodepositante;
                    $arrayCrearPagare["deceval_usuario"] = $deceval_usuario;
                    $arrayCrearPagare["deceval_identificacionemisor"] = $deceval_identificacionemisor;
                    $arrayCrearPagare["deceval_idclasedocumento"] = $deceval_idclasedocumento;
                    
                    //fields
                    $arrayCrearPagare["otorganteTipoId"] = $dataUser->tipodocumento;
                    $arrayCrearPagare["otorganteNumId"] = $dataUser->documento;

                    $arrayCreatePagare = $decevallib->crearPagare($arrayCrearPagare);
                    if( ($arrayCreatePagare->return->codigoError == "SDL.SE.0000" || $arrayCreatePagare->return->codigoError == "SDL.SE.0169") && $arrayCreatePagare->return->exitoso == 1)
                    {
                        $respuestaDeceval = json_encode($arrayCreatePagare);
                        $numpagareentidad = $arrayCreatePagare->return->respuesta->numPagareEntidad;
                        $iddocumentopagare = $arrayCreatePagare->return->respuesta->idDocumentoPagare; 
                        //actualizar el regitro de adelanto de nómina con la información del girador
                        $actualizarPagare = $adelantolib->setRespuestaPagareDeceval($idadelanto,$respuestaDeceval,$numpagareentidad,$iddocumentopagare);

                        if($actualizarPagare->status=="ok"){
                            $arrayReturn["status"] = $actualizarPagare->Success;
                            $arrayReturn["msg"] = $actualizarPagare->msg;

                            //
                        }else{
                            $arrayReturn["status"] = false;
                            $arrayReturn["msg"] = "Error";
                            $arrayReturn["error"] = $actualizarPagare->error;
                        }

                    }else{
                        $arrayReturn["status"] = false;
                        $arrayReturn["msg"] = "Error";
                        $arrayReturn["error"] = $arrayCreateGirador->return->descripcion;
                    }
                }else{
                    $arrayReturn["status"] = false;
                    $arrayReturn["msg"] = "Error";
                    $arrayReturn["error"] = $actualizarGirador->error;
                }
                
            }else{
                $arrayReturn["status"] = false;
                $arrayReturn["msg"] = "Error";
                $arrayReturn["error"] = $arrayCreateGirador->return->descripcion;
            }*/
           
            
        }else{
            $arrayReturn["status"] = false;
            $arrayReturn["msg"] = "Error";
            $arrayReturn["idregistro"] = $idregistro;
            $arrayReturn["returnArray"] = $returnArray;
            $arrayReturn["error"] = $objAutorizacion;
        }
        

        $response = new Response();
        $response->setContent(json_encode($arrayReturn));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    public function getPDFPagare(){
        $session = \Drupal::request()->getSession();
        $linxelib = new LinxeLibrary();
        $decevallib = new DecevalLibrary();
        $request = Request::createFromGlobals();

        
        if(\Drupal::request()->query->get('t')!=="")
            $tipoid = intval(\Drupal::request()->query->get('t'));
        else
            $tipoid = 0;

        if(\Drupal::request()->query->get('n')!=="")
            $numid = intval(\Drupal::request()->query->get('n'));
        else
            $numid = 0;

        if(\Drupal::request()->query->get('tk')!=="")
            $tok = \Drupal::request()->query->get('tk');
        else
            $tok = 0;
        
        if(\Drupal::request()->query->get('pag')!=="")
            $pagEntidad = \Drupal::request()->query->get('pag');
        else
            $pagEntidad = 0;

        //$tipoid = $session->get('tipodocumento'); 
        //$numid = $session->get('numerodocumento'); 
        
        $tokengen = substr(md5("fdfDSR32".$numid."fVe".$tipoid),0,6);
        if($tokengen != $tok)
        {
            exit();
        }
        
        $dataField["idTipoIdentificacionFirmante"] = intval($tipoid);
        $dataField["numIdentificacionFirmante"] = intval($numid);  
        $dataField["numPagareEntidad"] = intval($pagEntidad);  

        $objPagare = $decevallib->getconsultarPagares($dataField);

        //print_r($objPagare);
        //exit();

        if( ($objPagare->return->codigoError == "SDL.SE.0000" || $objPagare->return->codigoError == "SDL.SE.0169") && $objPagare->return->exitoso == 1)
        {
            $base64 = $arrayCreatePagare->return->listaRespuesta->pdfPagare->contenido; 
        }else{
            $base64 = "";
        }      

        $data = base64_decode($base64);
        
        $response = new Response();
        $response->setContent($data);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    public function lanzarOTPAdelanto(){
        $session = \Drupal::request()->getSession();
        $linxelib = new LinxeLibrary();
        $adelantolib = new AdelantoLibrary();
        $decevallib = new DecevalLibrary();
        $request = Request::createFromGlobals();

        if($request->request->get('idregistro')!=="")
            $idregistro = intval($request->request->get('idregistro'));
        else
            $idregistro = "";
        
        $returnArray = json_decode($adelantolib->getUserDataByIdRegistro($idregistro));
        $dataUser = $returnArray->userData;
        $idadelanto = $dataUser->idadelanto;

        $config = $this->config('linxecredit.settings');
        $twilio_sid = $config->get('linxecredit.twilio_sid');
        $twilio_token = $config->get('linxecredit.twilio_token');
        $twilio_phonenumber = $config->get('linxecredit.twilio_phonenumber');

        $respuesta = $adelantolib->sendOTP($idregistro,$idadelanto,$twilio_sid,$twilio_token,$twilio_phonenumber);

        if($respuesta == true)
        {
            
            $arrayReturn["status"] = "Ok";
        }else{
            $arrayReturn["status"] = "Fail";
        }
        

        
        $response = new Response();
        $response->setContent(json_encode($arrayReturn));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }


    //Metodo para SERVICE JSON
    public function getEmpresasByActividad($actividad)
    {

        $arrayreturn = [];
        $arrayFields = [];
        $arrayEmpresas = [];
		if($actividad=="empleado" || $actividad=="pensionado")
		{	
			$database = \Drupal::database();

			// registro
            $query = $database->select('empresas', 'em');
            $arrayAct = [$actividad,"ambos"];
            $result = $query->condition('em.convenio_actividadecon', $arrayAct, 'in')
                        ->condition('em.estado_convenio', "aceptado" , '=')
                        ->fields('em')
                        ->execute();
            //print_r($result);
            foreach ($result as $key=>$record) {
                //echo $record->convenio_actividadecon."<br/>";
                foreach($record as $key2 => $value)
				{
					$arrayEmpresas[$key][$key2] = $value;
				}
            }
            //print_r($arrayEmpresas);
			if(is_array($arrayEmpresas) and count($arrayEmpresas)>0)
			{
				$obj["status"] = "ok";
				$obj["empresas"] = $arrayEmpresas;
				$arrayreturn = json_encode($obj);
			}else{
				$obj["status"] = "fail";
				$obj["error"] = "No se encontraron empresas";
				$arrayreturn = json_encode($obj);
			}
			
		}else{
			$obj["status"] = "fail";
			$obj["error"] = "Actividad económica no valida";
			$arrayreturn = json_encode($obj);
		}

       
        $response = new Response();
        $response->setContent(json_encode($arrayreturn));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        return $response;
    }

    
    
}
