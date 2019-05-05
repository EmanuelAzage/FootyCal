<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

use Auth;

class UpcomingMatchesController extends Controller
{
  public function index(){

    $local_server = "http://localhost:8080/";
    $heroku_server = "https://footycal-server.herokuapp.com/";

    // get upcoming matches for this user
    $user = Auth::user();
    $headers = [
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . session('token'),
    ];
    $client = new Client([
        'headers' => $headers
    ]);
    $res = $client->request('GET', $heroku_server . 'api/upcoming_matches/' . $user->id);

    $matches = json_decode($res->getBody()->getContents());

    return view('upcoming', [
      'user' => Auth::user(),
      'matches' => $matches
    ]);
  }

}
