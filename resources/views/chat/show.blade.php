@extends('layouts.app')

@push('styles')
    <style>
        #users > li {
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card">
                        <div class="card-header">Chat</div>

                        <div class="card-body">
                            <div class="row p-2">
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-12 border rounded-1 p-3">
                                            <ul id="messages" class="list-unstyled overflow-auto" style="height: 45vh">
{{--                                                <li>Test1:Hello</li>--}}
{{--                                                <li>Test2:Hi there</li>--}}
                                            </ul>
                                        </div>
                                    </div>
                                    <form>
                                        <div class="row py-2">
                                            <div class="col-10">
                                                <input type="text" id="message" class="form-control">
                                            </div>
                                            <div class="col-2">
                                                <button id="send" type="submit" class="btn btn-primary btn-block">Send</button>
                                            </div>
                                        </div>
                                    </form>


                                </div>
                                <div class="col-2">
                                    <p class="">Online Now</p>
                                    <ul id="users" class="list-unstyled overflow-auto text-info" style="height: 45vh">
{{--                                        <li>--}}
{{--                                            Test1--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            Test2--}}
{{--                                        </li>--}}
                                    </ul>
                                </div>

                            </div>

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
        const usersElement = document.getElementById('users');
        const messagesElement = document.getElementById('messages');

        Echo.join('chat')
            .here((users)=>{

                users.forEach((user) => {
                    let element = document.createElement('li');
                    element.setAttribute('id', user.id);
                    element.setAttribute('onclick', 'greetUser("'+ user.id +'")');
                    element.innerText = user.name;
                    usersElement.appendChild(element);
                });
            })
            .joining((user)=>{
                let element = document.createElement('li');
                element.setAttribute('id', user.id);
                element.setAttribute('onclick', 'greetUser("'+ user.id +'")');
                element.innerText = user.name;
                usersElement.appendChild(element);
            })
            .leaving((user)=>{
                const element = document.getElementById(user.id);
                element.parentNode.removeChild(element);
            })
            .listen('MessageSent', (e)=>{
                let element = document.createElement('li');
                element.innerText = e.user.name + ": " + e.message;
                messagesElement.appendChild(element);
            })
    </script>

    <script>
        const messageElement = document.getElementById('message');
        const sendElement = document.getElementById('send');

        sendElement.addEventListener('click', (e)=>{
            e.preventDefault();
            window.axios.post('/chat/message', {
                message: messageElement.value,
            });
            messageElement.value = '';
        })
    </script>
    <script>
        function greetUser(id){
            window.axios.post('/chat/greet/' + id);
        }
    </script>

    <script>

        Echo.private('chat.greet.{{auth()->user()->id}}')
            .listen('GreetingEvent', (e)=>{
                let element = document.createElement('li');
                element.innerText = e.message;
                console.log(e.message);
                element.classList.add('text-success');
                messagesElement.appendChild(element);
            })
    </script>
@endpush
