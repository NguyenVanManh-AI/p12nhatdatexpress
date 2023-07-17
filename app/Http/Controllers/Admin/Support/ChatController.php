<?php

namespace App\Http\Controllers\Admin\Support;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\CreateMessage;
use App\Models\Chat\TempChat;
use App\Models\ChatHistory;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    # List session and history chat
    public function list($code = null)
    {
        // Initialize param
        $param = [];
        $param['messages'] = [];

        if ($code != null){
            $this->read_message($code);
        }
        // Get Session
        $this->get_sessions($param);

        // Get message
        if($param['chat_session']->count() > 0){

            $index = $param['chat_session']->search(function ($session) use ($code) {
                return $session->chat_code == $code;
            });
            $param['chat_session'][$index]['selected'] = 1;
            $param['chat_code'] = $param['chat_session'][$index]->chat_code;
            $param['selected'] = $param['chat_session'][$index];

            $this->get_message($param, $index);


        }

        return view('Admin.Support.Chat', $param);
    }

    # Store message
    public function store_message(CreateMessage $request){
        if (TempChat::where('chat_code', $request->chat_code)->count() > 0){
            $this->read_message($request->chat_code);
        }
        $message = new TempChat();
        $message->fill($request->all());
        $message->admin_id = auth('admin')->id();
        $message->user_id = optional(DB::table('user')->find($request->user_id))->id;
        $message->type = 1;
        $message->is_read = 1;
        $message->save();

        broadcast(new MessageSent($message));

        return response()->json([
            'message' => $message
        ], 200);
    }

    # Get List Session chat (SUPPORT FUNCTION)
    private function get_sessions(&$param){
        $session_temp = TempChat::select('chat_code', 'fullname', 'image_url','ud.user_id', 'u.user_code')
            ->join('user_detail as ud', 'ud.user_id', '=', 'temp_chat.user_id')
            ->join('user as u', 'u.id', '=', 'temp_chat.user_id')
            ->where('admin_id', auth('admin')->id())
            ->groupBy('chat_code', 'ud.fullname', 'ud.image_url', 'ud.user_id', 'u.user_code', 'temp_chat.id')
            ->orderBy('temp_chat.id','desc')
            ->get();

        $session_history = ChatHistory::select('chat_code', 'ud.fullname', 'ud.image_url', 'ud.user_id', 'u.user_code')
            ->where(['admin_id' => auth('admin')->id()])
            ->join('user_detail as ud', 'ud.user_id', '=', 'chat_history.user_id')
            ->join('user as u', 'u.id', '=', 'chat_history.user_id')
            ->orderBy('chat_history.id', 'desc')
            ->get();
        $param['chat_session'] = $session_temp->concat($session_history);
        $param['chat_session']->map(function ($item){
            $item['selected'] = 0;
            $item['unread'] = 0;
            $table = $item->getTable();
            if ($table == 'chat_history'){
                $lasted_conversion = (array) DB::table('chat_history')->where('chat_code', $item->chat_code)->first();
                $unseralize_value = unserialize(optional($lasted_conversion)['chat_message']);
                $item->lasted_message =  $unseralize_value[array_key_last($unseralize_value)];
            }else{
                $item['unread'] = TempChat::where(['chat_code' => $item->chat_code, 'is_read' => 0])->count();
                $item->lasted_message = (array) DB::table('temp_chat')
                    ->select('chat_message as message', 'chat_time as time', 'type')
                    ->where('chat_code', $item->chat_code)->orderBy('id', 'desc')->first();
            }
        });
    }

    # Get Message with index conversion (SUPPORT FUNCTION)
    private function get_message(&$param, $index = 0){
        $table = $param['chat_session'][$index]->getTable();
        $data = $table == 'chat_history'
            ? ChatHistory::where('chat_code', $param['chat_session'][$index]->chat_code)->get()->toArray()
            : TempChat::where('chat_code', $param['chat_session'][$index]->chat_code)->get()->toArray();
        $param['is_end'] = $table == 'chat_history';
        if ($table == 'chat_history'){
            foreach ($data as $item){
                $item = (array) $item;
                $unserialize_value = unserialize($item['chat_message']);
                $list_history_convert = [];
                foreach ($unserialize_value as $ms){
                    $list_history_convert[] = [
                        'chat_code' => $item['chat_code'],
                        'user_id' => $item['user_id'],
                        'admin_id' => $item['admin_id'],
                        'chat_message' => $ms['message'],
                        'type' => $ms['type'],
                        'chat_time' => date('H:s d/m/Y' , $ms['time']),
                    ];
                }
                $data = $list_history_convert;
            }
        }else{
            $data = json_decode(json_encode($data), true);
        }
        $param['messages'] = $data;
    }

    # Close session chat
    public function close_session($chat_code){
        app('App\Http\Controllers\User\SupportController')->save_temp_chat($chat_code);
    }

    # Read all message in session
    public function read_message($chat_code){
        TempChat::where('chat_code' ,$chat_code)->update([
           'is_read' => 1
        ]);
    }
}

