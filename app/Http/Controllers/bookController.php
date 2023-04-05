<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookModel;

class bookController extends Controller
{
    public function books(Request $request){
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fakerapi.it/api/v1/books?_quantity=100',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $responseArray = json_decode($response,true);
        // dd($responseArray);
        BookModel::insert($responseArray['data']);
        
    }
}
