@extends('layouts.app')

@push('styles')
    <style>
        @keyframes rotate {
            from{
                transform: rotate(0deg);

            }to{
                transform: rotate(360deg);
            }
        }

        .refresh{
            animation: rotate 1.5s linear infinite;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card">
                        <div class="card-header">Game</div>

                        <div class="card-body">
                            <div class="text-center">
                                <img id="circle" src="{{asset('img/circle.png')}}" alt="" height="250" width="250">
                                <p id="winner" class="display-1 d-none text-primary"></p>
                            </div>

                        </div>
                        <hr>
                        <div class="text-center">
                            <label class="font-weight-bold h5">Bet</label>
                            <select id="bet" class="custom-select col-auto">
                                <option selected>Not in</option>
                                @foreach( range(1, 12) as $number)
                                <option>{{$number}}</option>
                                @endforeach
                            </select>

                            <hr>
                            <p class="font-weight-bold h5">Remaining time</p>
                            <p id="timer" class="h5 text-danger">Waiting to start</p>
                            <p id="result" class="h-1"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
    <script>

        const circle = document.getElementById('circle');
        const timer = document.getElementById('timer');
        const winnerElement = document.getElementById('winner');
        const result = document.getElementById('result');
        const betElement = document.getElementById('bet');

        Echo.channel('game')
            .listen('RemainingTimeChanged', (e)=>{
                timer.innerText = e.time;

                if (!circle.classList.contains('refresh')) {
                    circle.classList.add('refresh'); // Keep rotating
                }
                winnerElement.classList.add('d-none');
                result.innerText = '';
                result.classList.remove('text-success');
                result.classList.remove('text-danger');
            })
            .listen('WinnerNumberGenerated', (e)=>{
                circle.classList.remove('refresh');

                let winner = e.number;
                winnerElement.innerText = winner;

                circle.classList.remove('d-none');

                let bet = betElement[betElement.selectedIndex].innerText

                if(bet == winner){
                    result.innerText = 'You WIN';
                    result.classList.add('text-success');
                }else{
                    result.innerText = 'You Lose';
                    result.classList.add('text-danger');
                }
            })
    </script>
@endpush
