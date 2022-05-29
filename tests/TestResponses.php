<?php

namespace Kaca\Tests;

class TestResponses
{
    public static array $cashier_me = [
        "id" => "d8acda9b-f93e-4865-xxxx-3ece40daaf0b",
        "full_name" => "cashier full name",
        "nin" => "000000000",
        "key_id" => "test_key_id",
        "signature_type" => "TEST",
        "created_at" => "2022-01-11T16:56:09+00:00",
        "updated_at" => "2022-01-12T15:34:53+00:00",
        "certificate_end" => null,
    ];
    public static array $sign_in = [
        "type" => "bearer",
        "token_type" => "bearer",
        "access_token" => "token_hash"
    ];

    public static array $shift_status_created = [
        "id" => "8d4471ff-726c-4ec7-bfda-73f6a048c6a2",
        "serial" => 4,
        "status" => "CREATED",
        "z_report" => null,
        "opened_at" => null,
        "closed_at" => null,
        "initial_transaction" => [],
        "closing_transaction" => null,
        "created_at" => "2022-02-05T06:00:07.605275+00:00",
        "updated_at" => null,
        "balance" => [
            "initial" => 0,
            "balance" => 0,
            "cash_sales" => 0,
            "card_sales" => 0,
            "cash_returns" => 0,
            "card_returns" => 0,
            "service_in" => 0,
            "service_out" => 0,
            "updated_at" => null
        ],
        "taxes" => [],
        "cash_register" => [
            "id" => "ca87c795-23fc-48da-b9d7-89957f2a0cd3",
        ],
        "cashier" => [
            "id" => "aac3863c-e30b-48c7-9041-7680103d00a8",
        ]
    ];

    public static array $shift_status_opened = [
        "id" => "8d4471ff-726c-4ec7-bfda-73f6a048c6a2",
        "serial" => 4,
        "status" => "OPENED",
        "z_report" => null,
        "opened_at" => '2022-02-05T06:00:04.344880+00:00',
        "closed_at" => null,
        "initial_transaction" => [],
        "closing_transaction" => null,
        "created_at" => "2022-02-05T06:00:07.605275+00:00",
        "updated_at" => "2022-02-05T06:00:04.823865+00:00",
        "balance" => [
            "initial" => 0,
            "balance" => 0,
            "cash_sales" => 0,
            "card_sales" => 0,
            "cash_returns" => 0,
            "card_returns" => 0,
            "service_in" => 0,
            "service_out" => 0,
            "updated_at" => null
        ],
        "taxes" => [],
        "cash_register" => [
            "id" => "ca87c795-23fc-48da-b9d7-89957f2a0cd3",
        ],
        "cashier" => [
            "id" => "aac3863c-e30b-48c7-9041-7680103d00a8",
        ]
    ];

    public static array $shift_status_closing = [
        "id" => "8d4471ff-726c-4ec7-bfda-73f6a048c6a2",
        "serial" => 4,
        "status" => "CLOSING",
        "z_report" => null,
        "opened_at" => '2022-02-05T06:00:04.344880+00:00',
        "closed_at" => null,
        "initial_transaction" => [],
        "closing_transaction" => null,
        "created_at" => "2022-02-05T06:00:07.605275+00:00",
        "updated_at" => "2022-02-05T08:00:04.823865+00:00",
        "balance" => [
            "initial" => 0,
            "balance" => 0,
            "cash_sales" => 0,
            "card_sales" => 0,
            "cash_returns" => 0,
            "card_returns" => 0,
            "service_in" => 0,
            "service_out" => 0,
            "updated_at" => null
        ],
        "taxes" => [],
        "cash_register" => [
            "id" => "ca87c795-23fc-48da-b9d7-89957f2a0cd3",
        ],
        "cashier" => [
            "id" => "aac3863c-e30b-48c7-9041-7680103d00a8",
        ]
    ];

    public static array $shift_status_closed = [
        "id" => "8d4471ff-726c-4ec7-bfda-73f6a048c6a2",
        "serial" => 4,
        "status" => "CLOSED",
        "z_report" => [
            "id" => "d71fb649-e270-4d50-8fc8-610ea7259a01",
            "serial" => 3,
            "is_z_report" => true,
            "payments" => [
                [
                    "id" => "2ec69c27-001f-4529-91da-32e1205a3359",
                    "code" => null,
                    "type" => "CASHLESS",
                    "label" => "Картка",
                    "sell_sum" => 3434457,
                    "return_sum" => 0,
                    "service_in" => 0,
                    "service_out" => 0
                ]
            ],
            "taxes" => [
                [
                    "id" => "32c35332-0cab-48bd-8602-cc90084ae5c6",
                    "code" => 8,
                    "label" => "Без ПДВ",
                    "symbol" => "З",
                    "rate" => 0.0,
                    "sell_sum" => 0,
                    "return_sum" => 0,
                    "sales_turnover" => 0,
                    "returns_turnover" => 0,
                    "created_at" => "2022-01-11T16:56:09+00:00",
                    "setup_date" => "2022-01-11T16:56:09+00:00"
                ]
            ],
            "sell_receipts_count" => 10,
            "return_receipts_count" => 0,
            "transfers_count" => 0,
            "transfers_sum" => 0,
            "balance" => 0,
            "initial" => 0,
            "created_at" => "2022-02-04T19:50:03.030983+00:00",
            "updated_at" => null
        ],
        "opened_at" => '2022-02-05T06:00:04.344880+00:00',
        "closed_at" => "2022-02-05T19:50:02.954032+00:00",
        "initial_transaction" => [],
        "closing_transaction" => null,
        "created_at" => "2022-02-05T06:00:07.605275+00:00",
        "updated_at" => "2022-02-05T08:00:04.823865+00:00",
        "balance" => [
            "initial" => 0,
            "balance" => 0,
            "cash_sales" => 0,
            "card_sales" => 0,
            "cash_returns" => 0,
            "card_returns" => 0,
            "service_in" => 0,
            "service_out" => 0,
            "updated_at" => null
        ],
        "taxes" => [],
        "cash_register" => [
            "id" => "ca87c795-23fc-48da-b9d7-89957f2a0cd3",
        ],
        "cashier" => [
            "id" => "aac3863c-e30b-48c7-9041-7680103d00a8",
        ]
    ];

    public static array $receipt_sell_created = [
        "id" => "7f28b06b-e81d-4f1d-9b90-406df1279fdf",
        "type" => "SELL",
        "transaction" => [],
        "serial" => 9,
        "status" => "CREATED",
        "goods" => [
            [
                "good" => [
                    "code" => "123",
                    "barcode" => null,
                    "name" => "Product 1",
                    "excise_barcodes" => null,
                    "header" => null,
                    "footer" => null,
                    "uktzed" => null,
                    "price" => 5798900
                ],
                "good_id" => null,
                "sum" => 5798900,
                "quantity" => 1000,
                "is_return" => false,
                "taxes" => [],
                "discounts" => []
            ]
        ],
        "payments" => [
            [
                "type" => "CASHLESS",
                "pawnshop_is_return" => null,
                "code" => null,
                "value" => 5798900,
                "label" => "Credit cart",
                "card_mask" => null,
                "bank_name" => null,
                "auth_code" => null,
                "rrn" => null,
                "payment_system" => null,
                "owner_name" => null,
                "terminal" => null,
                "acquiring" => null,
                "acquirer_and_seller" => null,
                "receipt_no" => null,
                "signature_required" => null
            ]
        ],
        "total_sum" => 5798900,
        "sum" => 5798900,
        "total_payment" => 5798900,
        "total_rest" => 0,
        "rest" => 0,
        "fiscal_code" => null,
        "fiscal_date" => null,
        "delivered_at" => null,
        "created_at" => "2022-01-19T12:52:32.085844+00:00",
        "updated_at" => null,
        "taxes" => [],
        "discounts" => [],
        "order_id" => null,
        "header" => null,
        "footer" => null,
        "barcode" => null,
        "is_created_offline" => false,
        "is_sent_dps" => false,
        "sent_dps_at" => null,
        "tax_url" => null,
        "related_receipt_id" => null,
        "technical_return" => false,
        "currency_exchange" => null,
        "shift" => [
            "id" => "dd54a0d7-3761-4ad3-b71d-18d33f3882c1",
            "serial" => 4,
            "status" => "OPENED",
            "z_report" => null,
            "opened_at" => "2022-01-19T12:39:19.890377+00:00",
            "closed_at" => null,
            "initial_transaction" => [],
            "closing_transaction" => null,
            "created_at" => "2022-01-19T12:39:19.890377+00:00",
            "updated_at" => "2022-01-19T12:39:20.271388+00:00",
            "balance" => [
                "initial" => 0,
                "balance" => 0,
                "cash_sales" => 0,
                "card_sales" => 0,
                "cash_returns" => 0,
                "card_returns" => 0,
                "service_in" => 0,
                "service_out" => 0,
                "updated_at" => null
            ],
            "taxes" => [],
            "cash_register" => [
                "id" => "63a02d9b-08af-4fb8-80bb-5bc1e3571e13",
                "fiscal_number" => "TEST442645",
                "active" => true,
                "created_at" => "2022-01-11T16:56:09+00:00",
                "updated_at" => "2022-01-11T16:56:09+00:00"
            ],
            "cashier" => [
                "id" => "d8acda9b-f93e-4865-bd4c-3ece40daaf0b",
                "full_name" => "cashier full name",
                "nin" => "000000000",
                "key_id" => "test_key_id",
                "signature_type" => "TEST",
                "permissions" => null,
                "created_at" => "2022-01-11T16:56:09+00:00",
                "updated_at" => "2022-01-12T15:34:53+00:00",
                "certificate_end" => null,
                "blocked" => null
            ],
        ],
        "control_number" => null
    ];

    public static array $receipt_sell_donne = [
        "id" => "7f28b06b-e81d-4f1d-9b90-406df1279fdf",
        "type" => "SELL",
        "transaction" => [],
        "serial" => 9,
        "status" => "DONE",
        "goods" => [
            [
                "good" => [
                    "code" => "123",
                    "barcode" => null,
                    "name" => "Product 1",
                    "excise_barcodes" => null,
                    "header" => null,
                    "footer" => null,
                    "uktzed" => null,
                    "price" => 5798900
                ],
                "good_id" => null,
                "sum" => 5798900,
                "quantity" => 1000,
                "is_return" => false,
                "taxes" => [],
                "discounts" => []
            ]
        ],
        "payments" => [
            [
                "type" => "CASHLESS",
                "pawnshop_is_return" => null,
                "code" => null,
                "value" => 5798900,
                "label" => "Credit cart",
                "card_mask" => null,
                "bank_name" => null,
                "auth_code" => null,
                "rrn" => null,
                "payment_system" => null,
                "owner_name" => null,
                "terminal" => null,
                "acquiring" => null,
                "acquirer_and_seller" => null,
                "receipt_no" => null,
                "signature_required" => null
            ]
        ],
        "total_sum" => 5798900,
        "sum" => 5798900,
        "total_payment" => 5798900,
        "total_rest" => 0,
        "rest" => 0,
        "fiscal_code" => "TEST-NHxviw",
        "fiscal_date" => "2022-01-19T12:52:32.085844+00:00",
        "delivered_at" => "2022-01-19T12:52:32.926819+00:00",
        "created_at" => "2022-01-19T12:52:32.085844+00:00",
        "updated_at" => "2022-01-19T12:52:32.926819+00:00",
        "taxes" => [],
        "discounts" => [],
        "order_id" => null,
        "header" => null,
        "footer" => null,
        "barcode" => null,
        "is_created_offline" => false,
        "is_sent_dps" => false,
        "sent_dps_at" => null,
        "tax_url" => "https:\/\/cabinet.tax.gov.ua\/cashregs\/check?id=TEST-NHxviw&date=20220119",
        "related_receipt_id" => null,
        "technical_return" => false,
        "currency_exchange" => null,
        "shift" => [
            "id" => "dd54a0d7-3761-4ad3-b71d-18d33f3882c1",
            "serial" => 4,
            "status" => "OPENED",
            "z_report" => null,
            "opened_at" => "2022-01-19T12:39:19.890377+00:00",
            "closed_at" => null,
            "initial_transaction" => [],
            "closing_transaction" => null,
            "created_at" => "2022-01-19T12:39:19.890377+00:00",
            "updated_at" => "2022-01-19T12:39:20.271388+00:00",
            "balance" => [
                "initial" => 0,
                "balance" => 0,
                "cash_sales" => 0,
                "card_sales" => 0,
                "cash_returns" => 0,
                "card_returns" => 0,
                "service_in" => 0,
                "service_out" => 0,
                "updated_at" => null
            ],
            "taxes" => [],
            "cash_register" => [
                "id" => "63a02d9b-08af-4fb8-80bb-5bc1e3571e13",
                "fiscal_number" => "TEST442645",
                "active" => true,
                "created_at" => "2022-01-11T16:56:09+00:00",
                "updated_at" => "2022-01-11T16:56:09+00:00"
            ],
            "cashier" => [
                "id" => "d8acda9b-f93e-4865-bd4c-3ece40daaf0b",
                "full_name" => "cashier full name",
                "nin" => "000000000",
                "key_id" => "test_key_id",
                "signature_type" => "TEST",
                "permissions" => null,
                "created_at" => "2022-01-11T16:56:09+00:00",
                "updated_at" => "2022-01-12T15:34:53+00:00",
                "certificate_end" => null,
                "blocked" => null
            ],
        ],
        "control_number" => null
    ];

    public static array $receipt_return_created = [
        "id" => "b7683773-fd87-4fb1-9a36-55450829548a",
        "type" => "RETURN",
        "transaction" => [
            "id" => "d99a5235-dcfe-4f7f-8a30-cedf8b3bdaf0",
            "type" => "RECEIPT",
            "serial" => 132,
            "status" => "PENDING",
            "request_signed_at" => null,
            "request_received_at" => null,
            "response_status" => null,
            "response_error_message" => null,
            "response_id" => null,
            "offline_id" => null,
            "created_at" => "2022-04-30T12:04:36.290573+00:00",
            "updated_at" => "2022-04-30T12:04:36.290573+00:00",
            "previous_hash" => "cc9b9ecbf3840c8b913e521b2886dee587bf7e5a30cbbfbe18c18b45d5bf5d50"
        ],
        "serial" => 93,
        "status" => "CREATED",
        "goods" => [
            [
                "good" => [
                    "code" => "3",
                    "barcode" => null,
                    "name" => "\u0422\u0435\u0441\u0442\u043e\u0432\u0438\u0439 \u0442\u043e\u0432\u0430\u0440 1",
                    "excise_barcodes" => null,
                    "header" => null,
                    "footer" => null,
                    "uktzed" => null,
                    "price" => 100
                ],
                "good_id" => null,
                "sum" => 100,
                "quantity" => 1000,
                "is_return" => true,
                "taxes" => [],
                "discounts" => []
            ],
            [
                "good" => [
                    "code" => "4",
                    "barcode" => null,
                    "name" => "\u0422\u0435\u0441\u0442\u043e\u0432\u0438\u0439 \u0442\u043e\u0432\u0430\u0440 2",
                    "excise_barcodes" => null,
                    "header" => null,
                    "footer" => null,
                    "uktzed" => null,
                    "price" => 100
                ],
                "good_id" => null,
                "sum" => 100,
                "quantity" => 1000,
                "is_return" => true,
                "taxes" => [],
                "discounts" => []
            ]
        ],
        "payments" => [
            [
                "type" => "CASHLESS",
                "pawnshop_is_return" => null,
                "code" => null,
                "value" => 200,
                "label" => "\u041a\u0430\u0440\u0442\u043a\u0430",
                "card_mask" => null,
                "bank_name" => null,
                "auth_code" => null,
                "rrn" => null,
                "payment_system" => null,
                "owner_name" => null,
                "terminal" => null,
                "acquiring" => null,
                "acquirer_and_seller" => null,
                "receipt_no" => null,
                "signature_required" => null
            ]
        ],
        "total_sum" => 200,
        "sum" => 200,
        "total_payment" => 200,
        "total_rest" => 0,
        "rest" => 0,
        "fiscal_code" => null,
        "fiscal_date" => null,
        "delivered_at" => null,
        "created_at" => "2022-01-19T12:52:32.085844+00:00",
        "updated_at" => null,
        "taxes" => [],
        "discounts" => [],
        "order_id" => null,
        "header" => null,
        "footer" => null,
        "barcode" => null,
        "is_created_offline" => false,
        "is_sent_dps" => false,
        "sent_dps_at" => null,
        "tax_url" => null,
        "related_receipt_id" => "b45f42c9-4d00-4c39-ae02-318b002eafa8",
        "technical_return" => false,
        "currency_exchange" => null,
        "shift" => [
            "id" => "b2ba6dfa-7bab-4a18-b937-fc7ca6749920",
            "serial" => 23,
            "status" => "OPENED",
            "z_report" => null,
            "opened_at" => "2022-04-30T07:08:04.541876+00:00",
            "closed_at" => null,
            "initial_transaction" => [
                "id" => "03077c15-1d7d-41ef-949e-284ce945f6f0",
                "type" => "SHIFT_OPEN",
                "serial" => 130,
                "status" => "DONE",
                "request_signed_at" => "2022-04-30T07:08:04.622602+00:00",
                "request_received_at" => "2022-04-30T07:08:04.693830+00:00",
                "response_status" => "OK",
                "response_error_message" => null,
                "response_id" => "TEST-svrhL_",
                "offline_id" => null,
                "created_at" => "2022-04-30T07:08:04.541876+00:00",
                "updated_at" => "2022-04-30T07:08:04.767964+00:00",
                "previous_hash" => "498aa7c7bdcc1805b786ad582747c0fd8d284c4bf6734892c7a090075dac258a"
            ],
            "closing_transaction" => null,
            "created_at" => "2022-04-30T07:08:04.541876+00:00",
            "updated_at" => "2022-04-30T07:08:04.776116+00:00",
            "balance" => [
                "initial" => 0,
                "balance" => 0,
                "cash_sales" => 0,
                "card_sales" => 200,
                "cash_returns" => 0,
                "card_returns" => 0,
                "service_in" => 0,
                "service_out" => 0,
                "updated_at" => "2022-04-30T10:44:36.865653+00:00"
            ],
            "taxes" => [
                [
                    "id" => "89731712-e8f3-4043-ae39-8a850f2dcd5a",
                    "code" => 8,
                    "label" => "\u0411\u0435\u0437 \u041f\u0414\u0412",
                    "symbol" => "\u0417",
                    "rate" => 0,
                    "extra_rate" => null,
                    "included" => true,
                    "created_at" => "2022-01-11T16:56:09+00:00",
                    "updated_at" => null,
                    "sales" => 0,
                    "returns" => 0,
                    "sales_turnover" => 0,
                    "returns_turnover" => 0
                ]
            ],
            "cash_register" => [
                "id" => "63a02d9b-08af-4fb8-80bb-5bc1e3571e13",
                "fiscal_number" => "TEST442645",
                "active" => true,
                "created_at" => "2022-01-11T16:56:09+00:00",
                "updated_at" => "2022-04-27T21:50:02+00:00"
            ],
            "cashier" => [
                "id" => "d8acda9b-f93e-4865-bd4c-3ece40daaf0b",
                "full_name" => "\u0422\u0435\u0441\u0442\u043e\u0432\u0438\u0439 \u043a\u0430\u0441\u0438\u0440",
                "nin" => "000000000",
                "key_id" => "test_yIxSwHZGhYOpGUnC",
                "signature_type" => "TEST",
                "permissions" => null,
                "created_at" => "2022-01-11T16:56:09+00:00",
                "updated_at" => "2022-01-12T15:34:53+00:00",
                "certificate_end" => null,
                "blocked" => null
            ]
        ],
        "control_number" => null
    ];

    public static array $receipt_return_done = [
        "id" => "dd54a0d7-3761-4ad3-b71d-18d33f3882c1",
        "type" => "RETURN",
        "transaction" => [],
        "serial" => 93,
        "status" => "DONE",
        "goods" => [
            [
                "good" => [
                    "code" => "123",
                    "barcode" => null,
                    "name" => "Product 1",
                    "excise_barcodes" => null,
                    "header" => null,
                    "footer" => null,
                    "uktzed" => null,
                    "price" => 200
                ],
                "good_id" => null,
                "sum" => 200,
                "quantity" => 1000,
                "is_return" => false,
                "taxes" => [],
                "discounts" => []
            ]
        ],
        "payments" => [
            [
                "type" => "CASHLESS",
                "pawnshop_is_return" => null,
                "code" => null,
                "value" => 200,
                "label" => "\u041a\u0430\u0440\u0442\u043a\u0430",
                "card_mask" => null,
                "bank_name" => null,
                "auth_code" => null,
                "rrn" => null,
                "payment_system" => null,
                "owner_name" => null,
                "terminal" => null,
                "acquiring" => null,
                "acquirer_and_seller" => null,
                "receipt_no" => null,
                "signature_required" => null
            ]
        ],
        "total_sum" => 200,
        "total_payment" => 200,
        "total_rest" => 0,
        "fiscal_code" => "TEST-NHxviy",
        "fiscal_date" => "2022-01-19T12:52:32.085844+00:00",
        "delivered_at" => "2022-01-19T12:52:32.926819+00:00",
        "created_at" => "2022-01-19T12:52:32.085844+00:00",
        "updated_at" => "2022-01-19T12:52:32.926819+00:00",
        "taxes" => [],
        "discounts" => [],
        "order_id" => null,
        "header" => null,
        "footer" => null,
        "barcode" => null,
        "is_created_offline" => false,
        "is_sent_dps" => false,
        "sent_dps_at" => null,
        "tax_url" => "https:\/\/cabinet.tax.gov.ua\/cashregs\/check?id=TEST-NHxviw&date=20220119",
        "related_receipt_id" => "7f28b06b-e81d-4f1d-9b90-406df1279fdf",
        "technical_return" => false,
        "currency_exchange" => null,
        "shift" => [
            "id" => "dd54a0d7-3761-4ad3-b71d-18d33f3882c1",
            "serial" => 4,
            "status" => "OPENED",
            "z_report" => null,
            "opened_at" => "2022-01-19T12:39:19.890377+00:00",
            "closed_at" => null,
            "initial_transaction" => [
                "id" => "cd49890c-d3ac-43a8-a871-1932824792d4",
                "type" => "SHIFT_OPEN",
                "serial" => 14,
                "status" => "DONE",
                "request_signed_at" => "2022-01-19T12:39:20.005410+00:00",
                "request_received_at" => "2022-01-19T12:39:20.175557+00:00",
                "response_status" => "OK",
                "response_error_message" => null,
                "response_id" => "TEST-ZXa4A5",
                "offline_id" => null,
                "created_at" => "2022-01-19T12:39:19.890377+00:00",
                "updated_at" => "2022-01-19T12:39:20.262095+00:00",
                "previous_hash" => "7f1a8401315acfec5951ff05721360b82540b19afb6a4e022b7cba779d3db571"
            ],
            "closing_transaction" => null,
            "created_at" => "2022-01-19T12:39:19.890377+00:00",
            "updated_at" => "2022-01-19T12:39:20.271388+00:00",
            "balance" => [
                "initial" => 0,
                "balance" => 0,
                "cash_sales" => 0,
                "card_sales" => 5798900,
                "cash_returns" => 0,
                "card_returns" => 5798900,
                "service_in" => 0,
                "service_out" => 0,
                "updated_at" => "2022-01-19T15:13:59.915427+00:00"
            ],
            "taxes" => [
                [
                    "id" => "353f79ee-20f5-4020-8411-a7fb8897814e",
                    "code" => 8,
                    "label" => "\u0411\u0435\u0437 \u041f\u0414\u0412",
                    "symbol" => "\u0417",
                    "rate" => 0,
                    "extra_rate" => null,
                    "included" => true,
                    "created_at" => "2022-01-11T16:56:09+00:00",
                    "updated_at" => null,
                    "sales" => 0,
                    "returns" => 0,
                    "sales_turnover" => 0,
                    "returns_turnover" => 0
                ]
            ],
            "cash_register" => [
                "id" => "63a02d9b-08af-4fb8-80bb-5bc1e3571e13",
                "fiscal_number" => "TEST442645",
                "active" => true,
                "created_at" => "2022-01-11T16:56:09+00:00",
                "updated_at" => "2022-01-11T16:56:09+00:00"
            ],
            "cashier" => [
                "id" => "d8acda9b-f93e-4865-bd4c-3ece40daaf0b",
                "full_name" => "\u0422\u0435\u0441\u0442\u043e\u0432\u0438\u0439 \u043a\u0430\u0441\u0438\u0440",
                "nin" => "000000000",
                "key_id" => "test_key_id",
                "signature_type" => "TEST",
                "permissions" => null,
                "created_at" => "2022-01-11T16:56:09+00:00",
                "updated_at" => "2022-01-12T15:34:53+00:00",
                "certificate_end" => null,
                "blocked" => null
            ]
        ],
        "control_number" => "1107"
    ];

    public static array $invalid_receipt_validation = [
        "detail" => [
            [
                "loc" => [
                    "body",
                    "payload",
                    "goods",
                    0,
                    "good",
                    "price"
                ],
                "msg" => "ensure this value is greater than 0",
                "type" => "value_error.number.not_gt",
                "ctx" => [
                    "limit_value" => 0
                ]
            ]
        ],
        "message" => "Validation error"
    ];

    public static array $x_report = [
        "id" => "e6d97699-fe80-4c31-9179-6af8368f4cdd",
        "serial" => 1,
        "is_z_report" => false,
        "payments" => [],
        "taxes" => [
            [
                "id" => "afb174b2-269a-43a5-bb18-f58f15718a1c",
                "code" => 8,
                "label" => "Без ПДВ",
                "symbol" => "З",
                "rate" => 0.0,
                "sell_sum" => 0,
                "return_sum" => 0,
                "sales_turnover" => 0,
                "returns_turnover" => 0,
                "created_at" => "2022-01-11T16:56:09+00:00",
                "setup_date" => "2022-01-11T16:56:09+00:00"
            ]
        ],
        "sell_receipts_count" => 0,
        "return_receipts_count" => 0,
        "transfers_count" => 0,
        "transfers_sum" => 0,
        "balance" => 0,
        "initial" => 0,
        "created_at" => "2022-04-30T08:58:56.201642+00:00",
        "updated_at" => null
    ];

    public static string $x_report_as_text = '========================================== ТЕСТОВИЙ ЗВІТ ========================================== ТЕСТ УКРАЇНА, М.КИЇВ ГОЛОСІЇВСЬКИЙ Р-Н, Тестова, 41а ІД 3107819397 Z-звіт №1 за 01.05.2022 ========================================== Зміна відкрита 01.05.2022 14:18:53 Останній фіскальний чек - Валюта звіту ГРН --------------- Реалізація --------------- Одержано по формах оплати Загальний оборот 0.00 Кількість чеків 0 --------------- Повернення --------------- Видано по формам оплати Загальний оборот 0.00 Кількість чеків 0 --------- Готівкові кошти в касі --------- Початковий залишок 0.00 Службове внесення 0.00 Службове вилучення 0.00 Кінцевий залишок 0.00 ---------------- Виручка ----------------- Готівкою 0.00 Безготівкова 0.00 Всього 0.00 ========================================== ФН ПРРО TEST ТЕСТОВИЙ ДОКУМЕНТ CHECKBOX 01.05.2022 21:50:02 ========================================== ТЕСТОВИЙ ЗВІТ ==========================================';

    public static array $cash_register_info = [
        "id" => "63a02d9b-08af-4fb8-80bb-5bc1e3571e13",
        "fiscal_number" => "TEST",
        "created_at" => "2022-01-01T16:56:09+00:00",
        "updated_at" => "2022-01-01T21:50:02+00:00",
        "offline_mode" => "",
        "stay_offline" => "",
        "address" => "УКРАЇНА, М.КИЇВ ГОЛОСІЇВСЬКИЙ Р-Н, Тестова, 41а",
        "title" => "",
        "has_shift" => "",
        "documents_state" => [
            "last_receipt_code" => "1",
            "last_report_code" => "1",
            "last_z_report_code" => "1",],
    ];

    public static array $cashier_shift = [
        "id" => "8c29655e-7169-4a4e-b8e9-a29b7949f944",
        "serial" => 28,
        "status" => "OPENED",
        "z_report" => null,
        "opened_at" => "2022-05-18T19:26:14.591277+00:00",
        "closed_at" => null,
        "initial_transaction" => [],
        "closing_transaction" => null,
        "created_at" => "2022-05-18T19:26:14.591277+00:00",
        "updated_at" => "2022-05-18T19:26:14.833404+00:00",
        "balance" => [
            "initial" => 100,
            "balance" => 100,
            "cash_sales" => 0,
            "card_sales" => 0,
            "cash_returns" => 0,
            "card_returns" => 0,
            "service_in" => 0,
            "service_out" => 0,
            "updated_at" => null,
        ],
        "taxes" => [],
        "cash_register" => [
            "id" => "63a02d9b-08af-4fb8-80bb-5bc1e3571e13",
            "fiscal_number" => "TEST442645",
            "active" => true,
            "created_at" => "2022-01-11T16:56:09+00:00",
            "updated_at" => "2022-05-13T21:50:02+00:00",
        ]
    ];
}