@extends('layouts.auth')    

@section('title', 'Login Admin')

@section('content')
    <form method="POST" action="{{route('login.admin.post')}}" class="needs-validation" novalidate="">
        @csrf
        <div class="form-group">
            <label for="email">Username/Email</label>
            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" tabindex="1">
            @error('username')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <div class="d-block">
                <label for="password" class="control-label">Password</label>
            </div>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" tabindex="2">
            @error('password')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
            @enderror
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                Login
            </button>
        </div>
    </form>
@endsection