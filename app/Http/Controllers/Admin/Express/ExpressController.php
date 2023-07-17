<?php

namespace App\Http\Controllers\Admin\Express;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\BannerRequest;
use App\Models\User\UserTransaction;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Banner\Banner;
use App\Models\Banner\BannerGroup;
use App\Models\Group;

class ExpressController extends Controller
{
    /*-----------------------------------------------------LIST-------------------------------------------------------*/
    /**
     * List
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function list(Request $request){
        $items = $request->items ?? 10;

        $params = Helper::array_remove_null($request->all());
        $list = Banner::orderBy('id', 'desc')
            ->filter($params)
            ->paginate($items);
        // select dates ->where('date_to', '>=', time())
        $dates = Banner::select('date_from', 'banner.date_to')->where('date_to', '>=', time())->get();

        $isValidDates = collect([]);
        foreach ($dates as $item){
            $dates = CarbonPeriod::create(date('Y-m-d', $item->date_from), date('Y-m-d', $item->date_to));
            // Iterate over the period
            foreach ($dates as $date) {
                $isValidDates->push($date->format('Y-m-d'));
            }
        }
        $isValidDates = $isValidDates->unique()->values()->all();

        return view('Admin.Express.ListExpress', compact('list', 'isValidDates'));
    }
    /*-----------------------------------------------------ADD--------------------------------------------------------*/
    /** Add
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function add()
    {
        // Retrieve group
        $group = Group::get();

        // Retrieve banner group
        $banner_group = BannerGroup::get();

        // select dates ->where('date_to', '>=', time())
        $dates = Banner::select('date_from', 'date_to')->where('date_to', '>=', time())->get();
        $isValidDates = collect([]);
        foreach ($dates as $item){
            $dates = CarbonPeriod::create(date('Y-m-d', $item->date_from), date('Y-m-d', $item->date_to));
            // Iterate over the period
            foreach ($dates as $date) {
                $isValidDates->push($date->format('Y-m-d'));
            }

        }
        $isValidDates = $isValidDates->unique()->values()->all();

        // Return view to add
        return view('Admin.Express.AddExpress', compact('group', 'banner_group', 'isValidDates'));
    }

    public function store(){

    }
    /*-----------------------------------------------------EDIT-------------------------------------------------------*/
    /**
     * Change Status
     * @param $id
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change_status($id, $status){
        $item = UserTransaction::find($id);
        //kiểm có tồn tại
        if ($item == null) {
            Toastr::warning('Không tồn tại');
            return back();
        }

        if ($status != -1){
            $item->banner()->update([
                'is_confirm' => $status
            ]);
            $item->user_deposit()->update([
                'deposit_status' => $status,
                'one_time_confirm_token' => null
            ]);
        }else{
            $item->banner()->update([
                'date_to' => time() - 5
            ]);
        }
        $item->save();

        // refund
        $item = $item->fresh();
        if ($item->banner->is_confirm == 2){
            $item->user()->update([
                'coint_amount' =>  $item->user->coint_amount + $item->coin_amount
            ]);
        }
        // $data = ['id'=>$id, 'is_confirm' => $status, 'date_to' => time() - 5,'coint_amount' =>  $item->user->coint_amount + $item->coin_amount];
        // Helper::create_admin_log(98,$data);
        Toastr::success('Thành công');
        return back();
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function renewal($id){

        $item = UserTransaction::find($id);
        //kiểm có tồn tại
        if ($item == null) {
            Toastr::warning('Không tồn tại');
            return back();
        }

        if ($item->banner->date_from > time()){
            Toastr::warning('Quảng cáo chưa bắt đầu');
            return back();
        }

        $start = time();
        if ($item->banner->date_to > time()){
//            $start = $item->banner->date_to;
            Toastr::warning('Quảng cáo chưa hết hạn');
            return back();
        }
        $diff  = abs($item->banner->date_to - $item->banner->date_from);

        $item->banner->update([
            'date_from' => time(),
            'date_to' => $start + $diff,
        ]);

        $item->banner()->update([
           'is_confirm' => 1
        ]);
        // $data =[
        // 'id'=>$id,
        //     'date_from' => time(),
        //     'date_to' => $start + $diff,
        //     'is_confirm' => 1
        // ];
        // Helper::create_admin_log(99,$data);
        Toastr::success('Thành công');
        return back();
    }

    public function renewal_ajax(Request $request, $id){

        $item = UserTransaction::find($id);
        //kiểm có tồn tại
        if ($item == null) {
            return response()->json([
               'status' => 'not found'
            ], 404);
        }

        if ($item->banner->date_from > time()){
            return response()->json([
                'status' => 'Quảng cáo chưa bắt đầu'
            ], 403);
        }

        $start = time();
        if ($item->banner->date_to > time()){
            return response()->json([
                'status' => 'Quảng cáo chưa hết hạn'
            ], 403);
        }

        // Check date
        if ($request->date) {
            $date_array = explode('-', $request->date);
            try {
                $item->banner->update([
                    'date_from' => strtotime(trim(str_replace('/', '-', $date_array[0]))),
                    'date_to' => strtotime(trim(str_replace('/', '-', $date_array[1]))),
                ]);
            }catch (\Exception $exception){
                return response()->json([
                    'status' => 'Ngày không hợp lệ'
                ], 500);
            }


            $item->banner()->update([
                'is_confirm' => 1
            ]);
            // $data = [
            //     'id'=>$id,
            //     'date_from' => strtotime(trim(str_replace('/', '-', $date_array[0]))),
            //     'date_to' => strtotime(trim(str_replace('/', '-', $date_array[1]))),
            //     'is_confirm' => 1

            // ];
            // Helper::create_admin_log(99,$data);
            return response()->json([
                'status' => 'Gia hạn quảng cáo thành công'
            ], 200);
        }else{
            return response()->json([
                'status' => 'Ngày bắt đầu và ngày kết thúc phải được chọn'
            ], 403);
        }
    }

    /**
     * Show edit view
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id){
        $item = UserTransaction::find($id)->banner;
        if ($item == null) {
            Toastr::warning('Không tồn tại');
            return back();
        }
        $banner_group = BannerGroup::get();
        $group = Group::get();

        return view('Admin.Express.EditExpress', compact('item', 'banner_group', 'group'));
    }


    /** Update
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(BannerRequest $request, int $id)
    {
        $banner = Banner::findOrFail($id);

        $data = [
            'banner_group_id' => $request->banner_group_id,
            'group_id' => $request->group_id,
            'banner_title' => $request->banner_title,
            'image_url' => $request->image_url,
            'link' => $request->link,
            'target_type' => $request->target_type,
            'banner_default' => 0,
            'updated_by' => Auth::guard('admin')->id(),
            'updated_at' => strtotime('now'),
            'date_from' => null,
            'date_to' => null,
        ];
        // Check date
        if ($request->date){
            $date_array = explode('-', $request->date);
            $data['date_from'] = strtotime(Carbon::createFromFormat('d-m-Y g:ia', trim(str_replace('/', '-', $date_array[0])))->toDateTimeString());
            $data['date_to'] = strtotime(Carbon::createFromFormat('d-m-Y g:ia', trim(str_replace('/', '-', $date_array[1])))->toDateTimeString());
        }

        $banner->update($data);
        // $data['id'] = $id;
        // Helper::create_admin_log(100,$data);
        // Notify
        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.express.list'));
    }


}
