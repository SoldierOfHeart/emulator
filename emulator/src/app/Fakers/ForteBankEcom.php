<?php

namespace App\Fakers;

use App\Helpers\Helper;
use Symfony\Component\HttpFoundation\Request;

class ForteBankEcom
{
    public function createOrderSuccess(Request $request)
    {
        return [
            'Response' => [
                'Operation' => 'CreateOrder',
                'Status'    => '00',
                'Order'     => [
                    'OrderID'   => Helper::randomInt(7),
                    'SessionID' => Helper::randomStr(32),
                    'URL'       => 'https://epay.fortebank.com/',
                ]
            ]
        ];
    }

    public function createOrderError()
    {
        return [
            'Response' => [
                'Operation' => 'CreateOrder',
                'Status'    => '96',
            ]
        ];
    }

    public function check3dsEnrolledSuccess(Request $request)
    {
        return [
            'Response' => [
                'Operation' => 'Check3DSEnrolled',
                'CardBrand' => 'MC',
                'Status'    => '00',
                'VERes'     => [
                    'ThreeDSecure' => [
                        'Message'   => [
                            'attrs' => ['id' => 'vereq_5354094'],
                            'VERes' => [
                                'version' => '1.0.2',
                                'CH' => [
                                    'enrolled' => 'Y',
                                    'acctID'   => 'ubPZ5M6tQginTyF+ug5c/wMJBAE=',
                                ],
                                'url'      => '',
                                'protocol' => 'ThreeDSecure',
                            ]
                        ]
                    ],
                ]
            ]
        ];
    }

    public function check3dsEnrolledError()
    {
        return [
            'Response' => [
                'Operation' => 'Check3DSEnrolled',
                'Status'    => '96',
            ]
        ];
    }

    public function getPAReqFormSuccess(Request $request)
    {
        return [
            'Response' => [
                'Operation' => 'GetPAReqForm',
                'Status'    => '00',
                'url'       => $request->get('acs_url'),
                'MD'        => '',
                'termURL'   => '',
                'pareq'     => Helper::randomStr(1000),
            ]
        ];
    }

    public function getPAReqFormError()
    {
        return [
            'Response' => [
                'Operation' => 'GetPAReqForm',
                'Status'    => '96',
            ]
        ];
    }

    public function processPAResSuccess(Request $request)
    {
        return [
            'Response' => [
                'Operation' => 'Purchase',
                'Status'    => '00',
                'XMLOut'    => [
                    'Message' => [
                        'attrs' => [
                            'date' => date('d/m/Y H:i:s')
                        ],
                        'Version'             => '1.0',
                        'OrderID'             => '5354094',
                        'TransactionType'     => 'Purchase',
                        'RRN'                 => '029708116921',
                        'PAN'                 => '4444 4444 4444 4444',
                        'PurchaseAmount'      => '600000',
                        'PurchaseAmountScr'   => '6 000.00',
                        'Currency'            => '398',
                        'TranDateTime'        => '23/10/2020 14:30:17',
                        'ResponseCode'        => '001',
                        'ResponseDescription' => 'Удачное выполнение транзакции',
                        'Brand'               => 'MC',
                        'OrderStatus'         => 'APPROVED',
                        'ApprovalCode'        => '265179',
                        'ApprovalCodeScr'     => '265179',
                        'AcqFee'              => '0.00',
                        'OrderDescription'    => 'ded27b2b-efd4-422a-a8c6-fb7b0423b1ea',
                        'CardHolderName'      => '',
                        'CurrencyScr'         => 'Тенге',
                        'OrderStatusScr'      => 'Одобрен',
                        'RezultOperation'     => 'Результат выполнения операции',
                        'Response_g'          => 'Транзакция прошла успешно. RRN: 29708116921, ID транзакции: 1116165454, номер заказа: 5354094',
                    ],
                ]
            ]
        ];
    }

    public function processPAResError()
    {
        return [
            'Response' => [
                'Operation' => 'Purchase',
                'Status'    => '00',
                'XMLOut'    => [
                    'Message' => [
                        'attrs' => [
                            'date' => date('d/m/Y H:i:s')
                        ],
                        'Version'             => '1.0',
                        'OrderID'             => '5354094',
                        'TransactionType'     => 'Purchase',
                        'RRN'                 => '029708116921',
                        'PAN'                 => '4444 4444 4444 4444',
                        'PurchaseAmount'      => '600000',
                        'PurchaseAmountScr'   => '6 000.00',
                        'Currency'            => '398',
                        'TranDateTime'        => '23/10/2020 14:30:17',
                        'ResponseCode'        => '050',
                        'ResponseDescription' => 'Финансовая транзакция не выполнена',
                        'Brand'               => 'MC',
                        'OrderStatus'         => 'DECLINED',
                        'ApprovalCode'        => '',
                        'ApprovalCodeScr'     => '',
                        'AcqFee'              => '0.00',
                        'OrderDescription'    => 'ded27b2b-efd4-422a-a8c6-fb7b0423b1ea',
                        'CardHolderName'      => '',
                        'CurrencyScr'         => 'Тенге',
                        'OrderStatusScr'      => 'Отказ в оплате',
                        'RezultOperation'     => 'Результат выполнения операции',
                    ],
                ]
            ]
        ];
    }

    public function purchaseSuccess(Request $request)
    {
        return $this->processPAResSuccess($request)['Response'];
    }

    public function purchaseError()
    {
        return $this->processPAResError()['Response'];
    }

    public function clearingSuccess(Request $request)
    {
        return [
            'Response' => [
                'Operation' => 'Completion',
                'Status'    => '00',
                'POSResponse' => [
                    'l' => [
                        'attrs' => [
                            'name'  => 'ResponseCode',
                            'value' => '001',
                        ]
                    ],
                    'f' => [
                        [
                            'attrs' => [
                                'name'  => 'R',
                                'value' => 'D',
                            ]
                        ],
                        [
                            'attrs' => [
                                'name'  => 'a',
                                'value' => '&F1000#&C643#&R01#',
                            ]
                        ],
                        [
                            'attrs' => [
                                'name'  => 'h',
                                'value' => '0010010090',

                            ]
                        ],
                        [
                            'attrs' => [
                                'name'  => 't',
                                'value' => '4634791',

                            ]
                        ],
                    ],
                ],
            ],
        ];
    }

    public function clearingError()
    {
        return [
            'Response' => [
                'Operation' => 'Completion',
                'Status'    => '',
                'POSResponse' => [
                    'l' => [
                        'attrs' => [
                            'name'  => 'ResponseCode',
                            'value' => '96',
                        ]
                    ],
                ],
            ]
        ];
    }

    public function refundSuccess(Request $request)
    {
        return [
            'Response' => [
                'Operation' => 'Refund',
                'Status'    => '00',
            ]
        ];
    }

    public function refundError()
    {
        return [
            'Response' => [
                'Operation' => 'Refund',
                'Status'    => '96',
            ]
        ];
    }

    public function reverseSuccess(Request $request)
    {
        return [
            'Response' => [
                'Operation' => 'Reverse',
                'Status'    => '00',
                'Order'     => [
                    'OrderID' => '1234'
                ],
                'Reversal'  => [
                    'RespCode'    => '001',
                    'RespMessage' => '',
                ]
            ]
        ];
    }

    public function reverseError()
    {
        return [
            'Response' => [
                'Operation' => 'Reverse',
                'Status'    => '00',
                'Order'     => [
                    'OrderID' => '1234'
                ],
                'Reversal'  => [
                    'RespCode'    => '96',
                    'RespMessage' => '',
                ]
            ]
        ];
    }

    public function getOrderInformationSuccess(Request $request)
    {
        return [
            'Response' => [
                'Operation' => 'GetOrderInformation',
                'Status' => '00',
                'Order' => [
                    'row' => [
                        'id'                => '5354094',
                        'SessionID'         => '1DF0E1DC0A5E7F9FBABCD2DFBEBCEAA8',
                        'createDate'        => '2020-10-23 14:29:47',
                        'lastUpdateDate'    => '2020-10-23 14:30:17',
                        'payDate'           => '2020-10-23 14:30:17',
                        'MerchantID'        => 'ASIAPAY02007373',
                        'Amount'            => '600000',
                        'Currency'          => '398',
                        'OrderLanguage'     => 'RU',
                        'Description'       => 'ded27b2b-efd4-422a-a8c6-fb7b0423b1ea',
                        'ApproveURL'        => '',
                        'CancelURL'         => '',
                        'DeclineURL'        => '',
                        'Orderstatus'       => 'APPROVED',
                        'Receipt'           => '',
                        'twoId'             => '1116165454',
                        'RefundAmount'      => '0',
                        'RefundCurrency'    => 'null',
                        'ExtSystemProcess'  => '0',
                        'OrderType'         => 'Purchase',
                        'OrderSubType'      => '',
                        'Fee'               => '0',
                        'RefundDate'        => '0000-00-00 00:00:00',
                        'TWODate'           => '201023',
                        'TWOTime'           => '143017',
                        'OrderParams'       => [
                            'row' => [
                                [
                                    'PARAMNAME' => 'RRN',
                                    'VAL'       => '029708116921',
                                ],
                                [
                                    'PARAMNAME' => 'XMLOUT',
                                    'VAL'       => [
                                        'XMLOut' => [
                                            'Message' => [
                                                'attrs' => [
                                                    'date' => date('d/m/Y H:i:s')
                                                ],
                                                'Version'             => '1.0',
                                                'OrderID'             => '5354094',
                                                'TransactionType'     => 'Purchase',
                                                'RRN'                 => '029708116921',
                                                'PAN'                 => '5169 49XX XXXX 3941',
                                                'PurchaseAmount'      => '600000',
                                                'PurchaseAmountScr'   => '6 000.00',
                                                'Currency'            => '398',
                                                'TranDateTime'        => '23/10/2020 14:30:17',
                                                'ResponseCode'        => '001',
                                                'ResponseDescription' => 'Удачное выполнение транзакции',
                                                'Brand'               => 'MC',
                                                'OrderStatus'         => 'APPROVED',
                                                'ApprovalCode'        => '265179',
                                                'ApprovalCodeScr'     => '265179',
                                                'AcqFee'              => '0.00',
                                                'OrderDescription'    => 'ded27b2b-efd4-422a-a8c6-fb7b0423b1ea',
                                                'CardHolderName'      => '',
                                                'CurrencyScr'         => 'Тенге',
                                                'OrderStatusScr'      => 'Одобрен',
                                                'RezultOperation'     => 'Результат выполнения операции',
                                                'Response_g'          => 'Транзакция прошла успешно. RRN: 29708116921, ID транзакции: 1116165454, номер заказа: 5354094',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function getOrderInformationError()
    {
        return [
            'Response' => [
                'Operation' => 'GetOrderInformation',
                'Status' => '00',
                'Order' => [
                    'row' => [
                        'id'                => '5354094',
                        'SessionID'         => '1DF0E1DC0A5E7F9FBABCD2DFBEBCEAA8',
                        'createDate'        => '2020-10-23 14:29:47',
                        'lastUpdateDate'    => '2020-10-23 14:30:17',
                        'payDate'           => '2020-10-23 14:30:17',
                        'MerchantID'        => 'ASIAPAY02007373',
                        'Amount'            => '600000',
                        'Currency'          => '398',
                        'OrderLanguage'     => 'RU',
                        'Description'       => 'ded27b2b-efd4-422a-a8c6-fb7b0423b1ea',
                        'ApproveURL'        => '',
                        'CancelURL'         => '',
                        'DeclineURL'        => '',
                        'Orderstatus'       => 'DECLINED',
                        'Receipt'           => '',
                        'twoId'             => '',
                        'RefundAmount'      => '0',
                        'RefundCurrency'    => 'null',
                        'ExtSystemProcess'  => '0',
                        'OrderType'         => 'Purchase',
                        'OrderSubType'      => '',
                        'Fee'               => '0',
                        'RefundDate'        => '0000-00-00 00:00:00',
                        'TWODate'           => null,
                        'TWOTime'           => null,
                        'OrderParams'       => [
                            'row' => [
                                [
                                    'PARAMNAME' => 'RRN',
                                    'VAL'       => '029708116921',
                                ],
                                [
                                    'PARAMNAME' => 'XMLOUT',
                                    'VAL'       => [
                                        'XMLOut' => [
                                            'Message' => [
                                                'attrs' => [
                                                    'date' => date('d/m/Y H:i:s')
                                                ],
                                                'Version'             => '1.0',
                                                'OrderID'             => '5354094',
                                                'TransactionType'     => 'Purchase',
                                                'RRN'                 => '029708116921',
                                                'PAN'                 => '5169 49XX XXXX 3941',
                                                'PurchaseAmount'      => '600000',
                                                'PurchaseAmountScr'   => '6 000.00',
                                                'Currency'            => '398',
                                                'TranDateTime'        => '23/10/2020 14:30:17',
                                                'ResponseCode'        => '050',
                                                'ResponseDescription' => 'Финансовая транзакция не выполнена',
                                                'Brand'               => 'MC',
                                                'OrderStatus'         => 'DECLINED',
                                                'ApprovalCode'        => '',
                                                'ApprovalCodeScr'     => '',
                                                'AcqFee'              => '0.00',
                                                'OrderDescription'    => 'ded27b2b-efd4-422a-a8c6-fb7b0423b1ea',
                                                'CardHolderName'      => '',
                                                'CurrencyScr'         => 'Тенге',
                                                'OrderStatusScr'      => 'Отказ в оплате',
                                                'RezultOperation'     => 'Результат выполнения операции',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}