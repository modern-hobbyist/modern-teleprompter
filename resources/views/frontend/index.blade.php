@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@section('content')
    <div class="row">
        <div class="col-12 text-center">
            <h1>Transcripts</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @foreach($transcripts as $transcript)
                <div class="row">
                    <a href="{{route('frontend.transcript', $transcript)}}">{{$transcript->title}}</a>
                </div>
            @endforeach
        </div>
    </div>
    <form method="POST" action="{{route('frontend.upload')}}" enctype="multipart/form-data">
        @csrf
        <label for="script_name">Script Name</label>
        <input class="form-control" type="text" name="title" title="script_name" placeholder="Script Name" required>

        <label for="transcript">Transcript</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">Transcript</span>
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="inputGroupFile01" name="transcript" required>
                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
            </div>
        </div>

        <button type="submit" class="form-control">Upload</button>
    </form>
@endsection
