@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')


    @if (Auth::user()->role === 'admin')
        @include('pages.dashboard.admin.index')
    @elseif (Auth::user()->role === 'tu')
        @include('pages.dashboard.tu.index')
    @elseif (Auth::user()->role === 'pegawai')
        @include('pages.dashboard.pegawai.index')
    @endif


@endsection
