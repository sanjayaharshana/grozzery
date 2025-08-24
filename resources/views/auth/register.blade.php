@extends('layouts.app')

@section('title', 'Register - Grozzoery')
@section('meta_description', 'Create your Grozzoery account')

@section('content')
<div class="container py-8">
    <div class="max-w-md mx-auto">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center">Create Account</h2>
                <p class="text-center text-muted">Join Grozzoery today</p>
            </div>
            
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" required>
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
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary w-full">
                            Create Account
                        </button>
                    </div>
                </form>
                
                <div class="text-center mt-4">
                    <p class="text-sm text-muted">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="text-red-500 hover:text-red-600">Sign in here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
