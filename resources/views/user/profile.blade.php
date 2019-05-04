@extends('layout')

@section('title', 'Profile')

@section('main')

<h1> Welcome to FootyCal, update teams below. </h1>
<br><br>
<p1>Command click for multiple selection. Select all teams you want to moniter.</p1>

<form action="/profile" method="post">
  @csrf
  <div class="form-group" >
    <div class="col-md-6">
      <select name="selected_teams[]" id="selected_teams" class="multiselect-ui form-control" multiple="multiple">
        @foreach($allteams as $team )
          <option value="{{$team->id}}">{{$team->name}}</option>
        @endforeach
      </select>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">
      Update
    </button>
  </div>
</form>

<br><br>

<h2> <br>Current Teams </h2>

<table class="table">
  <tr>
    <th>Teams</th>
  </tr>
  @forelse(session('myteams') as $team)
    <tr>
      <td>
        {{$team->name}}
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="4">No teams selected, please select teams above.</td>
    </tr>
  @endforelse
</table>

<h4> <br>Remove All Teams </h4>
<form action="/delete_teams" method="post">
  @csrf
  <div class="form-group" >
    <button type="submit" class="btn btn-danger">
      Remove
    </button>
  </div>
</form>

@endsection
