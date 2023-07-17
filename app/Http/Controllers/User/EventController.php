<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateEventRequest;
use App\Models\Event\Event;
use App\Models\ServiceFee;
use App\Services\EventService;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private EventService $eventService; 

    public function __construct()
    {
        $this->eventService = new eventService;
    }

    public function index(Request $request)
    {
        $user = Auth::guard('user')->user();
        $queries = $request->all();
        $queries['user_id'] = $user->id;

        $events = $this->eventService->index($queries);
        $serviceFee = get_fee_service(7);
        $countTrash = $user->events()
            ->onlyIsDeleted()
            ->count();

        return view('user.events.index', [
            'events' => $events,
            'serviceFee' => $serviceFee,
            'countTrash' => $countTrash
        ]);
    }

    public function trash(Request $request)
    {
        $user = Auth::guard('user')->user();
        $queries = $request->all();
        $queries['user_id'] = $user->id;
        $queries['trashed'] = 'only';
        $events = $this->eventService->index($queries);

        return view('user.events.trash', [
            'events' => $events,
        ]);
    }

    public function edit(Event $event)
    {
        $user = Auth::guard('user')->user();
        if ($user->can('edit', $event)) {
            $provinces = get_cache_province();
            $districts = get_cache_district();
            $serviceFee = get_fee_service(7);

            return view('user.events.edit', [
                'event' => $event,
                'provinces' => $provinces,
                'districts' => $districts,
                'serviceFee' => $serviceFee,
            ]);
        } else {
            Toastr::error('Bạn không đủ quyền!');
            return redirect(route('user.events.index'));
        }
    }

    public function update(Event $event, UpdateEventRequest $request)
    {
        $user = Auth::guard('user')->user();
        if ($user->can('edit', $event)) {
            // must check what fields edit can not change or must reset after change: date, status, etc ...
            $this->eventService->update($event, $user, $request->all());
            $this->eventService->updateLocation($event, $request->all());
            Toastr::success('Sửa thành công');
            return back();
        } else {
            Toastr::error('Bạn không đủ quyền!');
            return back();
        }
    }

    public function deleteMultiple(Request $request)
    {
        $user = Auth::guard('user')->user();

        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        $user->events()->find($ids)
            ->each(function($event) {
                $event->delete();
                // create_user_log()
            });

        Toastr::success('Xóa thành công');

        return redirect(route('user.events.index'));
    }

    public function restoreMultiple(Request $request)
    {
        $user = Auth::guard('user')->user();

        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        $user->events()->onlyIsDeleted()
            ->find($ids)
            ->each(function($event) {
                $event->restore();
                // create_user_log()
            });

        Toastr::success('Khôi phục thành công');

        return redirect(route('user.events.trash'));
    }

    /**
     * highlight event
     */
    public function highlight(Event $event)
    {
        $user = Auth::guard('user')->user();

        $success = false;

        if ($user->can('highlight', $event)) {
            $response = $this->eventService->highlight($event, $user);
            $message = data_get($response, 'message');
            $success = data_get($response, 'success');
        } else {
            $message = 'Bạn không đủ quyền!';
        }

        $success ? Toastr::success($message) : Toastr::error($message);

        return redirect()->back();
    }
}
