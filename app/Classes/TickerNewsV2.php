<?php

namespace App\Classes;

class TickerNewsV2 {

   protected $API_URL = 'https://api.polygon.io';
   protected $api_key;

    public function __construct($params = []) {
      $this->ticker = $params['ticker'];
      $this->tdate = date("Y-m-d");
      $this->api_key = getenv('POLYGON_API_KEY');
    }

    protected function _getApi(){
      return ['apiUrl' => $this->API_URL, 'api_key' => $this->api_key];
    }

    public function buildQuery(){

      $params = [
        'limit' => 8,
        'order' => 'descending',
        'sort' => 'published_utc',
        'ticker' => $this->ticker,
        'published_utc.gte' => $this->tdate,
        'apiKey' => $this->api_key
      ];

      $this->route = $this->API_URL . '/v2/reference/news?' . http_build_query($params);
    }

    public function getNews(){

      $client = new \GuzzleHttp\Client();

      try {

        $res = $client->request('GET', $this->route);
        $contents = $res->getBody()->getContents();

      } catch (\Throwable $th) {
        echo 'could not retrieve news ' . $th->getMessage() . PHP_EOL;
      }
      $cont = json_decode($contents, true);
      return $cont['results'];
    }

  }
