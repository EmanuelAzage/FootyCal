@extends('layout')

@section('title', 'Upcoming Matches')

@section('main')

<h1> Here are your upcoming matches! </h1>
<br><br>

<table class="table">
  <tr>
    <th>Home</th>
    <th>vs</th>
    <th>Away</th>
    <th>Date</th>
    <th>Time (UTC)</th>
  </tr>
  @forelse($matches as $match)
    <tr>
      <td>
        {{$match->home_team}}
      </td>
      <td>
        vs
      </td>
      <td>
        {{$match->away_team}}
      </td>
      <td>
        {{$match->date}}
      </td>
      <td>
        {{$match->time}}
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="4">There are no scheduled upcoming matches for your teams right now, come back later.</td>
    </tr>
  @endforelse
</table>


@endsection
