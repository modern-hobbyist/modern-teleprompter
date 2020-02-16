@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')
    <h1 class="font-5xl">{{ $contents }}</h1>
@endsection
