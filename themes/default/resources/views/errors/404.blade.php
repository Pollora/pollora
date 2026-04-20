@extends('layouts.app')

@section('content')
    <div class="mt-9 text-center">
        <h1 class="text-4xl font-bold tracking-tight text-zinc-800 sm:text-5xl">404</h1>
        <p class="mt-6 text-base text-zinc-600">Page not found.</p>
        <a href="{{ home_url('/') }}" class="mt-6 inline-block text-sm font-medium text-teal-500 hover:text-teal-600">
            &larr; Back to home
        </a>
    </div>
@endsection