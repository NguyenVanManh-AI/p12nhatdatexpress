<?php
namespace App\Http\Middleware\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AdminCheckMiddleware
{
    // Check routes parent and child (permission)
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, $page, $action)
    {
        // Check role if admin_type != 1 : Chinh 10:16 05/01/2021
        $admin = Auth::guard('admin')->user();
        if ($admin->admin_type != 1) {
            $list_permission = unserialize(session()->get('role')->role_content)[$page] ?? null;

            // Không có quyền gì cả
            if ($list_permission == null) {
                return redirect($this->noPermissionRedirect());
            }

            // Không có quyền vào trang
            if (!key_exists($action, $list_permission)) {
                return redirect($this->noPermissionRedirect());
            }
            // Self, group, all
            if ($request->route('id')) {
                // Check All
                if (key_exists("all", $list_permission[$action])) {
                    view()->share('check_role', $list_permission);
                    return $next($request);
                }
                try {
                    $createdByRequest = Crypt::decryptString($request->route('created_by'));
                } catch (DecryptException $e) {
                    return redirect($this->noPermissionRedirect());
                }
                $roleRequestId = DB::table('admin')
                        ->select('rol_id')
                        ->where('id', $createdByRequest)
                        ->first()->rol_id ?? null;
                if ($roleRequestId == null) {
                    return redirect($this->noPermissionRedirect());
                }
                // Check Group
                if (key_exists('group', $list_permission[$action])) {
                    if ($roleRequestId != null && $roleRequestId == $admin->rol_id) {
                        view()->share('check_role', $list_permission);
                        return $next($request);
                    }
                }
                // Check Self
                if (key_exists('self', $list_permission[$action])) {
                    if ($admin->id == $createdByRequest) {
                        view()->share('check_role', $list_permission);
                        return $next($request);
                    }
                }
                return redirect($this->noPermissionRedirect());
            } else { // Cho các request không có id đi kèm
                // get -> danh sách || lấy dữ liệu để thêm
                // post -> thêm || thao tác
                if ($request->method() == "GET") {
                    if (key_exists('all', $list_permission[$action])) {
                        $request->request->add(['request_list_scope' => 1]); // all
                    } else if (key_exists('group', $list_permission[$action])) {
                        $request->request->add(['request_list_scope' => 2]); // group
                    } else if (key_exists('self', $list_permission[$action])) {
                        $request->request->add(['request_list_scope' => 3]); // self
                    } else if (key_exists('check', $list_permission[$action])) {
                        $request->request->add(['request_list_scope' => 1]); // check
                    }
                    ///////////////
                    if (key_exists( 8, $list_permission)) {
                        if (key_exists('all', $list_permission[8])) {
                            $request->request->add(['request_trash_list_scope' => 1]); // all
                        } else if (key_exists('group', $list_permission[8])) {
                            $request->request->add(['request_trash_list_scope' => 2]); // group
                        } else if (key_exists('self', $list_permission[8])) {
                            $request->request->add(['request_trash_list_scope' => 3]); // self
                        } else if (key_exists('check', $list_permission[8])) {
                            $request->request->add(['request_trash_list_scope' => 1]); // check
                        }
                    }
                    //////////////
                } else {
                    $action_form = null;
                    switch ($request->action) {
                        case "update":
                            $action_form = 2;
                            break;
                        case "duplicate":
                            $action_form = 3;
                            break;
                        case "trash":
                            $action_form = 5;
                            break;
                        case "restore":
                            $action_form = 6;
                            break;
                        case "delete":
                            $action_form = 7;
                            break;
                    }
//                    dd($request->all());
                    if ($action_form != null) {
                        if (key_exists('all', $list_permission[$action_form])) {
                            return $next($request);
                        } else if (key_exists('group', $list_permission[$action_form])) {
                            foreach ($request->select_item as $select) {
                                try {
                                    $createdByRequest = Crypt::decryptString($request->select_item_created[$select]);
                                } catch (DecryptException $e) {
                                    return back();
                                }
                                $admin_created = DB::table('admin')->select('rol_id')->where('id', $createdByRequest)->first();
                                if (Auth::guard('admin')->user()->rol_id != $admin_created->rol_id) {
                                    return redirect($this->noPermissionRedirect());
                                }
                            }
                        } else if (key_exists('self', $list_permission[$action_form])) {
                            if(!isset($request->select_item)) {
                                return redirect($this->noPermissionRedirect());
                            }
                            foreach ($request->select_item as $select) {
                                try {
                                    $createdByRequest = Crypt::decryptString($request->select_item_created[$select]);
                                }
                                catch (DecryptException $e) {
                                    return redirect($this->noPermissionRedirect());
                                }
                                if (Auth::guard('admin')->user()->id != $createdByRequest) {
                                    return redirect($this->noPermissionRedirect());
                                }
                            }
                        } else if (key_exists('check', $list_permission[$action_form])) {
                            return $next($request);
                        }
                    }
                }
            }
            view()->share('check_role', $list_permission);
        } else {
            view()->share('check_role', 1);
        }

        return $next($request);
    }

    /**
     * redirect to dashboard index (not need check page permission) when admin not have any permission
     * @param string $message = 'Không đủ quyền
     *
     * @return string $url
     */
    private function noPermissionRedirect($message = 'Không đủ quyền')
    {
        Toastr::warning("Không đủ quyền");

        return route('admin.dashboard.index');
    }
}
