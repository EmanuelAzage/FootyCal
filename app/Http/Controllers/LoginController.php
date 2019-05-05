<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Auth;
use Validator;

class LoginController extends Controller
{
    public function index(){
      return view('login');
    }

    public function login(Request $request){
      $local_server = "http://localhost:8080/";
      $heroku_server = "https://footycal-server.herokuapp.com/";

      // validate input
      $input = $request->all();
      $validation = Validator::make($input, [
        'email' => 'required|min:6',
        'password' => 'required|min:4'
      ]);

      // if validation fails, redirect back to form with odbc_errors
      if ($validation->fails()){
        return redirect('/signup')
          ->withInput()
          ->withErrors($validation);
      }

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
        $res = $client->request('GET', $heroku_server . 'api/user/' . $user->id);

        $json = json_decode($res->getBody()->getContents());

        if(isset($json->teams)){
          session(['myteams' => $json->teams]);
        }else{
          session(['myteams' => []]);
        }


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
