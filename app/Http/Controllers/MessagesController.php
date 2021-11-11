<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Messages;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    //
    public function storeMessage(Request $request){
        $message = $request->get('message');
        $sender_id = $request->get('sender_id');
        $reciver_id = $request->get('reciver_id');
        $sessionSender = $request->session()->get('id');

        $message_id = (Messages::select('message_id',)->where('sender_id', '=', $sessionSender)
                                                  ->where('receiver_id', '=', $reciver_id)
                                                  ->get());
        $message_id_1 = (Messages::select('message_id',)->where('sender_id', '=', $reciver_id)
                                                  ->where('receiver_id', '=', $sessionSender)
                                                  ->get());



        if(!empty($message_id[0]) or !empty($message_id_1[0])){
                if(empty($message_id[0])){
                            $id_msg = $message_id_1[0]->message_id;
                        }else{
                            $id_msg = $message_id[0]->message_id;
                        }
        }



        if ($sessionSender == $sender_id ){

                if (empty($message_id[0]) and empty($message_id_1[0])  ) {
                    // return "ok0";
                    $newMessageId = (rand(10,1000));
                    Messages::insert([
                    'message_id' => $newMessageId,
                    'message' => $message,
                    'sender_id' => $sessionSender,
                    'receiver_id' => $reciver_id,
                    'created_at' => date("Y-m-d H:i:s")]);
                }
                else{

                    $result = Messages::insert([
                        'message_id' => $id_msg,
                        'message' => $message,
                        'sender_id' => $sessionSender,
                        'receiver_id' => $reciver_id,
                        'created_at' => date("Y-m-d H:i:s")]);
                }



        }else if ($sessionSender == $reciver_id){
             if (empty($message_id[0]) and empty($message_id_1[0])) {
                    // return "ok1";
                    $newMessageId = (rand(10,1000));
                    Messages::insert([
                    'message_id' => $newMessageId,
                    'message' => $message,
                    'sender_id' => $sessionSender,
                    'receiver_id' => $reciver_id,
                    'created_at' => date("Y-m-d H:i:s")]);
                }
                else{
                    $result = Messages::insert([
                        'message_id' =>$id_msg ,
                        'message' => $message,
                        'sender_id' => $sessionSender,
                        'receiver_id' => $reciver_id,
                        'created_at' => date("Y-m-d H:i:s")]);
                }
        }else{
            return "no user";
        }



        // return $id_msg;
        $allMessages = (Messages::select('message','sender_id','receiver_id')->where('message_id', '=', $id_msg)->get());
        return $allMessages;




    }


    public function getUserMessages(Request $request){
        $sender_person_to = $request->get('sender_person_to');
        $sessionSender = $request->session()->get('id');
        $message_id = (Messages::select('message_id',)->where('sender_id', '=', $sessionSender)
                                                  ->where('receiver_id', '=', $sender_person_to)
                                                  ->limit(1)
                                                  ->get());
        if(!empty($message_id)){
        $conversation_id = $message_id[0]->message_id;
        $all_messages =   (Messages::where('message_id', '=', $conversation_id)->get());
        return $all_messages;
        }


    }

    public function getUserMSgID(Request $request){

        $sender_id = $request->get('sender_id');
        $reciver_id = $request->get('reciver_id');
        return $reciver_id;
    }
}
