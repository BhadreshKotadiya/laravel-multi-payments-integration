@extends('layouts.master')

@section('title', 'User Profile')

@section('content')
    <h3 class="mb-5 mt-5 font-weight-bold">User Profile</h3>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ Auth::user()->name }}</h5>
            <p class="card-text">Email: {{ Auth::user()->email }}</p>
            <!-- Add more user details here -->
        </div>
    </div>
@endsection
