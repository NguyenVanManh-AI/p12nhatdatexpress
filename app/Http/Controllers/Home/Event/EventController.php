<?php

namespace App\Http\Controllers\Home\Event;

use App\Helpers\Helper;
use App\Helpers\SystemConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\RatingEventRequest;
use App\Http\Requests\Event\SendAdvisoryRequest;
use App\Http\Requests\Home\Event\EventReportRequest;
use App\Http\Requests\Home\Event\EventRequest;
use App\Jobs\SendAdvisoryJob;
use App\Jobs\SendUserEmail;
use App\Models\District;
use App\Models\Event\Event;
use App\Models\Event\EventRating;
use App\Models\Province;
use App\Services\AdvisoryService;
use App\Services\EventService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class EventController extends Controller
{
    public $coin_per_date = 1000;
    private EventService $eventService;
    private AdvisoryService $advisoryService;

    public function __construct()
    {
        $this->advisoryService = new AdvisoryService;
        $this->eventService = new EventService;
    }

    /**
     * List event
     */
    public function list(Request $request)
    {
        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $events = Event::showed()
            ->latest('is_highlight')
            ->oldest('start_date')
            ->filter($params)
            ->paginate(config('constants.home.num_paginate'));
        $num_collection = collect(['num_cur' => $events->currentPage() * config('constants.home.num_paginate')]);
        $provinces = Province::orderBy('province_type', 'desc')->showed()->get();
        $districts = District::orderBy('district_name', 'asc')->showed()->get();

        return view('Home.Event.ListEvent', compact('events', 'num_collection', 'provinces', 'districts'));
    }

    /**
     * Ajax call auto load page
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax_list(Request $request){
        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $events = Event::showed()
            ->orderBy('is_highlight', 'desc')
            ->orderBy('start_date', 'asc')
            ->filter($params)
            ->offset($request->num_cur)
            ->take(config('constants.home.num_paginate'))->get();

        $html = '';
        foreach ($events as $item){
            $html .= view('components.home.event.item-event', compact('item'))->render();
        }

        return response()->json([
            'num' => config('constants.home.num_paginate'),
            'html' => $html
        ]);
    }

    /**
     * Detail Event
     * @param Request $request
     * @param $event_url
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|void
     */
    public function detail(Request $request, $event_url)
    {
        $event = Event::where('event_url', $event_url)
            ->confirmed() // maybe showed()
            ->firstOrFail();

        // $ip = $request->ip();
        $event->increment('num_view');

        return view('Home.Event.DetailEvent', [
            'event' => $event,
        ]);
    }

    public function add(EventRequest $request)
    {
        $user = Auth::guard('user')->user();
        if ($user->user_type_id != 3)
        {
            Toastr::error('Chỉ tài khoản doanh nghiệp mới thêm được sự kiện!');
            return back();
        }

        $services_fee = get_fee_service(7);

        if ($request->is_highlight && $services_fee && $user->coint_amount < $services_fee->service_fee) {
            Toastr::error('Không đủ coin để sử dụng tính năng nổi bật!');
            Session::flash('popup_display', '#create-event');
            return back()->withInput();
        }

        try {
            $event = new Event();
            $event->fill($request->all());
            $event->is_highlight = false;
            $event->start_date = strtotime(str_replace('/', '-', $request->start_date) . ' ' . $request->start_time);
            $event->created_at = strtotime('now');
            $event->created_by = $user->id;
            $event->user_id = $user->id;

            // Save file
            if ($request->has('image_header_url') && $request->image_header_url)
                // $event->image_header_url = file_helper($request->image_header_url, Helper::get_upload_dir_user(Auth::guard('user')->user()));
                $event->image_header_url = base64ToFile(
                    $request->image_header_url,
                    SystemConfig::userDirectory(),
                    false,
                );

            if ($request->has('image_invitation_url') && $request->image_invitation_url)
                // $event->image_invitation_url = file_helper($request->image_invitation_url, Helper::get_upload_dir_user(Auth::guard('user')->user()));
                $event->image_invitation_url = base64ToFile(
                    $request->image_invitation_url,
                    SystemConfig::userDirectory(),
                    false,
                );

            if ($request->is_highlight && $services_fee) {
                DB::table('user_transaction')->insert([
                    'user_id' => $user->id,
                    'transaction_type' => 'S', // mua dich vu,
                    'sevice_fee_id' => $services_fee->id,
                    'coin_amount' => $services_fee->service_fee,
                    'total_coin_amount' => $services_fee->service_fee,
                    'created_at' => time(),
                    'created_by' => $user->id
                ]);

                $user->decrement('coint_amount', $services_fee->service_fee);

                $event->highlight_end = time() + $services_fee->existence_time;
                $event->is_highlight = true;
            }

            $event->save();

            // create event location
            $this->eventService->createLocation($event, $request->all());

            Toastr::success('Tạo sự kiện thành công');
        }catch (\Exception $exception){
            Toastr::error("Tạo sự kiện không thành công");
            Session::flash('popup_display', '#create-event');
            return back()->withInput();
        }

        return back();
    }

    public function rating_event($event_id,Request $request){
        if(!$request->rating){
            Toastr::error('Vui lòng chọn số sao đánh giá');
            return back();
        }
        // check đăng nhập
        $check_login =Auth::guard('user')->check();
        // không đăng nhập
        if(!$check_login){

            $ratin = EventRating::query()->where('ip',$request->ip())->where('event_id',$event_id)->first();
            if($ratin){
                $ratin->star=$request->rating;
                $ratin->ip=$request->ip();
                $ratin->rating_time=time();
                $ratin->event_id=$event_id;
                $ratin->save();
            }
            else {
                $ratin = new EventRating();
                $ratin->star=$request->rating;
                $ratin->ip=$request->ip();
                $ratin->rating_time=time();
                $ratin->event_id=$event_id;
                $ratin->save();
            }
            Toastr::success('Đánh giá thành công');
            return back();
        }
        if ($check_login){
            $user_id = Auth::guard('user')->id();
            $ratin = EventRating::query()->where('user_id',$user_id)->where('event_id',$event_id)->first();
            if($ratin){
                $ratin->star=$request->rating;
                $ratin->user_id=$user_id;
                $ratin->rating_time=time();
                $ratin->event_id=$event_id;
                $ratin->save();
            }
            else {
                $ratin = new EventRating();
                $ratin->star=$request->rating;
                $ratin->user_id=$user_id;
                $ratin->rating_time=time();
                $ratin->event_id=$event_id;
                $ratin->save();
            }
            Toastr::success('Đánh giá thành công');
            return back();
        }
        Toastr::error('Lỗi đánh giá');
        return back();
    }

    /**
     * Rating event
     */
    public function rating($eventId, RatingEventRequest $request)
    {
        $event = Event::confirmed() // maybe change to showed
            ->find($eventId);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Sự kiện không tồn tại!',
            ], 400);
        }

        $user = Auth::guard('user')->user();
        $request['user_id'] = data_get($user, 'id');
        $request['ip'] = $request->ip();
        $this->eventService->createRating($event, $request->all());

        $rating_avg = round($event->ratings->pluck('star')->avg());
        $total_rating = $event->ratings->count();
        $html = view('components.common.detail.review-result', [
            'item' => $event,
            'rating_avg' => $rating_avg,
            'total_rating' => $total_rating,
            'old_rating' => $request->star
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá thành công',
            'data' => [
                'html' => mb_convert_encoding($html, 'UTF-8', 'UTF-8'),
            ]
        ], 200);
    }

    public function sendAdvisory($id, SendAdvisoryRequest $request)
    {
        $event = Event::confirmed() // maybe change to showed
            ->find($id);

        if (!$event || !$event->user || !$event->user->email) {
            return response()->json([
                'success' => false,
                'message' => 'Gửi thông tin không thành công',
            ], 403);
        }

        $ip = $request->ip();
        $userId = Auth::guard('user')->id();
        $checkedData = [
            'ip' => $ip,
            'user_id' => $userId,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ];

        $checkLimitDayAdvisories = $this->advisoryService->checkAdded($event, $checkedData);

        if ($checkLimitDayAdvisories && !data_get($checkLimitDayAdvisories, 'success')) {
            return response()->json([
                'success' => false,
                'message' => data_get($checkLimitDayAdvisories, 'message'),
            ], 422);
        }

        // advisory
        $advisoryData = [
            'fullname' => $request->fullname,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'note' => $request->note,
            'ip' => $ip,
            'user_id' => $userId,
            'options' => [
                'registration' => [
                    'url' => route('home.event.detail', $event->event_url),
                    'name' => $event->event_title
                ]
            ]
        ];
        $advisory = $this->advisoryService->create($event, $advisoryData);
        // end advisory

        SendAdvisoryJob::dispatch($advisory->id);

        return response()->json([
            'success' => true,
            'message' => 'Gửi thông tin thành công',
        ], 200);
    }

    // report content
    public function  reportContent(EventReportRequest $request, $id)
    {
        // kiểm tra đăng nhập
        if(!Auth::guard('user')->check()){
            Toastr::error('Vui lòng đăng nhập');
            return back();
        }

        $user = Auth::guard('user')->user();
        $event = Event::findOrFail($id);

        $reported = $event->reports()
            ->firstWhere('user_id', $user->id);
        if($reported) {
            Toastr::error('Mỗi tài khoản chỉ được báo cáo 1 lần');
            return back();
        }

        $request['user_id'] = $user->id;
        $report = $this->eventService->createReport($event, $request->all());

        Toastr::success('Báo cáo thành công');

        return $report->event->is_block
            ? redirect(route('home.index'))
            : back();
    }
}
