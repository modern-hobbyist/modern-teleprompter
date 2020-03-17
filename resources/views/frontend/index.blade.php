@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@push('after-styles')
    {{style('//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css')}}
    <style>
        #reloadButton{
            position: fixed;
            top: 25px;
            left: 25px;
        }
        
        #ipaddress{
            position: fixed;
            bottom: 25px;
            left: 25px;
            z-index: 3;
        }
    </style>
@endpush

@push('after-scripts')
    {{script('//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js')}}
    <script>
        $(document).ready( function () {
            $('#transcripts').DataTable();
        } );

        $('#reloadButton').on('click', function(){
           location.reload();
        });
    </script>
@endpush

@section('content')
    <button class="btn btn-primary" id="reloadButton">Reload</button>
    <button class="btn btn-dark" id="ipaddress">{{$ip}}</button>
    <div class="row m-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Transcripts
                </div>
                <div class="card-body">
                    <table id="transcripts" class="display" style="width:100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transcripts as $transcript)
                            <tr>
                                <td>{{$transcript->title}}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{route('frontend.transcript', $transcript)}}">
                                            <button type="button" class="btn btn-success">Play</button>
                                        </a>
                                        <a href="{{route('frontend.transcript.delete', $transcript)}}">
                                            <button type="button" class="btn btn-danger">Delete</button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 text-center m-auto">
            <h1>OR</h1>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Upload
                </div>
                <div class="card-body">
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
                </div>
            </div>
        </div>
    </div>
@endsection
