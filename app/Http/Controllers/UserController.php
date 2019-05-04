<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

use Auth;

class UserController extends Controller
{
  var $local_server = "http://localhost:8080/";
  var $heroku_server = "https://footycal-server.herokuapp.com/";

  public function index(){

    // 1. get all teams
    $user = Auth::user();
    $headers = [
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . session('token'),
    ];
    $client = new Client([
        'headers' => $headers
    ]);
    $res = $client->request('GET', $heroku_server . 'api/allteams', [
      'json' => ['id' => $user->id, 'email' => $user->email, 'password' =>$user->password]
    ]);

    $teams = json_decode($res->getBody()->getContents());

    session(['allteams' => $teams]);

    if (!session('myteams')) {
      session(['myteams' => []]);
    }

    return view('user/profile', [
      'user' => Auth::user(),
      'allteams' => $teams,
      'myteams' => session('myteams')
    ]);
  }

  public function updateTeams(Request $request){
    // update teams list
    $selected_teams = $request->selected_teams;

    if ($selected_teams) {
      $myteams = [];
      foreach($selected_teams as $id){
        foreach(session('allteams') as $team){

          if (intval($id) === $team->id){
            array_push($myteams, $team);
          }
        }
      }
      session(['myteams' => $myteams]);

      // update node server
      $user = Auth::user();
      $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . session('token'),
      ];
      $client = new Client([
          'headers' => $headers
      ]);
      $res = $client->request('PATCH', $heroku_server .  'api/update_user_teams', [
        'json' => ['id' => $user->id, 'email' => $user->email, 'password' =>$user->password, 'teams' => $myteams]
      ]);

    } else {
      session(['myteams' => []]);
    }

    return redirect('/profile');
  }

  public function removeTeams(Request $request){
    // reset teams list
    session(['myteams' => []]);

    // update node server
    $user = Auth::user();
    $headers = [
      'Content-Type' => 'application/json',
      'Authorization' => 'Bearer ' . session('token'),
    ];
    $client = new Client([
        'headers' => $headers
    ]);
    $client->request('delete', $heroku_server . 'api/delete_teams', [
      'json' => ['id' => $user->id, 'email' => $user->email, 'password' =>$user->password]
    ]);

    return redirect('/profile');
  }
}
