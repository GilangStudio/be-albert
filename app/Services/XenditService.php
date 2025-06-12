<?php

namespace App\Services;

Class XenditService {
    public static function Request($url, $data=NULL) {
        $headers = [];
        $headers[] = "Content-Type: application/json";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_USERPWD, env('XENDIT_API_KEY'));
        if (isset($data)) {
            $payload = json_encode($data);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);
        return json_decode($result);
    }

    // public function getTransactionById($id) {
    //     $url = "https://api.xendit.co/transactions?reference_id=".$id;
    //     return $this->Request($url);
    // }

    public static function getBalance() {
        $url = "https://api.xendit.co/balance";
        return self::Request($url);
    }
    
    public static function getInvoices() {
        $url = 'https://api.xendit.co/v2/invoices?limit=100';
        return self::Request($url);
    }
    
    public static function getInvoicesByUser($id, $limit = 100) {
        $url = "https://api.xendit.co/v2/invoices?external_id=".$id . "&limit=" . $limit;
        
        return self::Request($url);
    }

    public static function getInvoicesByParameter($parameter) {
        $url = 'https://api.xendit.co/v2/invoices?' . $parameter;

        return self::Request($url);
    }
    
    public static function getInvoiceById($id) {
        $url = "https://api.xendit.co/v2/invoices/".$id;
        return self::Request($url);
    }

    public static function createInvoice($data) {
        $url = "https://api.xendit.co/invoices";

        $body = [
            // 'invoice_duration' => '60',
            'external_id' => $data['external_id'],
            'customer' => $data['customer'],
            'customer_notification_preference' => [
                'invoice_created' => [
                    "whatsapp",
                    "email"
                ],
                'invoice_reminder' => [
                    "whatsapp",
                    "email"
                ],
                'invoice_paid' => [
                    "whatsapp",
                    "email"
                ]
            ],
            // 'items' => $data['items'],
            'amount' => $data['amount'],
            // 'description' => $data['description'],
            // 'locale' => 'id',
            // 'invoice_duration' => $duration,
            // 'success_redirect_url' => $data['success_redirect_url'],
        ];

        if (isset($data['items'])) {
            $body['items'] = $data['items'];
        }

        return self::Request($url, $body);
    }
}