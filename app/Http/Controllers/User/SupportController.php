<?php

namespace App\Http\Controllers\User;

use App\Enums\ChatStatus;
use App\Enums\ChatType;
use App\Events\CloseSession;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\CreateMessage;
use App\Http\Requests\User\Support\SendMailRequest;
use App\Models\Admin;
use App\Models\Chat\TempChat;
use App\Models\ChatHistory;
use App\Models\User;
use App\Models\User\UserType;
use App\Services\AdminService;
use App\Services\ConversationService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    private AdminService $adminService;
    private ConversationService $conversationService;

    public function __construct()
    {
        $this->adminService = new AdminService;
        $this->conversationService = new ConversationService;
    }

    public function index(Request $request)
    {
        $user = Auth::guard('user')->user();
        $careStaffs = $this->adminService->getSupportAccount(); // must select column
        $chatTypes = ChatType::getValues();
        $ratings = [1, 2, 3, 4, 5];
        $statuses = ChatStatus::getValues();
        $request['sender_id'] = $user->id;
        $conversations = $this->conversationService->index($request->all());

        return view('user.supports.index', [
            'chatTypes' => $chatTypes,
            'careStaffs' => $careStaffs,
            'conversations' => $conversations,
            'ratings' => $ratings,
            'statuses' => $statuses,
        ]);
    }

    public function newConversation()
    {
        $user = Auth::user();

        $userChat = User::find(request()->user_id);

        if ($userChat && $user->id !== $userChat->id) {
            $conversationUsers = DB::connection('mysql_chats')
                        ->table('conversation_user')
                        ->select(DB::raw("conversation_id, COUNT(*) as count"))
                        ->groupBy('conversation_id')
                        ->where(function($query) use ($userChat, $user) {
                            return $query->orWhere('user_id', $user->id)
                                        ->orWhere('user_id', $userChat->id);
                        })
                        ->having('count', '>', '1')
                        ->first();

            if (!$conversationUsers) {
                $activeConversation = Conversation::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $userChat->id,
                    'token' => md5(uniqid($user->id) . date('d-m-Y H:i:s'))
                ]);

                $activeConversation->users()->sync([$userChat->id, $user->id]);
            } else {
                $activeConversation = $user->conversations()->where(function($query) use ($userChat) {
                                    return $query->orWhere('sender_id', $userChat->id)
                                                ->orWhere('receiver_id', $userChat->id);
                                })
                                ->first();
            }

            if (!$activeConversation) {
                return response()->json([
                    'success' => false,
                    'message' => __('Not found conversation.')
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'conversation' => (new ConversationResource($activeConversation))->resolve([]),
                ]
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    #Danh sách mailbox
    public function mailbox($mail_status = 0)
    {
        $user = Auth::guard('user')->user();
        $mails = DB::table('mailbox')
            ->where('user_id', $user->id)
            ->where('is_deleted', '<>', 1)
            ->latest('send_time')
            ->when(request()->search, function ($query, $search) {
                return $query->where('mail_title', 'LIKE', '%' . $search . '%');
            });

        #mail_status == 0: tất cả thư
        #mail_status == 1: Thu đã đọc
        $mail_status == 1?$mails->where('mailbox_status', 1):null;
        #mail_status == 2: thư chưa đọc
        $mail_status == 2?$mails->where('mailbox_status', 0):null;
        #mail_status == 3: thư được ghim
        $mail_status == 3?$mails->where('mailbox_pin', 1):null;
        #mail_status == 4: thông báo tin đăng
        $mail_status == 4?$mails->where('mailbox_type', 'I'):null;
        #mail_status == 5: Tài khoản
        $mail_status == 5?$mails->where('mailbox_type', 'A'):null;
        #mail_status == 6: Tài khoản
        $mail_status == 6?$mails->where('mailbox_type', 'S'):null;

        $params['mails'] = $mails->paginate(20);

        return view('user.support.mailbox', $params);
    }

    #Chi tiết mail
    public function mailbox_detail($mailbox_id)
    {
        $user = Auth::guard('user')->user();

        DB::table('mailbox')
            ->where('user_id', $user->id)
            ->where('id', $mailbox_id)
            ->update(['mailbox_status' => 1]);

        $params['mailbox_detail'] = DB::table('mailbox')
            ->where('user_id', $user->id)
            ->where('id', $mailbox_id)
            ->where('is_deleted', '<>', 1)
            ->first();

        return View('user.support.mailbox-detail', $params);
    }

    #Hỗ trợ kỹ thuật
    public function support()
    {
        $params['mail_types'] = array('I' => 'Tin đăng', 'A' => 'Tài khoản', 'S' => 'Hệ thống');
        $params['customer_care'] = Admin::where(['is_deleted' => 0, 'is_customer_care' => 1])
            ->select('id', 'admin_fullname', 'image_url', 'is_online', 'rating', 'is_customer_care', 'is_deleted')
            ->orderBy('rating', 'desc')
            ->get();
        return View('user.support.support', $params);
    }

    #Gửi mail hỗ trợ kỹ thuật
    public function post_support(SendMailRequest $request)
    {
        $mail_data = [
            'object_type' => 1,
            'mail_title' => $request->mail_title,
            'mail_content' => $request->mail_content,
            'mailbox_type' => $request->mail_type,
            'send_time' => time(),
            'created_by' => Auth::guard('user')->user()->id
        ];

        DB::table('mailbox')->insert($mail_data);

        Toastr::success('Gửi thư thành công');
        return redirect()->back();
    }

    # Generate chat code
    public function generate_chat_code($admin_id){
        $chat_code = optional(TempChat::where(['admin_id' => $admin_id, 'user_id' => auth('user')->id()])->first())->chat_code;
        $history = [];
        if ($chat_code == null)
        {
            $admin = DB::table('admin')->find($admin_id);
            $chat_code = auth('user')->id() . $admin->id . time();
        }

        return redirect()->route('user.detail-chat', [$admin_id, $chat_code]);
    }

    #Chi tiết hội thoại
    public function detail_chat(Request $request, $admin_id, $chat_code){ $this->update_rating_admin(1);
        if ($this->check_end($chat_code)) return redirect()->route('user.generate-chat', $admin_id);

        $history = TempChat::where('chat_code', $chat_code)->get();
        $admin = DB::table('admin')->find($admin_id);

        return view('user.support.detail-chat', compact('admin', 'history', 'chat_code'));
    }

    #Lưu message
    public function store_message(CreateMessage $request){
        $message = new TempChat();
        $message->fill($request->all());
        $message->user_id = auth('user')->id();
        $message->admin_id = optional(DB::table('admin')->find($request->admin_id))->id;
        $message->type = 0;
        $message->save();

        broadcast(new MessageSent($message));

        return response()->json([
            'message' => $message
        ], 200);
    }

    #Đánh giá phiên chat
    public function rating_session(Request $request, $chat_code){
        $is_exist_temp = TempChat::where('chat_code', $chat_code)->first();
        if ($is_exist_temp) {
            $result = TempChat::where('chat_code', $chat_code)->update([
                'rating' => $request->rating
            ]);
            $this->update_rating_admin($is_exist_temp->admin_id);
            if ($request->is_end) {
                return $this->save_temp_chat($chat_code);
            }
        }else{
            $result = ChatHistory::where('chat_code', $chat_code)->update([
                'rating' => $request->rating
            ]);
        }

        return response()->json([
            'status' => $result ? 'success' : 'error',
            'rating' => $request->rating,
        ], $result ? 200 : 402);
    }

    # Check is end chat code
    public function check_end($chat_code){
        $chat_history = ChatHistory::where('chat_code',$chat_code)->first();
        return $chat_history ? 1 : 0;
    }

    # Saving a conversion
    public function save_temp_chat($chat_code){
        $temp_chat = collect(TempChat::select('id', 'chat_code', 'user_id', 'admin_id', 'chat_message as message', 'chat_time as time', 'type', 'rating')
            ->where('chat_code', $chat_code)
            ->get()->toArray());

        if ($temp_chat->count() > 0){
            $chat_message_content = $temp_chat->map(function ($item, $key){
                return collect($item)->only(['message', 'type', 'time'])->toArray();
            })->toArray();

            $first_time_user = optional(TempChat::where(['chat_code' => $chat_code, 'type' => 0])->orderBy('id', 'asc')->first('chat_time'))->getRawOriginal('chat_time');
            $first_time_admin = optional(TempChat::where(['chat_code' => $chat_code, 'type' => 1])->orderBy('id', 'asc')->first('chat_time'))->getRawOriginal('chat_time');

            try{
                DB::beginTransaction();

                $chat_history = new ChatHistory();
                $chat_history->chat_code = $chat_code;
                $chat_history->user_id = $temp_chat[0]['user_id'];
                $chat_history->admin_id = $temp_chat[0]['admin_id'];
                $chat_history->rating = $temp_chat[0]['rating'] ?? null;
                $chat_history->respontime = $first_time_admin - $first_time_user > 0 ? $first_time_admin - $first_time_user : null;
                $chat_history->chat_message = serialize($chat_message_content);
                $chat_history->created_at = $temp_chat[0]['time'];
                $chat_history->save();

                TempChat::where('chat_code', $chat_code)->delete();
                DB::commit();
            }catch(\Exception $e){
                DB::rollback();
            }

            broadcast(new CloseSession($chat_code));
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error'], 502);
    }

    # Update rating admin
    public function update_rating_admin($admin_id){
        $avg_rate = number_format(round(ChatHistory::where('admin_id', $admin_id)->avg('rating')), 0);
        DB::table('admin')->where('id', $admin_id)->update([
            'rating' => $avg_rate
        ]);
    }
}

