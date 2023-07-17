<?php

namespace App\Http\Controllers\Admin\FocusNews;

use App\CPU\HelperImage;
use App\Helpers\CollectionHelper;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Services\GroupService;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FocusNewsController extends Controller
{
    private GroupService $groupService;

    public function __construct()
    {
        $this->groupService = new GroupService;
    }

    protected $var_array = array(47);
    public function index(Request $request)
    {
        $items = 10;
       //phân quyền
        //group
        if($request->request_list_scope == 2){
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $news = News::query()
                ->join('admin', 'news.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id)
                ->leftJoin('group','news.group_id','=','group.id')
                ->leftJoin('group as group1','group.parent_id','group1.id')
                ->select('group.id as group_child_id','group.group_name', 'group.group_url','group1.id as group_parent_id','group.is_deleted as group_deleted','news.id','news.image_url','news.num_view','news.news_title','news.news_content','news.news_url','admin.id as admin_id','admin.admin_fullname','admin.is_deleted as admin_deleted','news.is_highlight','news.highlight_to','news.is_express','news.is_deleted','news.created_at','news.created_by')
                ->orderBy('news.id','desc')
                ->get();
        }
        elseif($request->request_list_scope == 3){
            $admin_id = Auth::guard('admin')->user()->id;
            $news = News::query()
                ->leftJoin('admin','news.created_by','=','admin.id')
                ->leftJoin('group','news.group_id','=','group.id')
                ->leftJoin('group as group1','group.parent_id','group1.id')
                ->select('group.id as group_child_id','group.group_name', 'group.group_url', 'group1.id as group_parent_id','group.is_deleted as group_deleted','news.id','news.image_url','news.num_view','news.news_title','news.news_content','news.news_url','admin.id as admin_id','admin.admin_fullname','admin.is_deleted as admin_deleted','news.is_highlight','news.highlight_to','news.is_express','news.is_deleted','news.created_at','news.created_by')
                ->where('news.created_by',$admin_id)
                ->orderBy('news.id','desc')
                ->get();
        }
        //all
        else{
            $news = News::query()
                ->leftJoin('admin','news.created_by','=','admin.id')
                ->leftJoin('group','news.group_id','=','group.id')
                ->leftJoin('group as group1','group.parent_id','group1.id')
                ->select('group.id as group_child_id','group.group_name','group.group_url', 'group1.id as group_parent_id','group.is_deleted as group_deleted','news.id','news.image_url','news.num_view','news.news_title','news.news_content','news.news_url','admin.id as admin_id','admin.admin_fullname','admin.is_deleted as admin_deleted','news.is_highlight','news.highlight_to','news.is_express','news.is_deleted','news.created_at','news.created_by')
                ->orderBy('news.id','desc')
                ->get();
        }

        $count_trash = News::onlyIsDeleted()->count();

      //tìm kiếm
      if($request->has('key') &&  $request->key!=""){
          $key = $request->key;
          $news =collect($news)->filter(function ($item) use ($key){
              return false !== stristr($item->news_title,$key);
          });
      }
      if(($request->has('author'))&& $request->author !=""){
       $news =  $news->where('created_by','=',$request->author);
      }
      if(($request->has('group')) && $request->group!=""){
          $key = $request->group;
//          dd($news);
          $news =collect($news)->filter(function ($item) use ($key){
              return stristr($item->group_child_id,$key)||stristr($item->group_parent_id,$key);
          });
      }
      if(($request->has('news_type')) && $request->news_type!=""){
          // bài viết thường
            if ($request->news_type == 0){
                     $news =$news->where('is_express','=',0);
                     $news =$news->where('highlight_to','<',time());
            }
            // bài viết nổi bật
            if($request->news_type == 1){
//                $news->where('news.is_highlight','=','1')
                $news =$news->where('news.highlight_to','>',time());
            }
            //bài viết quản cáo
            if($request->news_type ==2){
                $news =$news->where('is_express','=',1);
            }
      }
        if(($request->has('start_day') && $request->start_day != "")||($request->has('end_day') && $request->end_day != "")  ){
            if($request->start_day==""){
                $start = strtotime(Carbon::parse($request->end_day));
                $end = strtotime(Carbon::parse($request->end_day)->addDay(1));
            }
            else if($request->end_day==""){
                $start = strtotime(Carbon::parse($request->start_day));
                $end = strtotime(Carbon::parse($request->start_day)->addDay(1));
            }
            else {
                $start = strtotime(Carbon::parse($request->start_day));
                $end = strtotime(Carbon::parse($request->end_day)->addDay(1));
            }
           $news= $news->where('created_at','>=',$start);
           $news= $news->where('created_at','<=',$end);
        }

      if($request->has('items')){
          $items = $request->items;
      }
      //Danh sách tác giả
      $author = DB::table('admin')
          ->join('news','admin.id','news.created_by')
          ->select('admin.id','admin.admin_fullname')
          ->get();
      $author =$author->unique('id');
      //Danh sách danh mục

        $group = new Collection([]);
        foreach ($this->var_array as $item){
            $test =$this->groupService->getChildren($item);
            $group = $group->merge($test);
        }
        $parent_group = new Collection();
        $parent_group = $this->groupService->prepareData($group);

        $group = $parent_group;
        $news = CollectionHelper::paginate($news,$items);
        return view('Admin.FocusNews.ListPosts',compact('news','count_trash','author','group'));
    }

    public function list_trash(Request $request)
    {
        $items = 10;
        //phân quyền
        //group
        if($request->request_list_scope == 2){
            $admin_role_id = Auth::guard('admin')->user()->rol_id;
            $news = News::query()
                ->join('admin', 'news.created_by', '=', 'admin.id')
                ->where('admin.rol_id', $admin_role_id)
                ->leftJoin('group','news.group_id','=','group.id')
                ->leftJoin('group as group1','group.parent_id','group1.id')
                ->select('group.id as group_child_id','group.group_name','group1.id as group_parent_id','group.is_deleted as group_deleted','news.id','news.image_url','news.num_view','news.news_title','news.news_content','news.news_url','admin.id as admin_id','admin.admin_fullname','admin.is_deleted as admin_deleted','news.is_highlight','news.highlight_to','news.is_express','news.is_deleted','news.created_at','news.created_by')
                ->orderBy('news.id','desc')
                ->get();
        }
        elseif($request->request_list_scope == 3){
            $admin_id = Auth::guard('admin')->user()->id;
            $news = News::query()
                ->leftJoin('admin','news.created_by','=','admin.id')
                ->leftJoin('group','news.group_id','=','group.id')
                ->leftJoin('group as group1','group.parent_id','group1.id')
                ->select('group.id as group_child_id','group.group_name','group1.id as group_parent_id','group.is_deleted as group_deleted','news.id','news.image_url','news.num_view','news.news_title','news.news_content','news.news_url','admin.id as admin_id','admin.admin_fullname','admin.is_deleted as admin_deleted','news.is_highlight','news.highlight_to','news.is_express','news.is_deleted','news.created_at','news.created_by')
                ->where('news.created_by',$admin_id)
                ->orderBy('news.id','desc')
                ->get();
        }
        //all
        else{
            $news = News::query()
                ->leftJoin('admin','news.created_by','=','admin.id')
                ->leftJoin('group','news.group_id','=','group.id')
                ->leftJoin('group as group1','group.parent_id','group1.id')
                ->select('group.id as group_child_id','group.group_name','group1.id as group_parent_id','group.is_deleted as group_deleted','news.id','news.image_url','news.num_view','news.news_title','news.news_content','news.news_url','admin.id as admin_id','admin.admin_fullname','admin.is_deleted as admin_deleted','news.is_highlight','news.highlight_to','news.is_express','news.is_deleted','news.created_at','news.created_by')
                ->orderBy('news.id','desc')
                ->get();
        }

        // đếm thùng rác
//        dd($news);
        $count_trash = News::onlyIsDeleted()->count();
        $news = $news->onlyIsDeleted();
        //tìm kiếm
        if($request->has('key') &&  $request->key!=""){
            $key = $request->key;
            $news =collect($news)->filter(function ($item) use ($key){
                return false !== stristr($item->news_title,$key);
            });
        }
        if(($request->has('author'))&& $request->author !=""){
            $news =  $news->where('created_by','=',$request->author);
        }
        if(($request->has('group')) && $request->group!=""){
            $key = $request->group;
            $news =collect($news)->filter(function ($item) use ($key){
                return stristr($item->group_child_id,$key)||stristr($item->group_parent_id,$key);
            });
        }
        if(($request->has('news_type')) && $request->news_type!=""){
            // bài viết thường
            if ($request->news_type == 0){
                $news =$news->where('is_express','=',0);
                $news =$news->where('highlight_to','<',time());
            }
            // bài viết nổi bật
            if($request->news_type == 1){
//                $news->where('news.is_highlight','=','1')
                $news =$news->where('news.highlight_to','>',time());
            }
            //bài viết quản cáo
            if($request->news_type ==2){
                $news =$news->where('is_express','=',1);
            }
        }
        if(($request->has('start_day') && $request->start_day != "")||($request->has('end_day') && $request->end_day != "")  ){
            if($request->start_day==""){
                $start = strtotime(Carbon::parse($request->end_day));
                $end = strtotime(Carbon::parse($request->end_day)->addDay(1));
            }
            else if($request->end_day==""){
                $start = strtotime(Carbon::parse($request->start_day));
                $end = strtotime(Carbon::parse($request->start_day)->addDay(1));
            }
            else {
                $start = strtotime(Carbon::parse($request->start_day));
                $end = strtotime(Carbon::parse($request->end_day)->addDay(1));
            }
            $news= $news->where('created_at','>=',$start);
            $news= $news->where('created_at','<=',$end);
        }

        if($request->has('items')){
            $items = $request->items;
        }
        //Danh sách tác giả
        $author = DB::table('admin')
            ->join('news','admin.id','news.created_by')
            ->select('admin.id','admin.admin_fullname')
            ->get();
        $author =$author->unique('id');

        $group = new Collection([]);
        foreach ($this->var_array as $item){
            $test =$this->groupService->getChildren($item);
            $group = $group->merge($test);
        }
        $parent_group = new Collection();
        $parent_group = $this->groupService->prepareData($group);

        $group = $parent_group;
        $news = CollectionHelper::paginate($news,$items);
        return view('Admin.FocusNews.TrashPosts',compact('news','count_trash','author','group'));
    }
    public function add_focus()
    {
        //Danh sách danh mục
        $group = new Collection([]);
        foreach ($this->var_array as $item){
            $test =$this->groupService->getChildren($item);
            $group = $group->merge($test);
        }
        $parent_group = new Collection();
        $parent_group = $this->groupService->prepareData($group);
        $group = $parent_group;
        
        return view('Admin.FocusNews.AddPost',compact('group'));
    }
    public function post_add_focus(Request  $request)
    {
        $arrayValidate = [
            'news_title' => 'required|max:255|unique:news,news_title',
            'news_description' => 'required|max:255',
            'news_tag' => 'max:255',
            'group_id' => 'required',
            'video_url' => ['nullable', 'max:255', 'regex:/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(\?\S*)?$/'],
            'audio_url' => 'file|mimes:audio/mpeg,mpga,mp3,wav,aac',
            'image_url' => 'required|max:255',
            'news_content' => 'required|max:50000',
            // SEO
            'meta_key' => 'required|max:255',
            'meta_desc' => 'required|max:255',
            'meta_title' => 'required|max:255',
            'meta_url' => 'required|max:255|unique:news,news_url',
        ];
        if ($request->has('checked_express')){
            $arrayValidate['video_url'] = ['required', 'max:255', 'regex:/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(\?\S*)?$/'];
        }
        $request->validate( $arrayValidate, [
            'news_title.required' => 'Tiêu đề không được để trống',
            'news_title.max' => 'Tiêu đề tối đa 255 kí tự',
            'news_title.unique' => 'Tiêu đề đã tồn tại',
            'news_description.required' => 'Mô tả không được để trống',
            'news_description.max' => 'Mô tả tối đa 255 kí tự',
            'video_url.max' => 'Đường dẫn tối đa 255 kí tự',
            'video_url.regex' => 'Đường dẫn video youtube không hợp lệ',
            'news_tag.max' => 'Độ dài tối đa 255 kí tự',
            'group_id.required' => 'Vui lòng chọn danh mục',
            'audio_url.mimes' => 'Định dạng file không hợp lệ',
            'image_url.required' => 'Vui lòng chọn ảnh đại diện',
            'news_content.required' => 'Nội dung không được để trống',
            'video_url.required' => 'Đường dẫn video không được để trống',

            //SEO
            'meta_key.required' => 'Vui lòng nhập',
            'meta_key.max' => 'Tối đa 255 kí tự',
            'meta_desc.required' => 'Vui lòng nhập',
            'meta_desc.max' => 'Tối đa 255 kí tự',
            'meta_title.required' => 'Vui lòng nhập',
            'meta_title.max' => 'Tối đa 255 kí tự',
            'meta_url.required' => 'Vui lòng nhập',
            'meta_url.max' => 'Tối đa 255 kí tự',
            'meta_url.unique' => 'Đường dẫn đã tồn tại',
        ]);

        $news = [
            'group_id' => $request->group_id,
            'news_title' => $request->news_title,
            'news_description' => $request->news_description,
            'news_content' => $request->news_content,
            'news_url' => $request->meta_url,
            'video_url' => $request->video_url,
            'tag_list' => $request->news_tag,
            'meta_title' => $request->meta_title,
            'meta_key' => $request->meta_key,
            'meta_desc' => $request->meta_desc,
            'created_at' => time(),
            'created_by' => Auth::guard('admin')->id(),
            'image_url' => $request->image_url,
        ];

        if($request->hasFile('audio_url')){
            $news["audio_url"] = HelperImage::saveImage('system/audio/news', $request->file('audio_url'));
        }
        // Làm nổi bật tiêu điểm
        if($request->has('checked_hightlight')){
            $news['is_highlight'] = 1;
            $news['highlight_from'] = strtotime(Carbon::now());
            $news['highlight_to'] = strtotime(Carbon::now()->addDay(1));
        }
        // Quảng cáo tiêu điểm
        if($request->has('checked_express')){
            $news['is_express'] = 1;
            $news['express_start'] = now()->startOfDay();
            $news['express_end'] = now()->endOfDay();
        }
        // xử lý thêm bài viết hướng dẫn
        if($request->has('checked_tutorial')){
            $user_guide = [
                'guide_type'=>"N",
                'guide_title'=>$news["news_title"],
                'guide_content'=>$news["news_content"],
                'guide_url' =>$news["news_url"].Str::random(5),
                'image_url'=>$request->image_url,
                'created_at'=>strtotime(Carbon::now()),
                'created_by' => Auth::guard('admin')->user()->id
            ];
            // if($request->hasFile('image_url')){
            //     copy(public_path('system/img/news/'.$news['image_url']),public_path('system/img/guide/'.$news['image_url']));
            // } 
            $id_huongdan = DB::table('user_guide')->insertGetId($user_guide);
            if(!empty($id_huongdan)){
                Helper::create_admin_log(82,$user_guide);
                Toastr::success("Thêm bài hướng dẫn thành công");
            }
            else {
                Toastr::error("Đã xảy ra lỗi thêm hướng dẫn!");
            }
        }

            $id = News::insertGetId($news);
            if(!empty($id)){
                Helper::create_admin_log(106,$news);
                Toastr::success("Thêm tiêu điểm thành công");
                return redirect(route('admin.focus.list'));
            }
            else {
                Toastr::error("Đã xảy ra lỗi, vui lòng kiểm tra lại !");
                return back();
            }
    }
    public function update($id){
        $news = News::where('id',$id)->first();
        if($news==null) {
            Toastr::error("Không tồn tại");
            return back();
        }
        //fix Danh sách danh mục
        $group = new Collection([]);
        foreach ($this->var_array as $item){
            $test =$this->groupService->getChildren($item);
            $group = $group->merge($test);
        }
        $parent_group = new Collection();
        $parent_group = $this->groupService->prepareData($group);
        $group = $parent_group;
        
        return view('Admin.FocusNews.UpdatePosts',compact('news','group'));
    }
    public function post_update(Request $request,$id){
        $news_old = News::find($id);
        if($news_old == null){
            Toastr::error("Tin tức không tồn tại");
            return back();
        }
        $validate = $request->validate([
            'news_title'=>'required|max:255|unique:news,news_title,'.$id,
            'news_description'=>'required|max:255',
            'video_url'=>['nullable','max:255','regex:/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(\?\S*)?$/'],
            'news_tag'=>'max:255',
            'group_id'=>'required',
            'audio_url'=>'file|mimes:audio/mpeg,mpga,mp3,wav,aac',
            'image_url'=>['required', 'max:255'],
            'news_content'=>'required',

            // SEO
            'meta_key' => 'required|max:255',
            'meta_desc' => 'required|max:255',
            'meta_title' => 'required|max:255',
            'meta_url'=>'required|max:255|unique:news,news_url,'.$id,
        ],[
            'news_title.required'=>'Tiêu đề không được để trống',
            'news_title.max'=>'Tiêu đề tối đa 255 kí tự',
            'news_title.unique'=>'Tiêu đề đã tồn tại',
            'news_description.required'=>'Mô tả không được để trống',
            'news_description.max'=>'Mô tả tối đa 255 kí tự',
            'video_url.max'=>'Đường dẫn tối đa 255 kí tự',
            'video_url.regex'=>'Đường dẫn video youtube không hợp lệ',
            'news_tag.max'=>'Độ dài tối đa 255 kí tự',
            'group_id.required'=>'Vui lòng chọn danh mục',
            'audio_url.mimes'=>'Định dạng file không hợp lệ',
            'image_url.required'=>'Vui lòng chọn ảnh đại diện',
            'news_content.required'=>'Nội dung không được để trống',

            //SEO
            'meta_key.required' => 'Vui lòng nhập',
            'meta_key.max' => 'Tối đa 255 kí tự',
            'meta_desc.required' => 'Vui lòng nhập',
            'meta_desc.max' => 'Tối đa 255 kí tự',
            'meta_title.required' => 'Vui lòng nhập',
            'meta_title.max' => 'Tối đa 255 kí tự',
            'meta_url.required' => 'Vui lòng nhập',
            'meta_url.max' => 'Tối đa 255 kí tự',
            'meta_url.unique' => 'Đường dẫn đã tồn tại',
        ]);
        $news = [
            'group_id' => $request->group_id,
            'news_title' => $request->news_title,
            'news_description' => $request->news_description,
            'news_content' => $request->news_content,
            'video_url' => $request->video_url,
            'tag_list' => $request->news_tag,
            'image_url' => $request->image_url,
            'updated_at' => time(),
            'updated_by' => Auth::guard('admin')->user()->id,
            // SEO
            'meta_key' => $request->meta_key,
            'meta_title' => $request->meta_title,
            'meta_desc' => $request->meta_desc,
            'news_url' => $request->meta_url,
        ];

        if($request->hasFile('audio_url')){
            $news["audio_url"] = HelperImage::updateImage('system/audio/news', $request->file('audio_url'), $news_old->audio_url);
        }
        // Quảng cáo tiêu điểm
        // if($request->has('checked_express')){
        //     $news['is_express'] = 1;
        // }

        News::where('id', $id)->update($news);
        $news['id']=$id;
        Helper::create_admin_log(107,$news);
        Toastr::success("Cập nhật tiêu điểm thành công");
        return redirect(route('admin.focus.list'));
    }
    public function trash_item($id){
        $news = News::where('id',$id)->first();
        if($news == null ){
            Toastr::error("Không tồn tại tiêu điểm");
            return back();
        }
        $news = News::where('id',$id)->update([
            'is_deleted'=>1,
        ]);
        $data =['id'=>$id,  'is_deleted'=>1];
        Helper::create_admin_log(108,$data);
        Toastr::success("Xóa tiêu điểm thành công");
        return back();
    }
    public function untrash_item($id){
        $news = News::where('id',$id)->first();
        if($news == null ){
            Toastr::error("Không tồn tại tiêu điểm");
            return back();
        }
        $news = News::where('id',$id)->update([
            'is_deleted'=>0,
        ]);
        $data =['id'=>$id,  'is_deleted'=>0];
        Helper::create_admin_log(109,$data);
        Toastr::success("Khôi phục tiêu điểm thành công");
        return back();
    }
    public function delete_item($id){
        $news = News::where('id',$id)->first();
        if($news == null ){
            Toastr::error("Không tồn tại tiêu điểm");
            return back();
        }
//        if(file_exists(public_path('system/img/news/'.$news->image_url) )&& $news->image_url != ""){
//            unlink(public_path('system/img/news/'.$news->image_url));
//        }
        Helper::create_admin_log(110,$news);

        $news = News::delete($id);
        Toastr::success("Xóa tiêu điểm thành công");
        return back();
    }
    public function highlight_item($id){

        $news = News::where('id',$id)->first();
        if($news == null ){
            Toastr::error("Không tồn tại tiêu điểm");
            return back();
        }
        if($news->is_highlight == 1 && ($news->highlight_to > time() || $news->highlight_to == null )){
            Toastr::error("Đã là tin nổi bật");
            return back();
        }
        $news = News::where('id',$id)->update([
            'is_highlight'=>1,
            'highlight_from'=>strtotime(Carbon::now()),
            'highlight_to'=>strtotime(Carbon::now()->addDay(1)),
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => strtotime(Carbon::now()),
            ]);
        $data =[
            'id'=>$id,
            'is_highlight'=>1,
            'highlight_from'=>strtotime(Carbon::now()),
            'highlight_to'=>strtotime(Carbon::now()->addDay(1)),
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => strtotime(Carbon::now()),
        ];
        Helper::create_admin_log(111,$data);
        Toastr::success("Nổi bật tiêu điểm thành công");
        return back();
    }
    public function express($id){
        $news = News::where('id',$id)->first();
        if($news->video_url == null){
            Toastr::error("Tiêu điểm quảng cáo cần có video");
            return back();
        }
        if($news == null ){
            Toastr::error("Không tồn tại tiêu điểm");
            return back();
        }
        if($news->is_express == 1){
            Toastr::error("Đã là tin quảng cáo");
            return back();
        }
        $news = News::where('id',$id)->update([
            'is_express'=>1,
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => strtotime(Carbon::now()),
        ]);
        $data = [
            'id'=>$id,
            'is_express'=>1,
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => strtotime(Carbon::now()),
        ];
        Helper::create_admin_log(112,$data);
        Toastr::success("Quảng cáo tiêu điểm thành công");
        return back();
    }
    public function un_express($id){
        $news = News::where('id',$id)->first();
        if($news == null ){
            Toastr::error("Không tồn tại tiêu điểm");
            return back();
        }
        if($news->is_express == 0){
            Toastr::error("Đây là tin thường không thể hủy quảng cáo");
            return back();
        }
        $news = $news->update([
            'is_express'=>1,
            'updated_by'=>Auth::guard('admin')->user()->id,
            'updated_at' => strtotime(Carbon::now()),
        ]);
        // $data = [
        //     'id'=>$id,
        //     'is_express'=>1,
        //     'updated_by'=>Auth::guard('admin')->user()->id,
        //     'updated_at' => strtotime(Carbon::now()),
        // ];
        // Helper::create_admin_log(113,$data);
        Toastr::success("Hủy quảng cáo tiêu điểm thành công");
        return back();
    }
    public function trash_list(Request $request){
        if($request->select_item ==null){
            Toastr::error("Vui lòng chọn");
            return back();
        }
        if($request->has('action') && $request->action == "trash"){
            foreach($request->select_item as $item){
                   News::where('id',$item)->update([
                       'is_deleted'=>1,
                   ]);
                   $data= ['id'=>$item,'is_deleted'=>1];
                   Helper::create_admin_log(108,$data);
            }
            Toastr::success("Chuyển vào thùng rác thành công");
            return back();
        }
        if($request->has('action') && $request->action == "restore"){
            foreach($request->select_item as $item){
                News::where('id',$item)->update([
                    'is_deleted'=>0,
                ]);
                $data= ['id'=>$item,'is_deleted'=>0];
                Helper::create_admin_log(109,$data);
            }
            Toastr::success("Khôi phục tiêu điểm thành công");
            return back();
        }

    }
    public  function  view_news($id){
        $new = News::find($id);
        if($new== null){
            Toastr::error("Không tồn tại tiêu điểm");
        }
        // fix đường dẫn view
        return view('Admin.FocusNews.ViewPosts',compact('new'));
    }
}
