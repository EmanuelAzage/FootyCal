<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Auth;

class LoginController extends Controller
{
    public function index(){
      return view('login');
    }

    public function login(){
      $local_server = "http://localhost:8080/";
      $heroku_server = "https://footycal-server.herokuapp.com/";

      $loginWasSuccessful = Auth::attempt([
        'email'=> request('email'),
        'password'=> request('password')
      ]);

      if ($loginWasSuccessful){
        // get new access token
        $user = Auth::user();
        $client = new Client();
        $res = $client->request('POST', $heroku_server . 'auth/token', [
          'json' => ['id' => $user->id, 'email' => $user->email, 'password' =>$user->password]
        ]);

        $token = json_decode($res->getBody()->getContents())->token;

        session(['token' => $token]);

        // get user's teams
        $headers = [
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer ' . session('token'),
        ];
        $client = new Client([
            'headers' => $headers
        ]);
        $res = $client->request('GET', $heroku_server . 'api/user', [
          'json' => ['id' => $user->id, 'email' => $user->email, 'password' =>$user->password]
        ]);

        $myteams = json_decode($res->getBody()->getContents())->teams;

        session(['myteams' => $myteams]);

        return redirect('/profile');
      } else {
        return redirect('/login');
      }

    }

    public function logout(){
      Auth::logout();
      return redirect('/login');
    }
}
