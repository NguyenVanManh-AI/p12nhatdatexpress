<?php

namespace App\Http\Controllers\Admin\Project;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Progress;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;

class UpdateManageController extends Controller
{
    private const PROGRESS = 1;
    private const RENT = 2;
    private const SELL = 3;

    //-----------------------------------------------PRICE RENT-------------------------------------------------------//
    # List Update Price Rent
    public function update_price_rent(Request $request)
    {
        $items = $request->items ?? 10;

        $listUpdateQuery = Project::query()
            ->whereNotNull('update_rent_price')
            ->orderby('project.id', 'desc')
            ->select('project.id', 'project.project_name', 'project.project_rent_price', 'project.update_rent_price');

        if ($request->request_list_scope == 2) { // group
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $listUpdateQuery = $listUpdateQuery
                ->join('admin', 'project.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id);
        } else if ($request->request_list_scope == 3) { //self
            $admin_id = Auth::guard('admin')->user()->id;

            $listUpdateQuery = $listUpdateQuery->where(['project.created_by' => $admin_id]);
        }

        if ($request->name_project) {
            $listUpdateQuery->where('project.project_name', 'like', '%' . $request->name_project . '%');
        }

        $listUpdate = $listUpdateQuery->get();

        $result = $listUpdate->map(function ($item){
            $unserialize_array = collect(unserialize($item->update_rent_price));
            return $unserialize_array->map(function ($t, $k) use ($item){

                try {
                    return [
                        'id' => $item->id,
                        'project_name' => $item->project_name,
                        'project_price_new' => $t['price'],
                        'num' => $t['num'],
                        'date' => $t['date'],
                        'confirm' => $t['confirm'],
                        'price_old' => $t['price_old'],
                        'change_date' => $t['change_date'] ?? null,
                        'index' => $k,
                    ];
                }catch (\Exception $exception){
//                    dd($t, $item);
                }
            });
        })->flatten(1)->sortBy('confirm');

        if ($request->from_date || $request->to_date) {
            $result = $result->filter(function ($item) use ($request){
                if ($request->from_date)
                    return $item['change_date'] >= strtotime($request->from_date);

                if ($request->to_date)
                    return $item['change_date'] >= strtotime($request->to_date) + 86400;

            });
        }

        $listUpdate = new \Illuminate\Pagination\LengthAwarePaginator(
            $result->forPage($request->page, $items),
            $result->count(),
            $items,
            $request->page,
            ['path' => route('admin.project.update-price-rent'), 'pageName' => "page"]
        );

        $countUpdateProgress = $this->get_pending_count(self::PROGRESS);
        $countUpdatePriceRent = $this->get_pending_count(self::RENT);
        $countUpdatePrice = $this->get_pending_count(self::SELL);

        return view('Admin/Project/UpdatePriceRent', ['listUpdate' => $listUpdate, 'countUpdateProgress' => $countUpdateProgress, 'countUpdatePrice' => $countUpdatePrice, 'countUpdatePriceRent' => $countUpdatePriceRent]);
    }

    # Confirm Update Price Rent
    public function confirm_update_price_rent($id_project, $index): RedirectResponse
    {
        $project = Project::findOrFail($id_project);

            $unserialized_array = unserialize($project->update_rent_price);
            $unserialized_array[$index]['confirm'] = 1;
            $unserialized_array[$index]['change_date'] = time();
            $serialized_array = serialize($unserialized_array);
            $data = [
                'update_rent_price' => $serialized_array,
                'project_rent_price'=>$unserialized_array[$index]['price'],
                'project_rent_price_old'=>$unserialized_array[$index]['price_old'],
            ];

            $project->update($data);
            // Helper::create_admin_log(138, array_merge([
            //     'id_project'=>$id_project,
            //     'index'=>$index
            // ], $data));

        Toastr::success('Xác thực thành công');
        return back();
    }

    # Cancel Update Price Rent
    public function recover_update_price_rent($id_project, $index): RedirectResponse
    {
        if (isset($id_project) && isset($index)) {
            $project = Project::findOrFail($id_project);

            $unserialized_array = unserialize($project->update_rent_price);
            $unserialized_array[$index]['confirm'] = 2;
            $unserialized_array[$index]['change_date'] = time();
            $serialized_array = serialize($unserialized_array);
            $data = [
                'update_rent_price' => $serialized_array,
                'project_rent_price' => $project->project_rent_price_old,
                'project_rent_price_old' => $project->project_rent_price,
            ];

            $project->update($data);

            // Helper::create_admin_log(139, array_merge([
            //     'id_project'=>$id_project,
            //     'index'=>$index
            // ], $data));
            Toastr::success('Hoàn tác thành công');
        }

        return back();
    }

    //-----------------------------------------------PRICE SELL-------------------------------------------------------//
    # List Update Price Sell
    public function update_price(Request $request)
    {
        $items = $request->items ?? 10;

        $listUpdateQuery = Project::query()
            ->whereNotNull('update_price')
            ->orderby('project.id', 'desc')
            ->select('project.id', 'project.project_name', 'project.project_price', 'project.update_price', 'project_url');

        if ($request->request_list_scope == 2) { // group
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $listUpdateQuery = $listUpdateQuery
                ->join('admin', 'project.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id);
        } else if ($request->request_list_scope == 3) { //self
            $admin_id = Auth::guard('admin')->user()->id;
            $listUpdateQuery = $listUpdateQuery->where(['created_by' => $admin_id]);

        }

        if ($request->name_project) {
            $listUpdateQuery->where('project.project_name', 'like', '%' . $request->name_project . '%');
        }

        $listUpdate = $listUpdateQuery->get();
        $result = $listUpdate->map(function ($item){
            $unserialize_array = collect(unserialize($item->update_price));
            return $unserialize_array->map(function ($t, $k) use ($item){

                try {
                    return [
                        'id' => $item->id,
                        'project_name' => $item->project_name,
                        'project_url' => $item->project_url,
                        'project_price_new' => $t['price'],
                        'num' => $t['num'],
                        'date' => $t['date'],
                        'confirm' => $t['confirm'],
                        'price_old' => $t['price_old'],
                        'change_date' => $t['change_date'] ?? null,
                        'index' => $k,
                    ];
                }catch (\Exception $exception){
//                    dd($t, $item);
                }
            });
        })->flatten(1)->sortBy('confirm');

        if ($request->from_date || $request->to_date) {
            $result = $result->filter(function ($item) use ($request){
                if ($request->from_date)
                    return $item['change_date'] >= strtotime($request->from_date);

                if ($request->to_date)
                    return $item['change_date'] >= strtotime($request->to_date) + 86400;

            });
        }

        $listUpdate = new \Illuminate\Pagination\LengthAwarePaginator(
            $result->forPage($request->page, $items),
            $result->count(),
            $items,
            $request->page,
            ['path' => route('admin.project.update-price'), 'pageName' => "page"]
        );

        $countUpdateProgress = $this->get_pending_count(self::PROGRESS);
        $countUpdatePriceRent = $this->get_pending_count(self::RENT);
        $countUpdatePrice = $this->get_pending_count(self::SELL);

        return view('Admin/Project/UpdatePrice', ['listUpdate' => $listUpdate, 'countUpdateProgress' => $countUpdateProgress, 'countUpdatePrice' => $countUpdatePrice, 'countUpdatePriceRent' => $countUpdatePriceRent]);
    }

    # Confirm Update Price Sell
    public function confirm_update_price($id_project, $index): RedirectResponse
    {
        $project = Project::findOrFail($id_project);

            $unserialized_array = unserialize($project->update_price);
            $unserialized_array[$index]['confirm'] = 1;
            $unserialized_array[$index]['change_date'] = time();
            $serialized_array = serialize($unserialized_array);
            $data = [
                'update_price' => $serialized_array,
                'project_price'=>$unserialized_array[$index]['price'],
                'project_price_old'=>$unserialized_array[$index]['price_old'],
            ];

        $project->update($data);
            // Helper::create_admin_log(138, array_merge([
            //     'id_project'=>$id_project,
            //     'index'=>$index
            // ], $data));

        Toastr::success('Xác thực thành công');
        return back();
    }

    # Cancel Update Price Sell
    public function recover_update_price($id_project, $index): RedirectResponse
    {
        $project = Project::findOrFail($id_project);

            $unserialized_array = unserialize($project->update_price);
            $unserialized_array[$index]['confirm'] = 2;
            $unserialized_array[$index]['change_date'] = time();
            $serialized_array = serialize($unserialized_array);
            $data = [
                'update_price' => $serialized_array,
                'project_price'=> $project->project_price_old,
                'project_price_old'=> $project->project_price,
            ];

        $project->update($data);
        // Helper::create_admin_log(139, array_merge([
        //     'id_project'=>$id_project,
        //     'index'=>$index
        // ], $data));

        Toastr::success('Hoàn tác thành công');
        return back();
    }

    //-----------------------------------------------PROGRESS---------------------------------------------------------//
    # List Update Progress
    public function update_manage(Request $request)
    {
        // $list_progress = [
        //     [
        //         'id_progress' => 2,
        //         'num' => 3,
        //         'date' => 1941285621,
        //         'confirm' => 0,
        //         'id_progress_old' => 1,
        //     ],
        //     [
        //         'id_progress' => 2,
        //         'num' => 28,
        //         'date' => 1941285655,
        //         'confirm' => 0,
        //         'id_progress_old' => 1,
        //     ]
        // ];
        $items = $request->items ?? 10;

        $listUpdateQuery = Project::query()
            ->join('progress', 'progress.id', 'project.project_progress')
            ->where('update_progress', '!=', null)
            ->orderby('project.id', 'desc')
            ->select('project.id', 'project.project_name', 'progress.progress_name', 'project.update_progress', 'project_url');

        if ($request->request_list_scope == 2) { // group
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $listUpdateQuery = $listUpdateQuery
                ->join('admin', 'project.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id);
        } else if ($request->request_list_scope == 3) { //self
            $admin_id = Auth::guard('admin')->user()->id;

            $listUpdateQuery = $listUpdateQuery->where(['project.created_by' => $admin_id]);
        }

        if ($request->name_project) {
            $listUpdateQuery->where('project.project_name', 'like', '%' . $request->name_project . '%');
        }

        $listUpdate = $listUpdateQuery->get();
        $listProgress = collect(Progress::select('id', 'progress_name')->get()->toArray());

        $result = $listUpdate->map(function ($item) use ($listProgress){
            $unserialize_array = collect(unserialize($item->update_progress));
            return $unserialize_array->map(function ($t, $k) use ($item, $listProgress){
                try {
                    return [
                        'id' => $item->id,
                        'project_name' => $item->project_name,
                        'project_url' => $item->project_url,
                        'progress_name' => $item->progress_name,
                        'progress_update' => optional($listProgress->where('id', $t['id_progress'])->first())['progress_name'],
                        'progress_update_old' => optional($listProgress->where('id', $t['id_progress_old'])->first())['progress_name'],
                        'num' => $t['num'],
                        'date' => $t['date'],
                        'confirm' => $t['confirm'],
                        'id_progress_old' => $t['id_progress_old'],
                        'change_date' => $t['change_date'] ?? null,
                        'index' => $k,
                    ];
                }catch (\Exception $exception){
//                    dd($t, $item);
                }
            });
        })->flatten(1)->sortBy('confirm');

        if ($request->from_date || $request->to_date) {
            $result = $result->filter(function ($item) use ($request){
               if ($request->from_date)
                   return $item['change_date'] >= strtotime($request->from_date);

                if ($request->to_date)
                   return $item['change_date'] >= strtotime($request->to_date) + 86400;

            });
        }

        $listUpdate = new \Illuminate\Pagination\LengthAwarePaginator(
            $result->forPage($request->page, $items),
            $result->count(),
            $items,
            $request->page,
            ['path' => route('admin.project.update-manage'), 'pageName' => "page"]
        );

        $countUpdateProgress = $this->get_pending_count(self::PROGRESS);
        $countUpdatePriceRent = $this->get_pending_count(self::RENT);
        $countUpdatePrice = $this->get_pending_count(self::SELL);

        return view('Admin/Project/UpdateManage', ['listUpdate' => $listUpdate, 'countUpdateProgress' => $countUpdateProgress, 'countUpdatePrice' => $countUpdatePrice, 'countUpdatePriceRent' => $countUpdatePriceRent]);
    }

    # Confirm Update Progress
    public function confirm_update_manage($id_project, $index): RedirectResponse
    {
        $project = Project::findOrFail($id_project);

        $unserialized_array = unserialize($project->update_progress);
            $unserialized_array[$index]['confirm'] = 1;
            $unserialized_array[$index]['change_date'] = time();
            $serialized_array = serialize($unserialized_array);
            $data =  [
                'update_progress' => $serialized_array,
                'project_progress'=>$unserialized_array[$index]['id_progress'],
                'project_progress_old'=>$unserialized_array[$index]['id_progress_old'],
            ];

        $project->update($data);
        // Helper::create_admin_log(138, array_merge([
        //     'id_project' => $id_project,
        //     'index' => $index
        // ], $data));

        Toastr::success('Xác thực thành công');
        return back();
    }

    # Cancel Update Progress
    public function recover_update_manage($id_project, $index): RedirectResponse
    {
        $project = Project::findOrFail($id_project);

        $unserialized_array = unserialize($project->update_progress);
            $unserialized_array[$index]['confirm'] = 2;
            $unserialized_array[$index]['change_date'] = time();
            $serialized_array = serialize($unserialized_array);
            $data = [
                'update_progress' => $serialized_array,
                'project_progress'=> $project->project_progress_old,
                'project_progress_old'=> $project->project_progress,
            ];

        $project->update($data);
        // Helper::create_admin_log(139, array_merge([
        //     'id_project'=>$id_project,
        //     'index'=>$index
        // ], $data));

        Toastr::success('Hoàn tác thành công');
        return back();
    }

    //-------------------------------------------------SUPPORT METHOD-------------------------------------------------//
    # Get pending count
    public function get_pending_count($type): int
    {
        $count = 0;
        $query = Project::query();

        switch ($type){
            case self::PROGRESS:
                $result = $query->select('update_progress as list')->whereNotNull('update_progress')->get();
                $this->calculate_request_update($result, $count);
                break;

            case self::RENT:
                $result = $query->select('update_rent_price as list')->whereNotNull('update_rent_price')->get();
                $this->calculate_request_update($result, $count);
                break;

            case self::SELL:
                $result = $query->select('update_price as list')->whereNotNull('update_price')->get();
                $this->calculate_request_update($result, $count);
                break;
        }
        return $count;
    }

    # Calculate number of request update project
    private function calculate_request_update($data, &$count) : void
    {

        try {
            $count = $data->map(function ($item) use ($count){
               return $count = collect(unserialize($item->list))->where('confirm', 0)->count();
            })->sum();

        }catch (\Exception $exception){
            // Toastr::error("Xảy ra lỗi không xác định");
        }

    }
}

