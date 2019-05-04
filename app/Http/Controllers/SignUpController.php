<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

use App\User;
use Hash;
use Auth;

class SignUpController extends Controller
{
    public function index()
    {
      return view('signup');
    }

    public function signup()
    {

      $local_server = "http://localhost:8080/";
      $heroku_server = "https://footycal-server.herokuapp.com/";

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
