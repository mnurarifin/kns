<?php

namespace App\Libraries;

class Delivery
{
    public function deliveryCost($origin, $destination, $weight, $courier)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => DELIVERY_URL . "cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin={$origin}&originType=subdistrict&destination={$destination}&destinationType=subdistrict&weight={$weight}&courier={$courier}",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: " . DELIVERY_KEY
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        $return = json_decode($response);
        if ($return) {
            if ($return->rajaongkir->status->code == 200) {
                return $return->rajaongkir->results;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }
}
