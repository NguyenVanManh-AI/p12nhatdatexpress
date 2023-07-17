<?php

namespace App\Http\Controllers\Admin\Event;

use App\Helpers\Helper;
use App\Helpers\SystemConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateEventRequest;
use App\Http\Requests\Home\Event\EventRequest;
use App\Models\Event\Event;
use App\Models\Province;
use App\Services\EventService;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    private EventService $eventService;

    public function __construct()
    {
        $this->eventService = new EventService;
    }

    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }

        $province = Province::all();

        $params = Helper::array_remove_null($request->all());
        $list = Event::with('bussiness.user_detail')
            ->filter($params);

        $list = $list->orderBy('id', 'desc')->paginate($items);
        $count_trash = Event::onlyIsDeleted()->count();

        return view('Admin.Event.ListEvent', compact('province', 'list', 'count_trash'));
    }

    public function trash(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }
        $list = Event::onlyIsDeleted()->paginate($items);
        return view('Admin.Event.ListTrashEvent', compact('list'));
    }

//------------------------------------------------------------------------EDIT---------------------------------------------------------------------//
    public function edit($id)
    {
        $item = Event::findOrFail($id);
        $province = Province::all();

        return view('Admin.Event.EditEvent', compact('item', 'province'));
    }

    public function update(CreateEventRequest $request, $id)
    {
        $event = Event::findOrFail($id);

        $event->fill($request->all());
        $event->start_date = strtotime(str_replace('/', '-', $request->start_date) . ' ' . $request->start_time);
        $event->updated_at = strtotime('now');
        $event->admin_id = Auth::guard('admin')->id();
        if ($request->is_highlight) {
            $serviceFee = get_fee_service(7);
            $event->highlight_end = time() + data_get($serviceFee, 'existence_time', SystemConfig::DAY_TIME * 7);
        }
        $event->update();

        $this->eventService->updateLocation($event, $request->all());

        $request->merge(['id' => $id]);
        // Helper::create_admin_log(87, $request->all());
        Toastr::success('Sửa thành công');
        return redirect()->route('admin.event.list');
    }

//------------------------------------------------------------------------DELETE---------------------------------------------------------------------//
    public function delete($id)
    {
        $ids = is_array($id) ? $id : array($id);

        Event::find($ids)
            ->each(function($event) {
                $event->delete();
                // Helper::create_admin_log(88, ['id' => $event->id, 'is_deleted' => 1]);
            });

        Toastr::success('Xóa thành công');

        return redirect(route('admin.event.list'));
    }

    public function restore($id)
    {
        $ids = is_array($id) ? $id : array($id);

        Event::onlyIsDeleted()
            ->find($ids)
            ->each(function($event) {
                $event->restore();
                // Helper::create_admin_log(89, ['id' => $event->id, 'is_deleted' => 0]);
            });

        Toastr::success('Khôi phục thành công!');
        return redirect()->back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Event::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                // maybe should remove user upload file not admin
                $item->forceDelete();
                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }

//------------------------------------------------------------------------ACTION---------------------------------------------------------------------//
    public function action(Request $request)
    {
        if ($request->select_item == null || !is_array($request->select_item)) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }
        if ($request->query('action') == "trash") {
            $this->delete(array_values($request->select_item));
        } else if ($request->query('action') == "duplicate") {
            $this->duplicate(array_values($request->select_item));
        } else if ($request->query('action') == "delete") {
            $this->force_delete(array_values($request->select_item));
        } else if ($request->query('action') == "restore") {
            $this->restore(array_values($request->select_item));
        } else if ($request->query('action') == "update") {
            for ($i = 0; $i < count($request->select_item); $i++) {
                $value = $request->show_order[$request->select_item[$i]];
//                $highlight = $request->is_highlight[$request->select_item[$i]];
                Event::where('id', $request->select_item[$i])
                    ->update(['show_order' => $value]);
            }
            Toastr::success("Thành công");
        }
        return redirect()->back();
    }

    /**
     * Change Status
     * @param $id
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change_status($id, $status)
    {
        $event = Event::findOrFail($id);

        $event->update([
            'is_confirmed' => $status
        ]);
        // Helper::create_admin_log(90, $data);

        Toastr::success('Thành công');
        return back();
    }

    /** Tooggle Hight Light
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle_highlight($id)
    {
        $event = Event::findOrFail($id);
        $event->update([
            'is_highlight' => $event->is_highlight
        ]);
        // Helper::create_admin_log(91, $data);

        Toastr::success('Thành công');
        return back();
    }

    // --------------------------------------------------ADD----------------------------------------------
    public function add()
    {
        // $item = Event::find();
        // //kiểm tra có tồn tại
        // if ($item == null) {
        //     Toastr::warning('Không tồn tại');
        //     return back();
        // }
        $province = Province::showed()->get();

        return view('Admin.Event.AddEvent', compact('province'));
    }

    public function post(EventRequest $request)
    {
        $event = new Event();
        $event->fill($request->all());
        $event->start_date = strtotime(str_replace('/', '-', $request->start_date) . ' ' . $request->start_time);
        $event->start_time = 0;
        $event->updated_at = strtotime('now');
        if ($request->is_highlight) {
            $serviceFee = get_fee_service(7);
            $event->highlight_end = time() + data_get($serviceFee, 'existence_time', SystemConfig::DAY_TIME * 7);
        }
        $event->admin_id = Auth::guard('admin')->id();
        $event->save();

        $this->eventService->createLocation($event, $request->all());

        // Helper::create_admin_log(92, $request->all());
        Toastr::success('Thêm thành công');
        return redirect()->route('admin.event.list');
    }

    public function high_light($id, $value)
    {
        $event = Event::findOrFail($id);
        $event->update([
            'is_highlight' => 1,
            'highlight_end' => strtotime(Carbon::now()->addWeek($value))
        ]);

        // Helper::create_admin_log(93, $data);
        Toastr::success('Nổi bật sự kiện thành công');
        return back();
    }

    public function unhightlight($id)
    {
        $event = Event::findOrFail($id);
        $event->update([
            'is_highlight' => 0,
            'highlight_end' => null
        ]);
        // Helper::create_admin_log(94, $data);

        Toastr::success('Hủy nổi bật sự kiện thành công');
        return back();
    }
}
