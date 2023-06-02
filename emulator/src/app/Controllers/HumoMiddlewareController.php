<?php

namespace App\Controllers;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Эмулирует Humo Middleware API (Узбекистан)
 */
class HumoMiddlewareController extends Controller
{
    /**
     * @Route("/fakers/humo/middleware/v2/iiacs/card")
     */
    public function index(Request $request): Response
    {
        $json = '{"id":"1k23j25hlgf2hhf5","result":{"card":{"institutionId":"AsiaAllianc","primaryAccountNumber":"986009******6138","effectiveDate":"2021-08-30T07:11:51.000Z","updateDate":"2021-08-30T07:16:04.000Z","prefixNumber":2,"expiry":"2608","cardSequenceNumber":1,"cardholderId":"000046410","nameOnCard":"JOHNDOE","accountRestrictionsFlag":"E","commissionGroup":"01001","cardUserId":"00004610","additionalInfo":"BRD00819790301IDN0100121087108CBS0011AGN0071022486","riskGroup":"A","riskGroup2":"A","bankC":"09","pinTryCount":0,"statuses":{"item":[{"type":"card","actionCode":"125","actionDescription":"Decline,cardnoteffective","effectiveDate":"2021-08-30T07:11:51.000Z"},{"type":"card2","actionCode":"118","actionDescription":"Decline,nocardrecord","effectiveDate":"2021-08-30T07:11:51.000Z"},{"type":"hot","actionCode":"000","actionDescription":"Approved"},{"type":"issuer","actionCode":"000","actionDescription":"Approved"},{"type":"user","actionCode":"000","actionDescription":"Approved"}]},"mb":{"state":"on","phone":"+99899*****96","message":"Statusofthecustomermobileagreementisactive"}}}}';

        $response = new Response($json);

        $response->headers->set('Content-Type', 'json');

        return $response;
    }

    /**
     * @param int $code
     * @param string $msg
     * @return string
     */
    private function error(int $code = 152, string $msg = 'error message'): string
    {
        return "{'id': '121d5sd5sd45sds4fd5','error': {'code': {$code},'message': '{$msg}}}";
    }
}
