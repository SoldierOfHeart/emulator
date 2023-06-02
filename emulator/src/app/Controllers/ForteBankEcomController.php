<?php

namespace App\Controllers;

use App\Fakers\ForteBankEcom;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForteBankEcomController extends Controller
{
    const
        OPR_PURCHASE            = 'Purchase',
        OPR_REFUND              = 'Refund',
        OPR_REVERSE             = 'Reverse',
        OPR_COMPLETION          = 'Completion',
        OPR_P2P_TRANSFER        = 'P2PTransfer',
        OPR_CREATE_ORDER        = 'CreateOrder',
        OPR_PROCESS_PARES       = 'ProcessPARes',
        OPR_GET_PA_REQ_FORM     = 'GetPAReqForm',
        OPR_GET_ORDER_STATUS    = 'GetOrderStatus',
        OPR_CHECK_3DS_ENROLLED  = 'Check3DSEnrolled',
        OPR_GET_ORDER_INFORMATION = 'GetOrderInformation'
    ;

    private ForteBankEcom $faker;

    public function __construct()
    {
        $this->faker = new ForteBankEcom();
    }

    /**
     * @Route("/fakers/forte")
     */
    public function index(Request $request)
    {
        $status = $request->get('f_status', 'success');

        $xml       = new \SimpleXMLElement($request->getContent());
        $operation = (string)$xml->Request->Operation;

        switch ($operation) {
            case self::OPR_CREATE_ORDER:
                return $this->createOrder($request, $status);

            case self::OPR_CHECK_3DS_ENROLLED:
                return $this->check3dsEnrolled($request, $status);

            case self::OPR_GET_PA_REQ_FORM:
                return $this->getPAReqForm($request, $status);

            case self::OPR_PROCESS_PARES:
                return $this->processPARes($request, $status);

            case self::OPR_PURCHASE:
                return $this->purchase($request, $status);

            case self::OPR_COMPLETION:
                return $this->clearing($request, $status);

            case self::OPR_REFUND:
                return $this->refund($request, $status);

            case self::OPR_REVERSE:
                return $this->reverse($request, $status);

            case self::OPR_GET_ORDER_INFORMATION:
                return $this->getOrderInformation($request, $status);

            default:
                return $this->createOrder($request, 'error');
        }
    }

    private function createOrder(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->createOrderSuccess($request));
        }

        return $this->response($this->faker->createOrderError());
    }

    private function check3dsEnrolled(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->check3dsEnrolledSuccess($request));
        }

        return $this->response($this->faker->check3dsEnrolledError());
    }

    private function getPAReqForm(Request $request, string $status)
    {
        if ($status === 'success') {
            $request->request->add([
                'acs_url' => $this->generateUrl('3ds', [], UrlGeneratorInterface::ABSOLUTE_URL)
            ]);

            return $this->response($this->faker->getPAReqFormSuccess($request));
        }

        return $this->response($this->faker->getPAReqFormError());
    }

    private function processPARes(Request $request, string $status)
    {
        $response = new Response('<TKKPG><Response><Operation>ProcessPARes</Operation><Status>00</Status><XMLOut><Message date="16/03/2021 11:36:33"><SessionId>034F077BB1E3FF0A46DB82E237C66B79</SessionId><PAN>5169-49XX-XXXX-5957</PAN><Brand>MC</Brand><TotalAmount>1000</TotalAmount><PurchaseAmount>1000</PurchaseAmount><OrderStatus>PREAUTH-APPROVED</OrderStatus><ThreeDSVars><PrefixParam><PurchaseIfNotEnrolled>true</PurchaseIfNotEnrolled><PurchaseIfNotVerified>true</PurchaseIfNotVerified><POSConditionIfNotEnrolled>81</POSConditionIfNotEnrolled><PurchaseIfAttempt>true</PurchaseIfAttempt><POSConditionIfEnrolledU>83</POSConditionIfEnrolledU><PurchaseIfUnknown>true</PurchaseIfUnknown><TypeCard>MC</TypeCard></PrefixParam><AnswerVars><xid>MTYxNTg3Mjk4ODE4MjAwMDAwMDA=</xid><CAVV>hgHZuTB3KcrBYwASHUjOCDMAAAA=</CAVV><eci>01</eci><ThreeDSVersion>1</ThreeDSVersion><Enrolled>Y</Enrolled><enrolled>Y</enrolled><ThreeDSVerification>A</ThreeDSVerification><ThreeDSVerificaion>A</ThreeDSVerificaion><HexCAVV>31363135383732393838313832303030303030308601D9B9307729CAC16300121D48CE0833000000</HexCAVV><CalculatedCAVV>3136313538373239383831383230303030303030hgHZuTB3KcrBYwASHUjOCDMAAAA=</CalculatedCAVV></AnswerVars></ThreeDSVars><OrderStatusScr>Предавторизация одобрена</OrderStatusScr><RezultOperation>Результат выполнения операции</RezultOperation><MerchantTranID>MTYxNTg3Mjk4ODE4MjAwMDAwMDA=</MerchantTranID><Response_g>Транзакция прошла успешно. RRN: 107505103865, ID транзакции: 1473743167, номер заказа: 14842605</Response_g><AuthorizationResponseCode>01</AuthorizationResponseCode><ShopName>ASIAPAY.KZ</ShopName><ThreeDSVerification>A</ThreeDSVerification><ThreeDSVerificaion>A</ThreeDSVerificaion><SessionID>034F077BB1E3FF0A46DB82E237C66B79</SessionID><TransactionType>PreAuth</TransactionType><OrderDescription>Ticket</OrderDescription><ApprovalCode>323254 A</ApprovalCode><ApprovalCodeScr>323254</ApprovalCodeScr><RRN>107505103865</RRN><PurchaseAmountScr>10.00</PurchaseAmountScr><TotalAmountScr>10.00</TotalAmountScr><FeeScr>0.00</FeeScr><AcqFeeScr>0.00</AcqFeeScr><CurrencyScr>Тенге</CurrencyScr><CurrencyISOAlpha>KZT</CurrencyISOAlpha><TranDateTime>16/03/2021 11:36:33</TranDateTime><ResponseDescription>Удачное выполнение транзакции</ResponseDescription><OrderID>14842605</OrderID><Version>1.0</Version><Currency>398</Currency><ResponseCode>001</ResponseCode><Language>RU</Language></Message></XMLOut></Response></TKKPG>');
        $response->headers->set('Content-Type', 'xml');

        return $response;

        if ($status === 'success') {
            return $this->response($this->faker->processPAResSuccess($request));
        }

        return $this->response($this->faker->processPAResError());
    }

    private function purchase(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->purchaseSuccess($request));
        }

        return $this->response($this->faker->purchaseError());
    }

    private function clearing(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->clearingSuccess($request));
        }

        return $this->response($this->faker->clearingError());
    }

    private function refund(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->refundSuccess($request));
        }

        return $this->response($this->faker->refundError());
    }

    private function reverse(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->reverseSuccess($request));
        }

        return $this->response($this->faker->reverseError());
    }

    private function getOrderInformation(Request $request, string $status)
    {
        if ($status === 'success') {
            return $this->response($this->faker->getOrderInformationSuccess($request));
        }

        return $this->response($this->faker->getOrderInformationError());
    }

    private function response(array $data)
    {
        $xml = new \SimpleXMLElement('<TKKPG/>');

        $this->buildXml($data, $xml);

        $response = new Response($xml->asXML());
        $response->headers->set('Content-Type', 'xml');

        return $response;
    }

    private function buildXml(array $data, \SimpleXMLElement &$xml, $parentKey = null)
    {
        foreach ($data as $key => $value) {
            if (is_array($value) && $key !== 'attrs') {
                if(is_numeric($key) && $parentKey){
                    $key = $parentKey;
                }

                if (is_numeric(array_key_first($value))) {
                    $this->buildXml($value, $xml, $key);
                } else {
                    $child = $xml->addChild($key);
                    $this->buildXml($value, $child);
                }

                continue;
            }

            if (is_array($value) && $key === 'attrs') {
                foreach ($value as $attrName => $attrValue) {
                    $xml->addAttribute($attrName, $attrValue);
                }

                continue;
            }

            $xml->addChild($key, $value);
        }
    }
}