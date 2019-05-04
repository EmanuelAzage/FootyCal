<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\User;
use Hash;
use Auth;
use Validator;

class SignUpController extends Controller
{
    public function index()
    {
      return view('signup');
    }

    public function signup(Request $request)
    {

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

      $user = new User();
      $user->email = request('email');
      $user->password = Hash::make(request('password')); // bcrypt encrytion method
      $user->save();

      Auth::login($user);

      // create user on mongo database (post request to node server)
      $client = new Client();

      $client->request('POST', $heroku_server . 'auth/create_user', [
        'json' => ['id' => $user->id, 'email' => $user->email, 'password' =>$user->password]
      ]);

      // get an access token and save it in the session
      $client = new Client();
      $res = $client->request('POST', $heroku_server . 'auth/token', [
        'json' => ['id' => $user->id, 'email' => $user->email, 'password' =>$user->password]
      ]);

      $token = json_decode($res->getBody()->getContents())->token;

      session(['token' => $token]);

      return redirect('/profile');
    }
}
