<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Helpers\HelperLog;

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
    }//index

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

        //GRAVAR SESSION PARA PROXIMA PESQUISA
        session()->put('consent', [
            'beneficiario' => $cod_beneficiario,
            'unimed' => $cod_unimed
        ]);

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

            //Log - tab
            HelperLog::gravaLog(
                'consent',
                'Consulta',
                "#unimed - ".$cod_unimed
                ." #beneficiario - ".$cod_beneficiario
                ." #data - ".date(DATE_ATOM)
                ." #operação - Verificar status beneficiario"
                ." #status - Erro",
                session('login')['id']);

            return response()->json(['status'=>'erro','msg'=> (string)$value]);
        }
        $url = $nodes [0];

        if((string) $url == "ACTIVE"){

            //Log - tab
            HelperLog::gravaLog(
                'consent',
                'Consulta',
                "#unimed - ".$cod_unimed
                ." #beneficiario - ".$cod_beneficiario
                ." #data - ".date(DATE_ATOM)
                ." #operação - Verificar status beneficiario"
                ." #status - Ativo",
                session('login')['id']);

            return response()->json([
                'status'=>'active',
                'msg'=> 'Beneficiário está ATIVO.']);
        }else{

            //Log - tab
            HelperLog::gravaLog(
                'consent',
                'Consulta',
                "#unimed - ".$cod_unimed
                ." #beneficiario - ".$cod_beneficiario
                ." #data - ".date(DATE_ATOM)
                ." #operação - Verificar status beneficiario"
                ." #status - Inativo",
                session('login')['id']);

            return response()->json([
                'status'=>'inactive',
                'msg'=> 'Beneficiário INATIVO. Para ativa-lo imprima o termo de ativação, scaneie e salve em PDF (até 2 MB). Depois faça o upload e envie com o botão "Enviar Ativação"'
            ]);
        }

    }//getConsultaStatus

    public function getAtivacao(Request $request)
    {
        //Verifica se tem arquivo

        if(Input::hasFile('fileup')) {
            //teste
            $file = Input::file('fileup');
            $fileMimeType = Input::file('fileup')->getMimeType();
            $fileData = file_get_contents($file);
            $imagebase64 = base64_encode($fileData);
            $path_file = "data:{$fileMimeType};base64,{$imagebase64}";

            //SALVAR PDF - PUBLIC/PDF
            $file_save = $request->file('fileup');
            $new_name = rand() . '.' . $file_save->getClientOriginalExtension();
            $archive = $file_save->move(public_path('pdf'), $new_name);

        }else{
            return response()->json(['status'=>'error','msg' => "Erro - Por favor faça o upload do arquivo PDF."]);
        }

        //Arquivos de teste
        /*$imagebase64 = "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dWxsIDBdCj4+CmVuZG9iagoKMTMgMCBvYmoKPDwvQ3JlYXRvcjxGRUZGMDA1NzAwNzIwMDY5MDA3NDAwNjUwMDcyPgovUHJvZHVjZXI8RkVGRjAwNDIwMDcyMDA0RjAwNjYwMDY2MDA2OTAwNjMwMDY1MDAyRTAwNkYwMDcyMDA2NzAwMjAwMDMyMDAyRTAwMzI+Ci9DcmVhdGlvbkRhdGUoRDoyMDA4MDYxNjExMTcwMS0wMycwMCcpPj4KZW5kb2JqCgp4cmVmCjAgMTQKMDAwMDAwMDAwMCA2NTUzNSBmIAowMDAwMDI0ODkyIDAwMDAwIG4gCjAwMDAwMDAwMTkgMDAwMDAgbiAKMDAwMDAwMDMxNCAwMDAwMCBuIAowMDAwMDI1MDM1IDAwMDAwIG4gCjAwMDAwMDAzMzQgMDAwMDAgbiAKMDAwMDAyMzg0NyAwMDAwMCBuIAowMDAwMDIzODY5IDAwMDAwIG4gCjAwMDAwMjQwNjYgMDAwMDAgbiAKMDAwMDAyNDUwNyAwMDAwMCBuIAowMDAwMDI0ODA1IDAwMDAwIG4gCjAwMDAwMjQ4MzcgMDAwMDAgbiAKMDAwMDAyNTEzNCAwMDAwMCBuIAowMDAwMDI1MjE4IDAwMDAwIG4gCnRyYWlsZXIKPDwvU2l6ZSAxNC9Sb290IDEyIDAgUgovSW5mbyAxMyAwIFIKL0lEIFsgPDNFNUZBRThDMUQyQjkwRTQ0MzBBNDQ5NDc4MUNGQzMyPgo8M0U1RkFFOEMxRDJCOTBFNDQzMEE0NDk0NzgxQ0ZDMzI+IF0KPj4Kc3RhcnR4cmVmCjI1Mzk3CiUlRU9GCg==";
        $new_name = "teste.pdf";
        $cod_unimed = "0005";
        $cod_beneficiario = "0000000238351";*/

        $soapUrl = "https://s975lresdesesb01.unimedpr.com.br:8243/services/00830_ativaBeneficiario?wsdl";
        $soapUser = "joao";  //  username
        $soapPassword = "j4o1t664"; // password

        if(empty(session('consent')['beneficiario']) || empty(session('consent')['unimed'])){
            return response()->json(['status'=>'error','msg' => "Erro - Algum problema aconteceu, por favor pesquise o Beneficiario novamente."]);
        }
        $cod_unimed = session('consent')['unimed'];
        $cod_beneficiario = session('consent')['beneficiario'];


        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ativ="http://integracao-res.unimed.com.br/schemas/servico/recebimento/dados-demograficos/ativacao-beneficiario" xmlns:xm="http://www.w3.org/2005/05/xmlmime">
                                <soapenv:Header/>
                                       <soapenv:Body>
                                          <ativ:AtivaBeneficiario>
                                             <IdentificacaoBeneficiario>
                                                <CodigoUnimed>'.$cod_unimed.'</CodigoUnimed>
                                                <CodigoBeneficiario>'.$cod_beneficiario.'</CodigoBeneficiario>
                                             </IdentificacaoBeneficiario>
                                             <IdentificacaoProfissional>
                                                <ProfissionalNaoSaude>
                                                   <Abreviatura>joao.silva</Abreviatura>
                                                   <!--Optional:-->
                                                   <NomeCompleto>Joao Silva</NomeCompleto>
                                                   <CadastroPessoaFisica>59301754541</CadastroPessoaFisica>
                                                </ProfissionalNaoSaude>
                                             </IdentificacaoProfissional>
                                             <ConsentimentoAceite>
                                                <DataAceite>'.date(DATE_ATOM).'</DataAceite>
                                                <Ambiente>PHYSICAL</Ambiente>
                                                <Consentimento>
                                                   <Nome>consentimento.pdf</Nome>
                                                   <AnexoConsentimento>'.$imagebase64.'</AnexoConsentimento>
                                                </Consentimento>
                                             </ConsentimentoAceite>
                                           </ativ:AtivaBeneficiario>
                                       </soapenv:Body>
                            </soapenv:Envelope>';   // data from the form, e.g. some ID number

        $headers = array(
            "Content-type: text/xml",
            "Accept-Encoding: gzip,deflate",
            "MIME-Version: 1.0",
            "SOAPAction: \"urn:AtivaBeneficiarioOperation\"",
            "Host: s975lresdesesb01.unimedpr.com.br:8243",
            "Connection: Keep-Alive",
            "User-Agent: Apache-HttpClient/4.1.1 (java 1.5)",
            "Content-length: ".strlen($xml_post_string)
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

        //return response()->json(['teste'=>'teste2','teste2' =>$xml ]);

        $nodes = $xml->xpath ( '//DescricaoSituacao' );
        if(empty($nodes)){

            $response2 = new \DOMDocument($response);
            $response2->loadXml($response);
            $mensagem_erro = $response2->getElementsByTagName('mensagem_erro')->item(0)->textContent;
            //$identificador_requisicao = $response2->getElementsByTagName('detalhe')->item(0)->textContent;

            //Log - tab
            HelperLog::gravaLog(
                'consent',
                'Ativação',
                "#unimed - ".$cod_unimed
                ." #beneficiario - ".$cod_beneficiario
                ." #data - ".date(DATE_ATOM)
                ." #operação - Ativar beneficiario"
                ." #status - Erro",
                session('login')['id']);

            return response()->json(['status'=>'erro','msg'=> (string)$mensagem_erro]);
        }

        if((string)$nodes[0] == "SUCESSO"){
            $descricaoMensagem = $xml->xpath ( '//DescricaoMensagem' );
            $mensagemEnvioID = $xml->xpath ( '//MensagemEnvioID' );

            //Gravar arquivo
            HelperLog::gravaConsent((string)$mensagemEnvioID[0],
                $archive,
                'Ativo',
                $cod_beneficiario,
                $cod_unimed,
                session('login')['id']);

            //Log
             HelperLog::gravaLog(
                'consent',
                'Ativação',
                "#unimed - ".$cod_unimed
                ." #beneficiario - ".$cod_beneficiario
                ." #data - ".date(DATE_ATOM)
                ." #operação - Ativar beneficiario"
                ." #status - Ativado",
                session('login')['id']);

            return response()->json(['status'=>'active','msg'=> (string) $descricaoMensagem[0] . " - Beneficiário ativado com sucesso!"]);
        } else{
            $descricaoMensagem = $xml->xpath ( '//DescricaoMensagem' );
            //Log
             HelperLog::gravaLog(
                 'consent',
                 'Ativação',
                 "#unimed - ".$cod_unimed
                 ." #beneficiario - ".$cod_beneficiario
                 ." #data - ".date(DATE_ATOM)
                 ." #operação - Ativar beneficiario"
                 ." #status - Inativo",
                 session('login')['id']);

            return response()->json(['status'=>'inative','msg'=> (string) $descricaoMensagem[0] . " - " .$nodes[0]]);
        }


    }//getAtivacao

}//ConsentController
