@extends('layout')

@section('title', 'Profile')

@section('main')
  <h1>Welcome back, your email is {{$user->email}}</h1>
@endsection
