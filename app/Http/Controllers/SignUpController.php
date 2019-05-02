<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
      $user = new User();
      $user->email = request('email');
      $user->password = Hash::make(request('password')); // bcrypt encrytion method
      $user->save();

      Auth::login($user);

      // TODO create user on mongo database (post request to node server)

      return redirect('/profile');
    }
}
