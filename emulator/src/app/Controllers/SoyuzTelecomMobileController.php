<?php

namespace App\Controllers;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Эмулирует работу мобильной коммерции Союзтелеком (Россия)
 */
class SoyuzTelecomMobileController extends Controller
{
    /**
     * Максимальное количество операторов
     * Операторы задаються в опциях терминала (ключ settings)
     *
     * @var int
     */
    const MAX_OPERATOR_CODE = 3;

    /**
     * @var int
     */
    protected $errorCode;

    /**
     * Получает номер оператора по мобильному номеру телефона
     *
     * @Route("/fakers/soyuztelecom/smsSender/getOperator")
     */
    public function smsSender(Request $request): Response
    {
        $params = $request->query->all();

        if (empty($params['ctn']) || empty($params['control'])) {
            $xml = $this->error(1, 'Не переданы нужные данные');
        } else {
            $operatorCode = rand(1, self::MAX_OPERATOR_CODE);
            $xml = '<?xml version="1.0" encoding="UTF-8"?><response><result>0</result><operator>'.$operatorCode.'</operator></response>';
        }

        $response = new Response($xml);
        $response->headers->set('Content-Type', 'xml');

        if ($this->errorCode) {
            $response->setStatusCode($this->errorCode);
        }

        return $response;
    }

    /**
     * Инициация оплаты
     *
     * @Route("/fakers/soyuztelecom/initp/order")
     */
    public function initOrder(Request $request): Response
    {
        $params = $request->request->all();

        if (empty($params['orderId']) || empty($params['goodPhone']) || empty($params['ctn'])
            || empty($params['smsText']) || empty($params['dt']) || empty($params['control'])) {
            $xml = $this->error(rand(1, 2),'Не переданы нужные данные');
        } else {
            $id = rand(111111, 999999);
            $xml = '<?xml version="1.0" encoding="UTF-8"?><response><result>0</result><descr></descr><id>'.$id.'</id></response>';
        }

        $response = new Response($xml);
        $response->headers->set('Content-Type', 'xml');

        if ($this->errorCode) {
            $response->setStatusCode($this->errorCode);
        }

        return $response;
    }

    /**
     * Статус оплаты
     *
     * @Route("/fakers/soyuztelecom/status/order")
     */
    public function statusOrder(Request $request): Response
    {
        $params = $request->query->all();
        $statuses = [
            0 => 'Успешный',
            1 => 'В процессе',
            2 => 'Неуспешный',
            3 => 'Не найден',
            4 => 'Найден, но статус неизвестен',
        ];


        if (empty($params['orderId']) || empty($params['control'])) {

            $xml = $this->error(5, 'Не переданы нужные данные');
        } else {
            $status = rand(0, count($statuses) - 1);
            $xml = '<?xml version="1.0" encoding="UTF-8"?><response><state>'.$status.'</state><descr>'.$statuses[$status].'</descr></response>';
        }

        $response = new Response($xml);
        $response->headers->set('Content-Type', 'xml');

        if ($this->errorCode) {
            $response->setStatusCode($this->errorCode);
        }

        return $response;
    }

    /**
     * @param int $code
     * @param string $msg
     * @return string
     */
    private function error(int $code, string $msg = 'Ошибка'): string
    {
        $this->errorCode = 500;
        return '<?xml version="1.0" encoding="UTF-8"?><response><result>'.$code.'</result><descr>'.$msg.'</descr></response>';
    }
}
