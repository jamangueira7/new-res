<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return $this->getConsultaStatus();
        $unimeds = DB::connection('oracle')
            ->table('RES_UNIDADE_SAUDE')
            ->leftJoin('RES_UNIDADE_IDENTIFICACAO', 'RES_UNIDADE_SAUDE.nr_sequencia', '=', 'RES_UNIDADE_IDENTIFICACAO.nr_seq_unidade_saude')
            ->select('RES_UNIDADE_SAUDE.cd_sistema_origem AS id_unimed','RES_UNIDADE_SAUDE.nm_unidade_saude AS ds_unimed')
            ->where('RES_UNIDADE_IDENTIFICACAO.cd_tipo_identificacao','=','IDUN')
            ->orderBy('RES_UNIDADE_SAUDE.nm_unidade_saude','ASC')
            ->get();

        return view('consent.index',[
            'unimeds' =>$unimeds
        ]);
    }

    public function getConsultaStatus(Request $request)
    {

        $soapUrl = "https://s975lresdesesb01.unimedpr.com.br:8243/services/00820_consultaStatusBeneficiario?wsdl";
        $soapUser = "joao";  //  username
        $soapPassword = "j4o1t664"; // password
        //ATIVO - 0000003552628 - 0187
        //INATIVO - 0000000238351 - 0005
        //$cod_unimed = "0005";
        //$cod_beneficiario = '0000000238354';

        $cod_unimed = explode("-",$request->get('codUnimed'))[0];
        $cod_beneficiario = $request->get('codBenef');



        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:con="http://integracao-res.unimed.com.br/schemas/servico/consulta/dados-demograficos/padrao-unimed/consulta-status-beneficiario/">
                               <soapenv:Header/>
                               <soapenv:Body>
                                  <con:ConsultaStatusBeneficiario>
                                     <IdentificacaoBeneficiario>
                                        <CodigoUnimed>'.$cod_unimed.'</CodigoUnimed>
                                        <CodigoBeneficiario>'.$cod_beneficiario.'</CodigoBeneficiario>
                                     </IdentificacaoBeneficiario>
                                     <IdentificacaoProfissional>
                                        <ProfissionalNaoSaude>
                                           <Abreviatura>joao.silva</Abreviatura>
                                           <CadastroPessoaFisica>59301754541</CadastroPessoaFisica>
                                        </ProfissionalNaoSaude>
                                     </IdentificacaoProfissional>
                                  </con:ConsultaStatusBeneficiario>
                               </soapenv:Body>
                            </soapenv:Envelope>';   // data from the form, e.g. some ID number

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: \"ConsultaStatusBeneficiarioOperation\"",
            "Content-length: ".strlen($xml_post_string),
        ); //SOAPAction: your op URL

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $soapUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $soapUser.":".$soapPassword); // username and password - declared at the top of the doc
        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch);
        //dd(curl_error($ch));
        curl_close($ch);

        $xml = simplexml_load_string ( $response );
        $nodes = $xml->xpath ( '//Estado' );
        if(empty($nodes)){

            $response2 = new \DOMDocument($response);
            $response2->loadXml($response);
            $value = $response2->getElementsByTagName('mensagem_erro')->item(0)->textContent;

            return response()->json(['status'=>'erro','msg'=> (string)$value]);
        }
        $url = $nodes [0];

        if((string) $url == "ACTIVE"){
            return response()->json(['status'=>'active','msg'=> (string) $url]);
        }else{

            return response()->json(['status'=>'inactive','msg'=> (string) $url]);
        }

    }



}
