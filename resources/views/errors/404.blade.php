@extends('errors.layout')

@section('title', '404 - Halaman Tidak Ditemukan')
@section('code', 'Error 404')
@section('heading', 'Halaman Tidak Ditemukan')
@section('message', 'Mohon maaf, halaman yang Anda tuju tidak ditemukan atau telah dipindahkan ke tautan lain.')

@section('icon')
<svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
@endsection
