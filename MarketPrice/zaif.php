<?php

$zaifCurrencyPairsUrl = 'https://api.zaif.jp/api/1/currency_pairs/all';
$zaifLastPrice = 'https://api.zaif.jp/api/1/last_price/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $zaifCurrencyPairsUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$jsonString = curl_exec($ch);
curl_close($ch);

$json = json_decode($jsonString,true);
$currencyPairs = [];

foreach ($json as $pairs) {
    if (strpos($pairs['currency_pair'], 'jpy') !== false) {
        $tmp = [
            'title'         =>  $pairs['name'],
            'currency_pair' =>  $pairs['currency_pair'],
            'item_japanese' =>  $pairs['item_japanese']
        ];
        $currencyPairs[] = $tmp;
    }
}

$zaif = [];

$ch = curl_init();

foreach ($currencyPairs as $pairs) {
    $url = $zaifLastPrice.$pairs['currency_pair'];
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $jsonString = curl_exec($ch);
    $json = json_decode($jsonString,true);
    $tmp = [
        'title'         =>  $pairs['title'],
        'item_japanese' =>  $pairs['item_japanese'],
        'last_price'    =>  $json['last_price']
    ];
    $zaif[] = $tmp;
}

curl_close($ch);

var_dump($zaif);
