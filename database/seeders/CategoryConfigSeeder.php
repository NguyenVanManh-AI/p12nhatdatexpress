<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class CategoryConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_config')->insert([
            [   
                'id'=>1,
                'group_id'=>1 ,
                'config_type'=>'C' ,
                'config_code'=>'C001',
                'config_name' => 'Số tin hiển thị',
                'config_value'=>10,
                'created_at'=> time(),
                'created_by'=>null,
                'updated_at'=>null,
                'updated_by'=>null,
            ],
            [   
                'id'=>2,
                'group_id'=>1 ,
                'config_type'=>'C' ,
                'config_code'=>'C002',
                'config_name' => 'Văn bản giới thiệu chuyên mục cuối trang',
                'config_value'=>'<div>Nhadatexpress là 1 trong những website Bất động sản&nbsp;<span style="font-size: 1rem;">hàng đầu Việt Nam - Giải pháp giúp tiếp cận hơn 10.000+ khách hàng mỗi ngày.Tại Nhà đất Express người dùng có thể tìm kiếm cho mình tất&nbsp;</span><span style="font-size: 1rem;">cả các loại hình bất động sản như Nhà đất bán, Nhà đất cho thuê, Cần mua - Cần thuê và thông tin về những dự án một cách khách quan &amp; nhanh nhất.</span><span style="font-size: 1rem;">Bạn đang xem thông tin tại mục </span><span class="text-danger" style="font-size: 1rem;"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger">%vị_trí</span></span></span></span></span></span></span><span style="font-size: 1rem;">.&nbsp;</span></div><div><span style="font-size: 1rem;">Đ</span><span style="font-size: 1rem;">ể tìm thấy bất động sản nhanh nhất bạn nên sử dụng tính năng Tìm Kiếm Xung Quanh đề thấy được danh sách </span><span class="text-danger" style="font-size: 1rem;"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger">%cần_mua_cần_thuê</span></span></span></span></span></span></span><span style="font-size: 1rem;"> gần bạn nhất hoặc sử dụng tính năng Tìm kiếm Nâng cao để tìm th</span><span style="font-size: 1rem;">ấy bất động sản hơp ý nhất</span></div>',
                'created_at'=> time(),
                'created_by'=>null,
                'updated_at'=>null,
                'updated_by'=>null,
            ],
            [   
                'id'=>3,
                'group_id'=>1 ,
                'config_type'=>'C' ,
                'config_code'=>'C003',
                'config_name' => 'Văn bản giới thiệu vị trí cuối trang',
                'config_value'=>'<div>Nhadatexpress là 1 trong những website Bất động sản&nbsp;<span style="font-size: 1rem;">hàng đầu Việt Nam - Giải pháp giúp tiếp cận hơn 10.000+ khách hàng mỗi ngày.Tại Nhà đất Express người dùng có thể tìm kiếm cho mình tất&nbsp;</span><span style="font-size: 1rem;">cả các loại hình bất động sản như Nhà đất bán, Nhà đất cho thuê, Cần mua - Cần thuê và thông tin về những dự án một cách khách quan &amp; nhanh nhất.</span><span style="font-size: 1rem;">Bạn đang xem thông tin tại mục </span><span class="text-danger" style="font-size: 1rem;"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger">%vị_trí</span></span></span></span></span></span></span><span style="font-size: 1rem;">.&nbsp;</span></div><div><span style="font-size: 1rem;">Đ</span><span style="font-size: 1rem;">ể tìm thấy bất động sản nhanh nhất bạn nên sử dụng tính năng Tìm Kiếm Xung Quanh đề thấy được danh sách </span><span class="text-danger" style="font-size: 1rem;"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger"><span class="text-danger">%cần_mua_cần_thuê</span></span></span></span></span></span></span><span style="font-size: 1rem;"> gần bạn nhất hoặc sử dụng tính năng Tìm kiếm Nâng cao để tìm th</span><span style="font-size: 1rem;">ấy bất động sản hơp ý nhất</span></div>',
                'created_at'=> time(),
                'created_by'=>null,
                'updated_at'=>null,
                'updated_by'=>null,
            ],
        ]);
    }
}
