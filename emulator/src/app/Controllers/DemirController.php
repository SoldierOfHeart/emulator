<?php

namespace App\Controllers;

use App\Helpers\Helper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DemirController extends Controller
{
    /**
     * @Route("/fakers/demir/3dsgate")
     */
    public function pay(Request $request)
    {
        $acsUrl = $this->generateUrl('3ds', [], UrlGeneratorInterface::ABSOLUTE_URL);
        return new Response("<!DOCTYPE html \"about:legacy-compat\">\n<html class=\"no-js\" lang=\"en\" xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>\n<meta charset=\"utf-8\"/>\n<title>3D Secure Processing</title>\n</head>\n<body>\n<div id=\"main\">\n<div id=\"content\">\n<div id=\"order\">\n<img src=\"https://3d.payten.com.tr/mdpaympi/static/preloader.gif\" alt=\"Please wait..\"/>\n<img src=\"https://3d.payten.com.tr/mdpaympi/static/verifiedbyvisa.png\" alt=\"Verified by VISA\"/>\n<div id=\"formdiv\">\n<script type=\"text/javascript\">\nfunction hideAndSubmitTimed(formid)\n{\nvar timer=setTimeout(\"hideAndSubmit(\'\"+formid+\"\');\",100);\n}\n\nfunction hideAndSubmit(formid)\n{\nvar formx=document.getElementById(formid);\n\tif (formx!=null)\n\t{\n\tformx.style.visibility=\"hidden\";\n\tformx.submit();\n\t}\n}\n</script>\n<div>\n<form id=\"webform0\" name=\"red2ACSv1\" method=\"POST\" action=\"$acsUrl\" accept_charset=\"UTF-8\">\n<input type=\"hidden\" name=\"_charset_\" value=\"UTF-8\"/>\n<input type=\"hidden\" name=\"PaReq\" value=\"eJxVUttSwjAQ/ZUOH0AuJbZllsyAOMo4aqF4wRcntkE60Atpi5SvN2mLaF6y52x2s+cksNwoKaeBDCslOTzIohBf0oqjUc9fzD17QKmDKetx8McLuedwkKqIs5STPu5TQGeoK1W4EWnJQYT7yeyRD4hDiQeog5BINZtyjAluF6EuY4BaGlKRSO6PV9bk6c1iLqHWiw+oYSHMqrRUNXc8feEZQKV2fFOWeTFEKBf1Z3bsJ1kqa7Q9fagKkMkDuozlVyYqdL9jHPGbYHG1my6DmK2j7fcrSk679buYB6vD8wiQOQGRKCWnmBJsY2oRZ2iT4UBP3PAgEjMIv78NLMJs1sdYS205yM1V4xaYpMn95UCbrWQa1sYkLemMQB5zLUGf0EJ/Y4hkEWop3XbRcX1nzA5L7R+xXcpc18PEGN5QplmsjaI29ppuBgAyRah7S9Q9t47+fYMf4h6scg==\"/>\n<input type=\"hidden\" name=\"MD\" value=\"421589:91D58D016A12C890A9D01F5004994458E4CB2EC048AD53751479FB782DDF8E45:3874:##170000128\"/>\n\t<input type=\"hidden\" name=\"TermUrl\" value=\"https://ecommerce.demirbank.kg/fim/est3Dgate\"/> \n<input type=\"submit\" name=\"submitBtn\" value=\"Please click here to continue\"/>\n</form>\n</div>\n</div>\n<script type=\"text/javascript\">\n\t\t\thideAndSubmitTimed(\'webform0\');\n\t\t</script>\n<noscript>\n<div align=\"center\">\n<b>Javascript is turned off or not supported!</b>\n<br/>\n</div>\n</noscript>\n</div>\n<div id=\"content-footer\"/>\n</div>\n</div>\n</body>\n</html>");
    }

    /**
     * @Route("/fakers/demir/post3ds")
     */
    public function post3ds()
    {
        return new Response('<?xml version="1.0" encoding="ISO-8859-9"?>
<CC5Response>
  <OrderId>438632373</OrderId>
  <GroupId>438632373</GroupId>
  <Response>Approved</Response>
  <AuthCode>031557</AuthCode>
  <HostRefNum>106117270784</HostRefNum>
  <ProcReturnCode>00</ProcReturnCode>
  <TransId>21061RgDG00108439</TransId>
  <ErrMsg></ErrMsg>
  <Extra>
    <SETTLEID>322</SETTLEID>
    <TRXDATE>20210302 20:32:03</TRXDATE>
    <ERRORCODE></ERRORCODE>
    <CARDBRAND>VISA</CARDBRAND>
    <CARDISSUER>CJSC DEMIR KYRGYZ INTERNATIONAL BANK</CARDISSUER>
    <NUMCODE>00</NUMCODE>
  </Extra>
</CC5Response>');
    }
}