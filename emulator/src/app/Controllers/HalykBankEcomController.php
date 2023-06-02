<?php

namespace App\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HalykBankEcomController extends Controller
{
    /**
     * @Route("/fakers/halyk")
     */
    public function request(Request $request)
    {
        $str = "<?xml version='1.0' encoding='utf-8'?><soapenv:Envelope xmlns:soapenv=\"http://www.w3.org/2003/05/soap-envelope\"><soapenv:Header/><soapenv:Body><ns:paymentOrderResponse xmlns:ns=\"http://ws.epay.kkb.kz/xsd\"><return xmlns:ax21=\"http://cert.security.java/xsd\" xmlns:ax210=\"http://models.ws.epay.kkb.kz/xsd\" xmlns:ax22=\"http://security.java/xsd\" xmlns:ax26=\"http://io.java/xsd\" xmlns:ax28=\"http://sax.xml.org/xsd\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:type=\"ax210:Result\"><acsUrl>null</acsUrl><approvalcode>124922</approvalcode><date>null</date><intreference>FA20201102124922</intreference><is3ds>false</is3ds><md>null</md><message>Approved-0</message><orderid>4571775</orderid><pareq>null</pareq><reference>201102124922</reference><responseSignature xsi:type=\"ns:ResponseSignature\"><signatureValue>JipMB+5fiTIGaFA5TG2lOAYR0Gay4zAQel0nRgKNSnVVNJqUo1iuukinpd/o3+yajhpuiuvHTDsh1zIFJprKLpWDRTMGYBVSWo/nR3VhoauKmSG8OPAC366h8h2Kn3ebh6AOk4rWqZ8fgX4ViAy6IGNs/vwkbfsSVJjptIk0C0I=</signatureValue><signedString>00Approved-0201102124922</signedString></responseSignature><returnCode>00</returnCode><sessionid>4D8112DC00D8E9DB4F7EE19D1DBDDAB0</sessionid><termUrl>92061103</termUrl></return></ns:paymentOrderResponse></soapenv:Body></soapenv:Envelope>";
        /** Error Flow **
        $str = '<?xml version=\'1.0\' encoding=\'utf-8\'?><soapenv:Envelope xmlns:soapenv="http://www.w3.org/2003/05/soap-envelope"><soapenv:Body><ns:paymentOrderResponse xmlns:ns="http://ws.epay.kkb.kz/xsd"><return xmlns:ax28="http://sax.xml.org/xsd" xmlns:ax210="http://models.ws.epay.kkb.kz/xsd" xmlns:ax26="http://io.java/xsd" xmlns:ax22="http://security.java/xsd" xmlns:ax21="http://cert.security.java/xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:type="ax210:Result"><acsUrl>null</acsUrl><approvalcode></approvalcode><date>null</date><intreference></intreference><is3ds>false</is3ds><md>null</md><message>JSP-10</message><orderid>530058530</orderid><pareq>null</pareq><reference></reference><responseSignature xsi:type="ns:ResponseSignature"><signatureValue>B7gAQZfndFfuNUkV7g/h+dbSsXCYYRrtA12V1PzzO9Vj+vv7r/6g6HF2cVhGKoUE3MDZj3obX44GmUkbWjrgr/6n3pgF00dUno/LOy9RqdfqAU/LlxXjIFygk3DAAoVXyilfJpxLk2On94WJsUgmJWZgXZDoWTa4P0O2oT2pU2Y=</signatureValue><signedString>-19JSP-10</signedString></responseSignature><returnCode>-19</returnCode><sessionid>D0B021D1F447602CA1CF29CC741501A9</sessionid><termUrl>92061103</termUrl></return></ns:paymentOrderResponse></soapenv:Body></soapenv:Envelope>';
        /**/
        $response = new Response($str);
        $response->headers->set('Content-Type', 'xml');

        return $response;
    }
}