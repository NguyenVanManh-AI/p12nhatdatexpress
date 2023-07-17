<?php

namespace App\Http\Controllers\Admin\Project;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Project\AddProjectRequest;
use App\Http\Requests\Admin\Project\EditProjectRequest;

use App\Http\Requests\Admin\Project\PreviewProjectRequest;
use App\Models\Admin\ProjectLocation;
use App\Models\Direction;
use App\Models\District;
use App\Models\Furniture;
use App\Models\Group;
use App\Models\Progress;
use App\Models\Project;
use App\Models\ProjectParam;
use App\Models\Property;
use App\Models\Province;
use App\Models\Unit;
use App\Models\Utility;
use App\Traits\Filterable;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    use Filterable;

    protected $id_group = 34;
    protected $filterable = [
        'keyword' => 'project_name',
        'group_id',
        'created_at',
        'created_by',
        'new_comment',
        'new_notify',
    ];
    protected $table = 'project';

    protected function filterKeyword($query, $value)
    {
        return $query->where($this->table . '.' . 'project_name', 'like', "%$value%");
    }

    protected function filterNewComment($query, $value)
    {
        return $query->has('new_comments');
    }

    protected function filterNewNotify($query, $value)
    {
        return $query->has('report_pending');
    }

    protected function filterCreatedAt($query, $value)
    {
        if (key_exists('date_start', $value)) {
            $date_start = strtotime($value['date_start']);
            $query = $query->where($this->table . '.' . 'created_at', '>=', $date_start);
        }
        if (key_exists('date_end', $value)) {
            $end_of_date = Carbon::createFromFormat('Y-m-d', $value['date_end'])->endOfDay()->toDateTimeString();
            $date_end = strtotime($end_of_date);
            $query = $query->where($this->table . '.' . 'created_at', '<=', $date_end);
        }
        return $query;
    }

    public function objectToArray($input): array
    {
        $new = [];
        foreach ($input as $item) {
            if (is_object($item) || is_array($item)) {
                $new[$item['name']] = $item['value'];
            }
        }
        return (array)$new;
    }

    public function array_key_lookup($haystack, $needle)
    {
        $matches = preg_grep("/$needle/", array_keys($haystack));

        return array_intersect_key($haystack, array_flip($matches));
    }

    //-------------------------------------------------------------------------LIST-------------------------------------------------------------------------//
    public function list(Request $request)
    {
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }
        $list_project = Project::query();
        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $list_project = $list_project->select($this->table . '.*', 'admin.rol_id')
                    ->join('admin', $this->table . '.created_by', '=', 'admin.id')
                    ->where(['rol_id' => $admin_role_id]);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $list_project = $list_project->where([$this->table . '.created_by' => $admin_id]);
            }
        }
        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $list_project = $this->scopeFilter($list_project, $params);

        $count_trash = Project::onlyIsDeleted()->count();

        $category = Group::select('id', 'group_name')
            ->where('parent_id', $this->id_group)
            ->get();
        $list_project = $list_project->withCount('total_new_comments')
            ->withCount('report_pending')->orderBy('id', 'desc')->paginate($items);

        $creator = Project::select(['admin.id', 'admin.admin_fullname'])->join('admin', 'project.created_by', '=', 'admin.id')->groupBy('admin.id', 'admin.admin_fullname')->get();
        return view('Admin.Project.ListProject', compact('list_project', 'count_trash', 'category', 'creator'));
    }

    public function trash(Request $request)
    {
        //lấy data phân trang
        $items = 10;
        if ($request->has('items') && is_numeric($request->items)) {
            $items = $request->items;
        }
        $trash = Project::onlyIsDeleted();
        if (Auth::guard('admin')->user()->admin_type != 1) {
            // check request_list_scope
            if ($request->request_list_scope == 2) { // group
                $admin_role_id = Auth::guard('admin')->user()->rol_id;
                $trash = $trash->select($this->table . '.*', 'admin.rol_id')
                    ->join('admin', $this->table . '.created_by', '=', 'admin.id')
                    ->where(['rol_id' => $admin_role_id]);
            } else if ($request->request_list_scope == 3) { //self
                $admin_id = Auth::guard('admin')->user()->id;
                $trash = $trash->where([$this->table . '.created_by' => $admin_id]);
            }
        }

        $params = Helper::array_remove_null($request->all());
        $params = array_filter($params);
        $trash = $this->scopeFilter($trash, $params);

        $trash = $trash->orderBy('id', 'desc')->paginate($items);
        return view('Admin.Project.TrashProject', compact('trash'));
    }

    public function trash_item($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();


        // $data = [
        //     'id' => $id,
        //     'is_deleted' => 1
        // ];
        // Helper::create_admin_log(130, $data);

        Toastr::success(' Xóa thành công');
        return back();
    }

    public function untrash_item($id)
    {
        $project = Project::onlyIsDeleted()->findOrFail($id);
        $project->restore();

        // Helper::create_admin_log(131, $data);
        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function trash_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $project = Project::find($item);
            if (!$project) continue;

            $project->delete();
            // Helper::create_admin_log(130, $data);
        }

        Toastr::success('Xóa thành công');
        return back();
    }

    public function preview(PreviewProjectRequest $request)
    {
        $data = $request->data;
        $data = self::objectToArray($data);
        $project = new Project();
        $project->fill($data);
        $project->bank_sponsor = $data['bank_sponsor'] ?? 0;
        $project->created_by = Auth::guard('admin')->id();
        $project->created_at = strtotime('now');
        $project->area_unit_id = 7; // dien_tich (m2)
        $project->project_road_unit = 9; //road_unit (m)
        $project->project_unit_id = 8; //quy_mo (ha)
        $project->list_utility = array_values(self::array_key_lookup($data, 'list_utility+'));
        // Check have image
        $image_array = [];
        if (json_decode($data['image_url'])) {
            $image_array = json_decode($data['image_url']);
        } else $image_array[] = $data['image_url'];

        if ($data['image_url_order'] != null) {
            $list_order = json_decode($data['image_url_order']);
            $new_image_list = [];
            foreach ($list_order as $value) {
                $new_image_list[] = $image_array[$value];
            }
            $image_array = $new_image_list;
        }
        $project->image_url = json_encode($image_array);
        $project->admin = Auth::guard('admin')->user();
        $project->unit_sell = Unit::where('id', $project->price_unit_id)->first();
        $project->unit_rent = Unit::where('id', $project->project_unit_rent_id)->first();
        $project->unit_area = Unit::where('id', $project->area_unit_id)->first();
        $project->unit_road = Unit::where('id', $project->project_road_unit)->first();
        $project->unit_scale = Unit::where('id', $project->project_unit_id)->first();
        $project->group = Group::where('id', $project->group_id)->first();
        $project->progress = Progress::where('id', $project->project_progress)->first();
        $project->legal = ProjectParam::where('id', $project->project_juridical)->first();
        $project->furniture = Furniture::where('id', $project->project_furniture)->first();
        $project->direction = Direction::where('id', $project->project_direction)->first();
        $project->location = new \stdClass();
        $project->location->map_latitude = $data['map_latitude'];
        $project->location->map_longtitude = $data['map_longtitude'];
        $project->location->province = Province::where('id', $data['province_id'])->first();
        $project->location->district = District::where('id', $data['district_id'])->first();
        $is_preview = true;
        $get_utility_list = function () use ($project) {
            return Utility::showed()->whereIn('id', $project->list_utility)->get()->values()->all();
        };

        $properties = Property::where('id', '<', 16)->orderBy('id', 'asc')->get(['id', 'name']);
        header('Content-Type: application/html');
        $html = view('components.home.project.content-project', compact('project', 'properties', 'is_preview', 'get_utility_list'))->render();
//        $html = trim(preg_replace('/\r\n/', ' ', $html));
        return response($html);
    }

    public function view(Request $request)
    {
        $project = Project::findOrFail($request->id);
        $properties = Property::where('id', '<', 16)->orderBy('id', 'asc')->get(['id', 'name']);
        $is_preview = true;
        $get_utility_list = function () use ($project) {
            $utility_list = json_decode($project->list_utility);
            if (is_array($utility_list) && count($utility_list) > 0)
                return DB::table('utility')->whereIn('id', $utility_list)->get()->values()->all();
            else
                return [];
        };
        header('Content-Type: application/html');
        $html = view('components.home.project.content-project', compact('project', 'properties', 'is_preview', 'get_utility_list'))->render();
        return response($html);
    }

    //------------------------------------------------------------------------CREATE------------------------------------------------------------------------//
    public function add()
    {
        $category = Group::select('id', 'group_name')
            ->where('parent_id', $this->id_group)
            ->get();
        $province = Province::showed()->orderBy('province_type', 'desc')->get();
        $direction = Direction::showed()->get(['id', 'direction_name']);
        $unit_rent = Unit::showed()->where(['unit_type' => 'C', ['unit_name', 'like', '%/tháng']])->get(['id', 'unit_code', 'unit_name']);
        $unit_sell = Unit::showed()->where(['unit_type' => 'C'])
            ->whereNotIn('id', $unit_rent->pluck('id')->toArray())
            ->get(['id', 'unit_code', 'unit_name']);
        $legal = ProjectParam::showed()->where('param_type', 'L')->get(['id', 'param_code', 'param_name']);
        $utility = Utility::showed()->get(['id', 'utility_name', 'image_url']);
        $properties = Property::where('properties_type', 1)->orderBy('id', 'asc')->get();

        return view('Admin.Project.AddProject',
            compact('category', 'province', 'direction', 'unit_sell', 'unit_rent', 'legal', 'utility', 'properties'));
    }

    public function store(AddProjectRequest $request)
    {
        // Create a new Project
        $project = new Project();
        $project->fill($request->all());
        $project->bank_sponsor = $request->bank_sponsor ?? 0;
        $project->created_by = Auth::guard('admin')->id();
        $project->created_at = strtotime('now');
        $project->area_unit_id = 7; // dien_tich (m2)
        $project->project_road_unit = 9; //road_unit (m)
        $project->project_unit_id = 8; //quy_mo (ha)
        $project->list_utility = is_array($project->list_utility) ? json_encode(collect($project->list_utility)->keys()) : null;
        // Check have image
        if ($request->has('image_url')) {
            $image_array = [];
            if (json_decode($request->image_url)) {
                $image_array = json_decode($request->image_url);
            } else $image_array[] = $request->image_url;

            if ($request->image_url_order != null) {
                $list_order = json_decode($request->image_url_order);
                $new_image_list = [];
                foreach ($list_order as $value) {
                    $new_image_list[] = $image_array[$value];
                }
                $image_array = $new_image_list;
            }
            $project->image_url = json_encode($image_array);
            $project->image_thumbnail = $image_array[0];
        }
        $project->save();

        $projectLocation = new ProjectLocation();
        $projectLocation->fill($request->all());
        $projectLocation->project_id = $project->id;
        $projectLocation->save();
        // Helper::create_admin_log(55, $request->all());

        Toastr::success('Thêm mới dự án thành công');
        return redirect(route('admin.project.list'));
    }

    //------------------------------------------------------------------------EDIT------------------------------------------------------------------------//
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        $category = Group::select('id', 'group_name')
            ->where('parent_id', $this->id_group)
            ->get();

        $province = Province::showed()->orderBy('province_type', 'desc')->get();
        $direction = Direction::showed()->get(['id', 'direction_name']);
        $unit_rent = Unit::showed()->where(['unit_type' => 'C', ['unit_name', 'like', '%/tháng']])->get(['id', 'unit_code', 'unit_name']);
        $unit_sell = Unit::showed()->where(['unit_type' => 'C'])
            ->whereNotIn('id', $unit_rent->pluck('id')->toArray())
            ->get(['id', 'unit_code', 'unit_name']);
        $legal = ProjectParam::showed()->where('param_type', 'L')->get(['id', 'param_code', 'param_name']);
        $utility = Utility::showed()->get(['id', 'utility_name', 'image_url']);
        $properties = Property::where('properties_type', 1)->orderBy('id', 'asc')->get();

        return view('Admin.Project.EditProject',
            compact('category', 'province', 'direction', 'unit_sell', 'unit_rent', 'legal', 'utility', 'project', 'properties'));
    }

    public function update(EditProjectRequest $request, $id)
    {
        // Create a new Project
        $project = Project::findOrFail($id);
        $project->fill($request->all());
        $project->updated_by = Auth::guard('admin')->id();
        $project->updated_at = strtotime('now');
        $project->bank_sponsor = $request->bank_sponsor ?? 0;
        $project->area_unit_id = 7; // dien_tich (m2)
        $project->project_road_unit = 9; //road_unit (m)
        $project->project_unit_id = 8; //quy_mo (ha)
        $project->list_utility = is_array($project->list_utility) ? json_encode(collect($project->list_utility)->keys()) : null;

        if ($request->has('image_url')) {
            $image_array = [];
            if (json_decode($request->image_url)) {
                $image_array = json_decode($request->image_url);
            } else $image_array[] = $request->image_url;

            if ($request->image_url_order != null) {
                $list_order = json_decode($request->image_url_order);
                $new_image_list = [];
                foreach ($list_order as $value) {
                    $new_image_list[] = $image_array[$value];
                }
                $image_array = $new_image_list;
            }
            $project->image_url = json_encode($image_array);
            $project->image_thumbnail = $image_array[0];
        }
        $project->update();

        $projectLocation = $project->location;
        $projectLocation->fill($request->all());
        $projectLocation->project_id = $project->id;
        $projectLocation->update();
        $request->merge(['id' => $id]);
        // Helper::create_admin_log(129, $request->all());
        Toastr::success('Cập nhật thành công');
        return redirect(route('admin.project.list'));

    }

    public function untrash_list(Request $request)
    {
        if ($request->select_item == null) {
            Toastr::warning("Vui lòng chọn");
            return back();
        }

        foreach ($request->select_item as $item) {
            $project = Project::onlyIsDeleted()->find($item);
            if (!$project) continue;

            $project->restore();
            // Helper::create_admin_log(131, $data);
        }

        Toastr::success('Khôi phục thành công');
        return back();
    }

    public function forceDeleteMultiple(Request $request)
    {
        $ids = is_array($request->ids) ? $request->ids : explode(',', $request->ids);

        Project::onlyIsDeleted()
            ->find($ids)
            ->each(function($item) {
                $item->forceDelete();

                // should create log force delete
            });

        Toastr::success('Xóa thành công');
        return back();
    }

}

