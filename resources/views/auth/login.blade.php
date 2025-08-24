@extends('layouts.app')

@section('title', 'Login - Grozzoery')
@section('meta_description', 'Login to your Grozzoery account')

@section('content')
<div class="container py-8">
    <div class="max-w-md mx-auto">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center">Welcome Back</h2>
                <p class="text-center text-muted">Sign in to your account</p>
            </div>
            
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="mr-2">
                            <span class="text-sm">Remember me</span>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary w-full">
                            Sign In
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="text-sm text-muted">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="text-red-500 hover:text-red-600">Sign up here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
