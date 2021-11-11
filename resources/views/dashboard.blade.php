<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat App</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
      <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- CSS only -->
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
            <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
        <style>
            .chat-row {
                margin: 50px;
            }


             ul {
                 margin: 0;
                 padding: 0;
                 list-style: none;
             }


             ul li {
                 padding:8px;
                 background: #928787;
                 margin-bottom:20px;
             }


             ul li:nth-child(2n-2) {
                background: #c3c5c5;
             }


             .chat-input {
                 border: 1px soild lightgray;
                 border-top-right-radius: 10px;
                 border-top-left-radius: 10px;
                 padding: 8px 10px;
                 color: black;
             }
        </style>
</head>

<body>

    {{-- 12345Shakil --}}

    <nav class="navbar navbar-light navbar-expand-lg mb-5" style="background-color: #e3f2fd;">
        <div class="container">
            <a class="navbar-brand mr-auto" href="#">Chat APP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register-user') }}">Register</a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('signout') }}">Logout</a>

                    </li>
                      <p>MR {{ session()->get('name') }}</p>
                      <input type="hidden" id="sender_id_main" name="sender_id_main" value="{{ session()->get('id') }}">
                    @endguest
                </ul>
            </div>



        </div>
    </nav>
    @yield('content')


@guest

{{-- <p> login first </p> --}}

 @else

 <div class="chat-container">
        <header class="chat-header">
            <h1><i class="fas fa-smile"></i> ChatRoom</h1>
            <a href="" class="btn">Leave Room</a>
        </header>
        <main class="chat-main">
            <div class="chat-sidebar">
                <h3><i class="fas fa-comments"></i> {{ session()->get('name') }}</h3>
                <h3><i class="fas fa-users"></i> Users</h3>
                <ul id="users">
                    @foreach ($users as $user)
                    {{-- <a href="">{{ $user->name }}</a> --}}
                    @if ($user['id'] != session()->get('id'))
                        <li class="get_sender_id" data-id="{{$user['id']}}">{{ $user->name }}</li>
                    @endif

                        {{-- <p> <button style="margin-top: 2px;" data-id="{{$user['id']}}" Show</button></p> --}}
                        {{-- <input type="button" id="send_user_id" name="send_user_id" value="{{ $user->id }}"> --}}
                    @endforeach
                </ul>
            </div>
            <div class="chat-messages">
                <div class="row chat-row">
                <div  class="chat-content">

                    <ul>
                        <p>Welcome to your conversation </p>
                    </ul>
                    <ul id="allmessagges">

                    </ul>
                </div>

            </div>

            </div>
        </main>
        <div class="chat-form-container">
              <div class="chat-section ">
                    <div class="chat-box">
                        <h3 id="sender_id_"></h3>
                         <input type="hidden" id="sender_id_" name="sender_id_" value="">
                        <div style="background-color: white !important;" class="chat-input bg-primary" id="chatInput" contenteditable="">

                        </div>
                    </div>
                </div>
            {{-- <form id="chat-form">
                <input id="msg" type="text" placeholder="Enter Message" required autocomplete="off" />
                <button class="btn"><i class="fas fa-paper-plane"></i> Send</button>
            </form> --}}
        </div>
    </div>


            {{-- <div class="container"> --}}



        </div>
@endguest
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.socket.io/4.0.1/socket.io.min.js" integrity="sha384-LzhRnpGmQP+lOvWruF/lgkcqD+WDVt9fU3H4BWmwP5u5LTmkUGafMcpZKNObVMLU" crossorigin="anonymous"></script>



        <script>

                // io.to(socket.id).emit("event", data);
                // var people={};
                // people[name] =  socket.id;
                // console.log("hello");


            $(function() {
                let ip_address = '127.0.0.1';
                let socket_port = '3000';
                let socket = io(ip_address + ':' + socket_port);
                // let socket = io('https://realtime.splitreq.io/');
                let chatInput = $('#chatInput');
                var sender_id = document.getElementById('sender_id_main').value;
                var mychannel='sendChatToClient_'+ sender_id;


                chatInput.keypress(function(e) {
                    let message = $(this).html();
                    console.log(message);
                    if(e.which === 13 && !e.shiftKey) {
                        var sender_id = document.getElementById('sender_id_main').value;
                        var reciver_id = document.getElementById('sender_id_').value;
                        $('#allmessagges').append(`<li>${message}</li>`);
                        console.log(socket.id)
                        var channel='sendChatToClient_'+reciver_id;
                        let msginfo = {sender:sender_id, reciver:reciver_id, message:message,channel:channel};
                        socket.emit('sendChatToServer',msginfo);
                        messageSendToDatabase(message,sender_id, reciver_id);
                        $(this).html('');
                        return false;
                    }
                });
                // sendChatToClient1
                // var chat_content = ".chat-content_"+sender_id + ' ul';
                // console.log(chat_content);
                socket.on('channel', (msginfo) => {
                    // var reciver_id = document.getElementById('sender_id_main').value;
                    // if(reciver_id == msginfo['reciver']){
                            $('#allmessagges').append(`<li >${msginfo['message']}</li>`);
                    // }
                });
            });


                $('.get_sender_id').click(function() {

                    document.getElementById("allmessagges").innerHTML = "";
                    var sender_id = $(this).data("id");
                    document.getElementById("sender_id_").value = sender_id;
                    getPreviousMessage(sender_id);

                 })



    function messageSendToDatabase(message,sender_id, reciver_id ) {
        // alert("hello")
        axios.post('/messageToDatabase', {
                message: message,
                sender_id: sender_id,
                reciver_id: reciver_id,
            })
            .then(function(response) {
                if (response.status == 200) {
                    //  msg_id = (response.data);
                    //  return msg_id;
                } else {
                    console.log("error");
                }
            }).catch(function(error) {
                console.log(" catch error");
            });



    }












    function getPreviousMessage(sender_id){
        axios.post('/getPreviousData', {
                sender_person_to: sender_id,

            })
            .then(function(response) {
                if (response.status == 200) {

                    var data = response.data;
                    var roomId = (data[0]['message_id']);
                    var sender_id = document.getElementById('sender_id_main').value;
                    var reciver_id = document.getElementById('sender_id_').value;
                    // userSessionManage(sender_id,reciver_id, roomId);
                    for (let i = 0; i < data.length; i++) {
                        if (sender_id == data[i]['sender_id']){
                                $('#allmessagges').append(`<li>${data[i]['message']}</li>`);
                        }else{
                                $('#allmessagges').append(`<li>${data[i]['message']}</li>`);
                        }
                    }
                } else {
                    console.log("error");
                }
            }).catch(function(error) {
                console.log(" catch error");
            });
    }


        // function getMessageId(sender_id,reciver_id) {
        //        var msg_id = 0;
        //     axios.get('/getmsgID', {
        //         sender_id: sender_id,
        //         reciver_id: reciver_id,
        //     })
        //     .then(function(response) {
        //         if (response.status == 200) {
        //             msg_id = response.data;

        //         } else {
        //             console.log("error");
        //         }
        //     }).catch(function(error) {
        //         console.log(" catch error");
        //     });


        //     return msg_id;
        // }


    </script>


</body>
<script src="{{ URL::asset('js/main.js') }}"></script>
</html>
