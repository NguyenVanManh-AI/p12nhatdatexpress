<?php

namespace App\Services;

use App\Enums\AdminType;
use App\Models\Admin;
use App\Models\Role;

class AdminService
{
    /**
     * Get support account
     * @param $withSuperAdmin = false
     * 
     * @return collection
     */
    public function getSupportAccount($withSuperAdmin = false)
    {
        // Nhân viên CSKH ( danh sách tài khoản được gán cho chức năng CSKH )
        $roles = Role::latest()->get();
        $supportGroupRoleIds = [];
        $supportPageId = 28; // page 'Hỗ trợ kỹ thuật'
        foreach ($roles as $role) {
            $permissions = unserialize($role->role_content);
            if (data_get($permissions, $supportPageId)) {
                $supportGroupRoleIds[] = $role->id;
            }
        }

        $careStaffs = Admin::select('admin.*')
            ->whereIn('rol_id', $supportGroupRoleIds)
            ->when($withSuperAdmin, function ($query) {
                return $query->orWhere('admin_type', AdminType::SUPER_ADMIN);
            })
            ->get();

        return $careStaffs;
    }
}

