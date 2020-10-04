@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.general.home'))

@push('after-styles')
    <style>
        body{
            background-color: black;
            color: white;
            line-height: 200%;
        }
        .transcript{
            padding-top: 100vh;
            padding-bottom: 100vh;
            font-size: 30px;
            line-height: 200%;
            height: 100%;
            width: 100%;
            background-color: black;
            color: white;
            transform: scaleY(-1);
        }

        .controls{
            position: fixed;
            bottom: 20px;
            left: 0;
            z-index: 3;
            width: 100%;
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
            }, 310/($('#speed').val()/10)*1000, "linear");
        });

        $(window).keydown(function(e) {
            if (e.which === 32) {

                pause();

            }
        });

        // $(window).keydown(function(e) {
        //     if (e.keyCode == 32) {
        //         console.log("paused");
        //         togglePause();
        //         return false;
        //     }
        // }).keyup(function(e){
        //     if (e.keyCode == 32) {
        //         console.log("resumed");
        //         togglePause();
        //     }
        //
        //     return false;
        // });

        function updateSpeed(){
            $('html, body').clearQueue();
            $('html, body').stop();
            $('html, body').animate({
                scrollTop: $(".transcript").offset().top
            }, 310/($('#speed').val()/10)*1000, "linear" );
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
                }, 310/($('#speed').val()/10)*1000, "linear" );
            });
        }

        function speedUp(){
            $('#speed').val( function(i, oldval) {
                return parseInt( oldval, 10) + 1;
            });
            $('html, body').animate({
                scrollTop: $(".transcript").offset().top
            }, 310/($('#speed').val()/10)*1000, "linear" );
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
            paused = true;
        }

        function resume(){
            updateSpeed();
            paused = false;
        }

        function togglePause(){
            if(paused){
                $('#pause').html('Pause');
                resume();
            }else{
                $('#pause').html('Play');
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
            }else if(e.keyCode == 32 && !paused) {
                togglePause();
                paused = true;
            }
            return false;
        });

        $("body").keyup(function(e) {
            if(e.keyCode == 32 && paused) {
                togglePause();
                paused = false;
            }
            return false;
        });
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="transcript">
            <p>{!! nl2br($contents) !!}</p>
        </div>
    </div>
    <div class="controls">
        <div class="row">
            <div class="col-10 m-auto">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <button class="btn btn-dark home-button">
                                <a href="{{route('frontend.index')}}" class="text-decoration-none">
                                    <i class="fa fa-arrow-left"></i>
                                    Home
                                </a>
                            </button>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row justify-content-center">
                            <label for="speed" class="col-form-label col-6 text-right">
                                Speed
                            </label>
                            <input type="number" name="speed" id="speed" value="1" class="form-control btn-dark col" />
                        </div>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="btn-group col" role="group" aria-label="Basic example">
                                <button name="restart" id="restart" class="btn btn-dark">Restart</button>
                                <button name="end" id="end" class="btn btn-dark">End</button>
                                <button name="pause" id="pause" class="btn btn-dark">Pause</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
