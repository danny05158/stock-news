<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

require __DIR__ . '/../../../vendor/autoload.php';
use PolygonIO\rest\Rest;

class DashboardController extends Controller
{

    public function __construct (){
        $this->rest = null;
        $this->ticker_simbol = null;
        $this->params = ['perpage' => 5, 'page' => 1];
        $this->response = [];
    }

    public function load_api_key(){
        $this->rest = new Rest('0vuALpjDqJ_XmYXC8mU_pw92V9D879OZ');
    }

    public function index(){
         return view('dashboard');
    }

    public function get_website(Request $request){

        $data = [];
        $this->load_api_key();

        //get form input
        $this->ticker_simbol = $request->input('website');

        try {
            $res = $this->rest->reference->tickerNews->get($this->ticker_simbol, $this->params);

        } catch (\Throwable $th) {
            echo 'error';
        }
        $this->get_open_close();


        //if ticker is not right redirect home
        if(empty($res)){
            return redirect('/');
        }

        foreach($res as $news_data){

            $obj = new \stdClass;

            $obj->title = $news_data['title'];
            $obj->url = $news_data['url'];
            $obj->summary = $news_data['summary'];
            $obj->image = $news_data['image'];

            $timestamp = $news_data['timestamp'];
            $data[$timestamp] = $obj;
        }

        $this->response['ticker_simbol'] = $this->ticker_simbol;
        $this->response['news'] = $data;
        return view('news', $this->response);

    }

    public function get_open_close(){

        $open_close = [];
        $ticker = trim(strtoupper($this->ticker_simbol));
        $tdate = date("Y-m-d", strtotime("-2 day"));

        try {
            $open_close = $this->rest->stocks->dailyOpenClose->get($ticker, $tdate);
        } catch (\Throwable $th) {
            echo 'error';
        }

        $obj = new \stdClass;
        $obj->open = $open_close['open'];
        $obj->close = $open_close['close'];

        $this->response['daily_open_close'] = $obj;
    }

}
