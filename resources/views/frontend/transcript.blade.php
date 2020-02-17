@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@push('after-styles')
    <style>
        body{
            background-color: black;
            color: white;
            line-height: 300%;
        }
        .transcript{
            padding-top: 100vh;
            padding-bottom: 100vh;
            font-size: 50px;
            line-height: 200%;
            height: 100%;
            width: 100%;
            left: 0;
            top: 0;
            background-color: black;
            color: white;
            transform: scaleY(-1);
        }

        .controls{
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 3;
        }
    </style>
@endpush

@push('after-scripts')
    <script>
        var paused = false;
        $(document).ready(function(){
            $('html, body').animate({scrollTop:$(document).height()}, 'slow');
            $('html, body').animate({
                scrollTop: $(".transcript").offset().top
            }, 310/$('#speed').val()*1000, "linear");
        });

        function updateSpeed(){
            $('html, body').clearQueue();
            $('html, body').stop();
            $('html, body').animate({
                scrollTop: $(".transcript").offset().top
            }, 310/$('#speed').val()*1000, "linear" );
        }

        $('#speed').on('change', function(){
            updateSpeed();
        });

        function restart(){
            $('html, body').clearQueue();
            $('html, body').stop();
            $('html, body').animate({scrollTop:$(document).height()}, 'slow', function(){
                $('html, body').animate({
                    scrollTop: $(".transcript").offset().top
                }, 310/$('#speed').val()*1000, "linear" );
            });
        }

        function speedUp(){
            $('#speed').val( function(i, oldval) {
                return parseInt( oldval, 10) + 1;
            });
            $('html, body').animate({
                scrollTop: $(".transcript").offset().top
            }, 310/$('#speed').val()*1000, "linear" );
            updateSpeed();
        }

        function speedDown(){
            $('#speed').val( function(i, oldval) {
                return parseInt( oldval, 10) - 1;
            });
            updateSpeed();
        }

        function goToEnd(){
            $('html, body').clearQueue();
            $('html, body').stop();
            $('html, body').animate({scrollTop:$(".transcript").offset().top}, 'slow');
        }

        function pause(){
            $('html, body').clearQueue();
            $('html, body').stop();
        }

        function resume(){
            updateSpeed();
        }

        function togglePause(){
            if(paused){
                $('#pause').html('Pause');
                paused = false;
                resume();
            }else{
                $('#pause').html('Play');
                paused = true;
                pause();
            }
        }

        $('#pause').on('click', function(){
           togglePause();
        });

        $('#restart').on('click', function(){
           restart();
        });

        $('#end').on('click', function(){
           goToEnd();
        });

        $("body").keydown(function(e) {
            if(e.keyCode == 13) {
                togglePause();
            }
            else if(e.keyCode == 37) { // left
                restart();
            }
            else if(e.keyCode == 38) { // up
                speedUp();
            }
            else if(e.keyCode == 39) { // right
                goToEnd();
            }
            else if(e.keyCode == 40) { // down
                speedDown();
            }
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="transcript">
            <p>{{ $contents }}</p>
        </div>
    </div>
    <div class="controls">
        <div class="row">
            <div class="col">
                <button class="btn btn-dark home-button">
                    <a href="{{route('frontend.index')}}" class="text-decoration-none">
                        <i class="fa fa-arrow-left"></i>
                        Home
                    </a>
                </button>
            </div>
            <div class="col">
                <div class="form-group row">
                    <label for="speed" class="col m-auto">
                        Speed
                    </label>
                    <input type="number" name="speed" id="speed" value="1" class="form-control btn-dark col" />
                </div>
            </div>
            <div class="col">
                <button name="restart" id="restart" class="btn btn-dark">Restart</button>
            </div>
            <div class="col">
                <button name="end" id="end" class="btn btn-dark">End</button>
            </div>
            <div class="col">
                <button name="pause" id="pause" class="btn btn-dark">Pause</button>
            </div>
        </div>
    </div>

@endsection
