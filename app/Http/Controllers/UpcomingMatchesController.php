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

    // get upcoming matches for this user
    $user = Auth::user();
    $headers = [
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . session('token'),
    ];
    $client = new Client([
        'headers' => $headers
    ]);
    $res = $client->request('GET', 'http://localhost:8080/api/upcoming_matches', [
      'json' => ['id' => $user->id, 'email' => $user->email, 'password' =>$user->password]
    ]);

    $matches = json_decode($res->getBody()->getContents());

    return view('upcoming', [
      'user' => Auth::user(),
      'matches' => $matches
    ]);
  }

}
