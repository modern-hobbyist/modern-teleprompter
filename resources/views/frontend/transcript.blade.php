@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@push('after-styles')
    <style>
        body{
            background-color: black;
        }
        .transcript{
            height: 100%;
            width: 100%;
            left: 0;
            top: 0;
            background-color: black;
            color: white;
            transform: rotate(180deg);
        }

        .home-button{
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 3;
        }

    </style>
@endpush

@section('content')
    <button class="btn btn-dark home-button">
        <a href="{{route('frontend.index')}}" class="text-decoration-none">
            <i class="fa fa-arrow-left"></i>
            Home
        </a>
    </button>
    <div class="transcript">
        <h1 class="font-5xl" style="color: white">{{ $contents }}</h1>
    </div>
@endsection
