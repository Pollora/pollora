@extends('layouts.app')

@section('content')
    <div data-pollora-template="404" class="mx-auto max-w-3xl px-6 py-32 text-center lg:px-8">
        <p class="text-7xl font-bold bg-gradient-to-r from-primary-vivid via-primary to-secondary bg-clip-text text-transparent">404</p>
        <h1 class="mt-4 text-2xl font-bold tracking-tight text-foreground">Page not found</h1>
        <p class="mt-4 text-muted">Sorry, the page you're looking for doesn't exist.</p>
        <a href="{{ home_url('/') }}" class="wp-element-button mt-8 inline-flex items-center gap-2 rounded-xl bg-foreground px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:shadow-xl hover:-translate-y-0.5">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
            Back to home
        </a>
    </div>
@endsection