@extends('errors.layout')

@section('title', '403 - Akses Ditolak')
@section('code', 'Error 403')
@section('heading', 'Akses Dibatasi')
@section('message', 'Anda tidak memiliki hak akses atau izin yang memadai untuk membuka halaman ini.')

@section('icon')
<svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
</svg>
@endsection
