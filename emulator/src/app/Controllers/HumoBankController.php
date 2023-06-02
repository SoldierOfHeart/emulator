<?php

namespace App\Controllers;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Эмулирует работу банка Humo (Узбекистан)
 */
class HumoBankController extends Controller
{
    const DATE_FORMAT = 'YmdHis';

    /**
     * @var int
     */
    protected $errorCode;

    /**
     * @Route("/fakers/humo")
     */
    public function index(Request $request): Response
    {
        $method = trim($request->server->get('HTTP_SOAPACTION'), "\"");
        switch ($method) {
            case "Payment":
                $xml = $this->payment($request->getContent());
                break;
            case 'GetPayment':
                $xml = $this->status($request->getContent());
                break;
            case 'CancelRequest':
                $xml = $this->cancel($request->getContent());
                break;
            case 'ReturnPayment':
                $xml = $this->return($request->getContent());
                break;
        }

        $response = new Response($xml);
        $response->headers->set('Content-Type', 'xml');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Server', 'gSOAP/2.8');

        if ($this->errorCode) {
            $response->setStatusCode($this->errorCode);
        }

        return $response;
    }

    /**
     * @param string $xml
     * @return string
     * @throws \Exception
     */
    private function payment(string $xml): string
    {
        $params = $this->parseRequestV2($xml, 'Payment');

        $pan = $this->getDetailsField($params, 'pan');
        $date = new \DateTime();

        $data = [
            'billerRef' => isset($params->billerRef) ? $params->billerRef->__toString() : null,
            'paymentRef' => isset($params->paymentRef) ? $params->paymentRef->__toString() : null,
            'pan' => $pan,
            'expiry' => $this->getDetailsField($params, 'expiry'),
            'ccyCode' => $this->getDetailsField($params, 'ccy_code'),
            'merchantId' => $this->getDetailsField($params, 'merchant_id'),
            'terminalId' => $this->getDetailsField($params, 'terminal_id'),
            'pointCode' => $this->getDetailsField($params, 'point_code'),
            'centreId' => $this->getDetailsField($params, 'centre_id'),
            'paymentId' => isset($params->paymentID) ? $params->paymentID->__toString() : null,
            'confirmed' => isset($params->confirmed) ? $params->confirmed->__toString() : null,
            'amount' => $this->getDetailsField($params, 'amount') ?? 0,
            'date' => $date->format(self::DATE_FORMAT),
            'panMasked' => mb_strcut($pan, 0, 5) . '******' . mb_strcut($pan, strlen($pan)-4, 4),
        ];

        if (empty($data['paymentId'])) {
            $data['paymentId'] = rand(1111111, 999999999);
        }

        if ($data['billerRef'] == 'SOAP_RECO') {
            // confirm
            if ($data['paymentId'] && $data['confirmed']) {
                return "<?xml version='1.0' encoding='UTF-8'?><SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchemainstance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:wsa='http://schemas.xmlsoap.org/ws/2004/08/addressing' xmlns:cs='urn:CardServices' xmlns:ebppif1='urn:PaymentServer' xmlns:el='urn:ExtListsServices' xmlns:iiacs='urn:IIACardServices' xmlns:lm='urn:Limits'><SOAP-ENV:Body SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'><ebppif1:PaymentResponse><paymentID>{$data['paymentId']}</paymentID><paymentRef>{$data['paymentRef']}</paymentRef><details><item><name>pan</name><value>{$data['pan']}</value></item><item><name>expiry</name><value>{$data['expiry']}</value></item><item><name>ccy_code</name><value>{$data['ccyCode']}</value></item><item><name>amount</name><value>{$data['amount']}</value></item><item><name>merchant_id</name><value>{$data['merchantId']}</value></item><item><name>terminal_id</name><value>{$data['terminalId']}</value></item><item><name>point_code</name><value>{$data['pointCode']}</value></item><item><name>centre_id</name><value>{$data['centreId']}</value></item><item><name>internal_pan_masked</name><value>{$data['panMasked']}</value></item><item><name>netsw_proccode</name><value>000000</value></item><item><name>sli</name><value>210</value></item><item><name>cardholder_amount1a</name><value>{$data['amount']}</value></item><item><name>cardholder_ccy_code</name><value>{$data['ccyCode']}</value></item><item><name>conversion_rate</name><value>1.000000000000000</value></item><item><name>auth_action_code</name><value>000</value></item><item><name>auth_action_code_final</name><value>000</value></item><item><name>auth_appr_code</name><value>351175</value></item><item><name>auth_appr_code1a</name><value>351175</value></item><item><name>auth_ref_number</name><value>200604901380</value></item><item><name>auth_stan</name><value>901474</value></item><item><name>auth_stan1a</name><value>901380</value></item><item><name>auth_time</name><value>{$data['date']}</value></item><item><name>stip_client_id</name><value>00005754</value></item><item><name>card_type</name><value>01</value></item><item><name>merchant_name</name><value>Test Merch after upg 5 16C>Tashkent UZ</value></item><item><name>acq_inst</name><value>90000901</value></item><item><name>auth_row_numb1a</name><value>7901380</value></item><item><name>ret_ref_numb1a</name><value>{$data['date']}</value></item><item><name>reconcile_info</name><value>{$data['date']}</value></item><item><name>amount1a</name><value>{$data['amount']}</value></item><item><name>amount1a_original</name><value>{$data['amount']}</value></item><item><name>iss_ref_data</name><value>0003025545199{$data['date']}51175ROWN00779013800005011{$data['centreId']}</value></item><item><name>transaction_amount</name><value>{$data['amount']}</value></item><item><name>auth_row_numb1</name><value>7901474</value></item></details><action>10</action></ebppif1:PaymentResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>";
            }

            $this->saveLastData($data);

            // create
            return "<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:wsa='http://schemas.xmlsoap.org/ws/2004/08/addressing' xmlns:cs='urn:CardServices' xmlns:ebppif1='urn:PaymentServer' xmlns:el='urn:ExtListsServices' xmlns:iiacs='urn:IIACardServices' xmlns:lm='urn:Limits'><SOAP-ENV:Body SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'><ebppif1:PaymentResponse><paymentID>{$data['paymentId']}</paymentID><paymentRef>P2P_HUMO-TO-HUMO_{$data['date']}</paymentRef><details><item><name>terminal_id</name><value>{$data['terminalId']}</value></item><item><name>ttl</name><value>10080</value></item><item><name>ttl_action</name><value>0001</value></item></details><action>4</action></ebppif1:PaymentResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>";
        } else {
            // confirm
            if ($data['paymentId'] && $data['confirmed']) {
                return "<?xml version='1.0' encoding='UTF-8'?><SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchemainstance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:wsa='http://schemas.xmlsoap.org/ws/2004/08/addressing' xmlns:cs='urn:CardServices' xmlns:ebppif1='urn:PaymentServer' xmlns:el='urn:ExtListsServices' xmlns:iiacs='urn:IIACardServices' xmlns:lm='urn:Limits'><SOAP-ENV:Body SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'><ebppif1:PaymentResponse><paymentID>{$data['paymentId']}</paymentID><paymentRef>{$data['paymentRef']}</paymentRef><details><item><name>pan</name><value>{$data['pan']}</value></item><item><name>expiry</name><value>{$data['expiry']}</value></item><item><name>ccy_code</name><value>{$data['ccyCode']}</value></item><item><name>amount</name><value>{$data['amount']}</value></item><item><name>merchant_id</name><value>{$data['merchantId']}</value></item><item><name>terminal_id</name><value>{$data['terminalId']}</value></item><item><name>point_code</name><value>{$data['pointCode']}</value></item><item><name>centre_id</name><value>{$data['centreId']}</value></item><item><name>internal_pan_masked</name><value>{$data['panMasked']}</value></item><item><name>netsw_proccode</name><value>000000</value></item><item><name>sli</name><value>210</value></item><item><name>cardholder_amount1a</name><value>{$data['amount']}</value></item><item><name>cardholder_ccy_code</name><value>{$data['ccyCode']}</value></item><item><name>conversion_rate</name><value>1.000000000000000</value></item><item><name>auth_action_code</name><value>000</value></item><item><name>auth_action_code_final</name><value>000</value></item><item><name>auth_appr_code</name><value>351175</value></item><item><name>auth_appr_code1a</name><value>351175</value></item><item><name>auth_ref_number</name><value>200604901380</value></item><item><name>auth_stan</name><value>901474</value></item><item><name>auth_stan1a</name><value>901380</value></item><item><name>auth_time</name><value>{$data['date']}</value></item><item><name>stip_client_id</name><value>00005754</value></item><item><name>card_type</name><value>01</value></item><item><name>merchant_name</name><value>Test Merch after upg 5 16C>Tashkent UZ</value></item><item><name>acq_inst</name><value>90000901</value></item><item><name>auth_row_numb1a</name><value>7901380</value></item><item><name>ret_ref_numb1a</name><value>{$data['date']}</value></item><item><name>reconcile_info</name><value>{$data['date']}</value></item><item><name>amount1a</name><value>{$data['amount']}</value></item><item><name>amount1a_original</name><value>{$data['amount']}</value></item><item><name>iss_ref_data</name><value>0003025545199{$data['date']}51175ROWN00779013800005011{$data['centreId']}</value></item><item><name>transaction_amount</name><value>{$data['amount']}</value></item><item><name>auth_row_numb1</name><value>7901474</value></item></details><action>10</action></ebppif1:PaymentResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>";
            }

            $this->saveLastData($data);

            return "<?xml version='1.0' encoding='UTF-8'?><SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:wsa='http://schemas.xmlsoap.org/ws/2004/08/addressing' xmlns:cs='urn:CardServices' xmlns:ebppif1='urn:PaymentServer' xmlns:el='urn:ExtListsServices' xmlns:iiacs='urn:IIACardServices' xmlns:lm='urn:Limits'><SOAP-ENV:Body SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'><ebppif1:PaymentResponse><paymentID>{$data['paymentId']}</paymentID><paymentRef>{$data['paymentRef']}</paymentRef><details><item><name>pan</name><value>{$data['pan']}</value></item><item><name>expiry</name><value>{$data['expiry']}</value></item><item><name>ccy_code</name><value>{$data['ccyCode']}</value></item><item><name>amount</name><value>{$data['amount']}</value></item><item><name>merchant_id</name><value>{$data['merchantId']}</value></item><item><name>terminal_id</name><value>{$data['terminalId']}</value></item><item><name>point_code</name><value>{$data['pointCode']}</value></item><item><name>centre_id</name><value>{$data['centreId']}</value></item><item><name>internal_pan_masked</name><value>{$data['panMasked']}</value></item><item><name>netsw_proccode</name><value>000000</value></item><item><name>sli</name><value>210</value></item><item><name>cardholder_amount1a</name><value>{$data['amount']}</value></item><item><name>cardholder_ccy_code</name><value>{$data['ccyCode']}</value></item><item><name>conversion_rate</name><value>1.000000000000000</value></item><item><name>auth_action_code</name><value>000</value></item><item><name>auth_action_code_final</name><value>000</value></item><item><name>auth_appr_code</name><value>351175</value></item><item><name>auth_appr_code1a</name><value>351175</value></item><item><name>auth_ref_number</name><value>200604901380</value></item><item><name>auth_stan</name><value>901474</value></item><item><name>auth_stan1a</name><value>901380</value></item><item><name>auth_time</name><value>{$data['date']}</value></item><item><name>stip_client_id</name><value>00005754</value></item><item><name>card_type</name><value>01</value></item><item><name>merchant_name</name><value>Test Merch after upg 5 16C&gt;Tashkent   UZ</value></item><item><name>acq_inst</name><value>90000901</value></item><item><name>auth_row_numb1a</name><value>7901380</value></item><item><name>ret_ref_numb1a</name><value>{$data['date']}</value></item><item><name>reconcile_info</name><value>{$data['date']}</value></item><item><name>amount1a</name><value>{$data['amount']}</value></item><item><name>amount1a_original</name><value>{$data['amount']}</value></item><item><name>iss_ref_data</name><value>0003025545199{$data['date']}51175ROWN00779013800005011{$data['centreId']}</value></item><item><name>transaction_amount</name><value>{$data['amount']}</value></item><item><name>auth_row_numb1</name><value>7901474</value></item></details><action>4</action></ebppif1:PaymentResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>";
        }
    }

    /**
     * @param string $xml
     * @return string
     * @throws \Exception
     */
    private function status(string $xml): string
    {
        $params = $this->parseRequestV2($xml, 'GetPayment');

        $paymentRef = $params->paymentRef->__toString();

        $data = $this->getLastData();

        if ($data['paymentRef'] !== $paymentRef) {
            return $this->error('paymentRef не совпадает');
        }

        $status = 'none';
        $finalCode = '000';

        return "<?xml version='1.0' encoding='UTF-8'?><SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchemainstance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:wsa='http://schemas.xmlsoap.org/ws/2004/08/addressing' xmlns:cs='urn:CardServices' xmlns:ebppif1='urn:PaymentServer' xmlns:el='urn:ExtListsServices' xmlns:iiacs='urn:IIACardServices' xmlns:lm='urn:Limits'><SOAP-ENV:Body SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'><ebppif1:GetPaymentResponse><paymentID>{$data['paymentId']}</paymentID><paymentRef>{$data['paymentRef']}</paymentRef><status>{$status}</status><details><item><name>pan</name><value>{$data['pan']}</value></item><item><name>expiry</name><value>{$data['expiry']}</value></item><item><name>ccy_code</name><value>{$data['ccyCode']}</value></item><item><name>amount</name><value>{$data['amount']}</value></item><item><name>merchant_id</name><value>{$data['merchantId']}</value></item><item><name>terminal_id</name><value>{$data['terminalId']}</value></item><item><name>point_code</name><value>{$data['pointCode']}</value></item><item><name>centre_id</name><value>{$data['centreId']}</value></item><item><name>internal_pan_masked</name><value>{$data['panMasked']}</value></item><item><name>netsw_proccode</name><value>000000</value></item><item><name>sli</name><value>210</value></item><item><name>cardholder_amount1a</name><value>{$data['amount']}</value></item><item><name>cardholder_ccy_code</name><value>{$data['ccyCode']}</value></item><item><name>conversion_rate</name><value>1.000000000000000</value></item><item><name>auth_action_code</name><value>000</value></item><item><name>auth_action_code_final</name><value>{$finalCode}</value></item><item><name>auth_appr_code</name><value>517551</value></item><item><name>auth_appr_code1a</name><value>517551</value></item><item><name>auth_ref_number</name><value>111004201726</value></item><item><name>auth_stan</name><value>201727</value></item><item><name>auth_stan1a</name><value>201726</value></item><item><name>auth_time</name><value>20210420092748</value></item><item><name>stip_client_id</name><value>00003466</value></item><item><name>card_type</name><value>01</value></item><item><name>merchant_name</name><value>Test Merch after upg 5 16C>Tashkent UZ</value></item><item><name>acq_inst</name><value>90000901</value></item><item><name>auth_row_numb1a</name><value>5201726</value></item><item><name>ret_ref_numb1a</name><value>111004201726</value></item><item><name>reconcile_info</name><value>{$data['date']}</value></item><item><name>amount1a</name><value>{$data['amount']}</value></item><item><name>amount1a_original</name><value>{$data['amount']}</value></item><item><name>iss_ref_data</name><value>00030255389997111111111112517551ROWN00752017260005011AsiaAllianc</value></item><item><name>transaction_amount</name><value>{$data['amount']}</value></item><item><name>auth_row_numb1</name><value>5201727</value></item><item><name>remaining_amount</name><value>0</value></item><item><name>refunded_amount</name><value>{$data['amount']}</value></item></details></ebppif1:GetPaymentResponse></SOAP-ENV:Body></SOAP-ENV:Envelope>";
    }

    /**
     * @param string $xml
     * @return string
     * @throws \Exception
     */
    private function cancel(string $xml): string
    {
        $params = $this->parseRequestV1($xml, 'CancelRequest');

        $paymentRef = $params->paymentRef->__toString();

        $data = $this->getLastData();

        if ($data['paymentRef'] !== $paymentRef) {
            return $this->error("paymentRef:{$paymentRef} не совпадает c {$data['paymentRef']}");
        }

        return "<?xml version='1.0' encoding='UTF-8'?><SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchemainstance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:wsa='http://schemas.xmlsoap.org/ws/2004/08/addressing' xmlns:cs='urn:CardServices' xmlns:ebppif1='urn:PaymentServer' xmlns:el='urn:ExtListsServices' xmlns:iiacs='urn:IIACardServices' xmlns:lm='urn:Limits'><SOAP-ENV:Header/><SOAP-ENV:Body SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'><ebppif1:CancelRequestResponse/></SOAP-ENV:Body></SOAP-ENV:Envelope>";
    }

    /**
     * @param string $xml
     * @return string
     * @throws \Exception
     */
    private function return(string $xml): string
    {
        $params = $this->parseRequestV1($xml, 'ReturnPayment');

        $paymentRef = $params->paymentRef->__toString();

        $data = $this->getLastData();

        if ($data['paymentRef'] !== $paymentRef) {
            return $this->error("paymentRef:{$paymentRef} не совпадает c {$data['paymentRef']}");
        }

        return "<?xml version='1.0' encoding='UTF-8'?><SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchemainstance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:wsa='http://schemas.xmlsoap.org/ws/2004/08/addressing' xmlns:cs='urn:CardServices' xmlns:ebppif1='urn:PaymentServer' xmlns:el='urn:ExtListsServices' xmlns:iiacs='urn:IIACardServices' xmlns:lm='urn:Limits'><SOAP-ENV:Header/><SOAP-ENV:Body SOAP-ENV:encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'><ebppif1:ReturnPaymentResponse/></SOAP-ENV:Body></SOAP-ENV:Envelope>";
    }

    /**
     * Эмуляция долгого ответа и ошибки. Чтобы проверить нерабочий шлюз
     *
     * @return string
     */
    private function simulateErrorTimeout(): string
    {
        sleep(60);
        $xml = $this->error('Connection timed out');
        $this->errorCode = 100;
        return $xml;
    }

    /**
     * @param $msg
     * @return string
     */
    private function error($msg = 'req_session_initialize(): Not enough input to start session!'): string
    {
        $this->errorCode = 500;
        return "<?xml version='1.0' encoding='UTF-8'?><SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/' xmlns:SOAP-ENC='http://schemas.xmlsoap.org/soap/encoding/' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xmlns:xsd='http://www.w3.org/2001/XMLSchema' xmlns:wsa='http://schemas.xmlsoap.org/ws/2004/08/addressing' xmlns:cs='urn:CardServices' xmlns:ebppif1='urn:PaymentServer' xmlns:el='urn:ExtListsServices' xmlns:iiacs='urn:IIACardServices' xmlns:lm='urn:Limits'><SOAP-ENV:Body><SOAP-ENV:Fault><faultcode>SOAP-ENV:Client</faultcode><faultstring></faultstring><detail><ebppif1:PaymentServerException><provider>EBPP</provider><error>-1</error><description>{$msg}</description><screen>EBPP-1</screen></ebppif1:PaymentServerException></detail></SOAP-ENV:Fault></SOAP-ENV:Body></SOAP-ENV:Envelope>";
    }

    /**
     * @param string $xml
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    private function parseRequestV2(string $xml, string $method): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement($xml);
        $xml->registerXPathNamespace('SOAP-ENV', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('SOAP-ENC', 'http://schemas.xmlsoap.org/soap/encoding/');
        $xml->registerXPathNamespace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xml->registerXPathNamespace('xsd', 'http://www.w3.org/2001/XMLSchema');
        $xml->registerXPathNamespace('ebppif1', 'urn:PaymentServer');

        $paymentResponse = $xml->xpath('/SOAP-ENV:Envelope/SOAP-ENV:Body/ebppif1:' . $method);

        return $paymentResponse[0];
    }

    /**
     * @param string $xml
     * @param string $method
     * @return \SimpleXMLElement
     * @throws \Exception
     */
    private function parseRequestV1(string $xml, string $method): \SimpleXMLElement
    {
        $xml = new \SimpleXMLElement($xml);
        $xml->registerXPathNamespace('soapenv', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('urn', 'urn:PaymentServer');

        $paymentResponse = $xml->xpath('/soapenv:Envelope/soapenv:Body/urn:' . $method);

        file_put_contents(dirname(getcwd()) . '/storage/humo/PaymentServer1.txt', json_encode($paymentResponse));

        return $paymentResponse[0];
    }

    /**
     * Достает составное поле из поля details
     *
     * @param $request
     * @param string $field
     * @return mixed
     */
    protected function getDetailsField($request, string $field)
    {
        if (empty($request->details->item)) {
            return null;
        }

        foreach ($request->details->item as $detail) {
            if (empty($detail) || empty($detail->name)) {
                continue;
            }

            if ($detail->name->__toString() === $field) {
                return isset($detail->value) ? $detail->value->__toString() : null;
            }
        }

        return null;
    }

    /**
     * @param array $data
     */
    private function saveLastData(array $data)
    {
        file_put_contents(dirname(getcwd()) . '/storage/humo/PaymentServer.txt', json_encode($data));
    }

    /**
     * @return array
     */
    private function getLastData(): array
    {
        return json_decode(file_get_contents(dirname(getcwd()) . '/storage/humo/PaymentServer.txt'), true);
    }
}
