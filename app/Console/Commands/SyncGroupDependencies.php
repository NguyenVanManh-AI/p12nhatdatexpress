<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;

class SyncGroupDependencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:group-dependencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync paradigms dependencies for paradigms of group';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // project paradigms
        $projectParadigms = Group::where('parent_id', 34)
            ->get();

        $dependencyMaps = [
            '35' => [
                3, 11, 21, 30
            ],
            '37' => [
                3, 11, 21, 30
            ],
            '38' => [
                3, 11, 21, 30
            ],
            '39' => [
                3, 11, 21, 30
            ],
            '40' => [
                3, 11, 21, 30
            ],
            '41' => [
                5, 11, 85, 33
            ],
            '42' => [
                3, 14, 21, 30
            ],
        ];

        foreach ($projectParadigms as $projectParadigm) {
            $dependencyIds = data_get($dependencyMaps, $projectParadigm->id, []);

            $projectParadigm->dependencies()->sync($dependencyIds);
        }

        return 0;

        // Dự án chọn  : Căn hộ - Chung cư
        // Nhà đất bán : Căn hộ - chung cư
        // Nhà đất cho thuê : Cho thuê căn hộ
        // Cần mua : Cần mua căn hộ
        // Cần thuê : Cần thuê căn hộ

        // Dự án chọn : Đất nền phân lô
        // Nhà đất bán : Đất nền dự án
        // Nhà đất cho thuê : Cho thuê Đất
        // Cần mua : Cần mua đất
        // Cần thuê : Cần thuê mặt bằng

        // Dự án chọn nhà ở xã hội 
        // Nhà đất bán : Căn hộ - chung cư
        // Nhà đất cho thuê : Cho thuê căn hộ
        // Cần mua : Cần mua nhà ở xã hội
        // Cần thuê : Cần thuê căn hộ

        // Dư án chọn Khu phức hợp :
        // Nhà đất bán : Căn hộ - chung cư
        // Nhà đất cho thuê : Cho thuê căn hộ
        // Cần mua : Cần mua căn hộ
        // Cần thuê : Cần thuê căn hộ

        // Dư án chọn căn hộ dịch vụ :
        // Nhà đất bán : Căn hộ - chung cư
        // Nhà đất cho thuê : Cho thuê căn hộ
        // Cần mua : Cần mua căn hộ
        // Cần thuê : Cần thuê căn hộ

        // Dự án chọn : khu nghỉ dưỡng
        // Nhà đất bán : Căn hộ - chung cư
        // Nhà đất cho thuê : Cho thuê căn hộ
        // Cần mua : Cần mua căn hộ
        // Cần thuê : Cần thuê căn hộ

        // Dự án chọn Biệt thự, liền kề
        // Nhà đất bán : Biệt thự - liền kề
        // Nhà đất cho thuê : Cho thuê căn hộ
        // Cần mua : Cần mua biệt thự liền kề
        // Cần thuê : Cần thuê biệt thự

        // Dự án chọn : cao ốc văn phòng
        // Nhà đất bán : Căn hộ chung cư
        // Nhà đất cho thuê : Cho thuê Văn phòng
        // Cần mua : Cần mua căn hộ
        // Cần thuê : Cần thuê căn hộ

        // $dependencyMap = [
        //     '35can-ho-chung-cu', '37nha-o-xa-hoi', '38khu-phuc-hop', '39can-ho-dich-vu', '40khu-nghi-duong' => [
        //         '3can-ho-chung-cu',
        //         '11cho-thue-can-ho',
        //         '21can-mua-can-ho'
        //         '30can-thue-can-ho'
        //     ],
        //     41khu-biet-thu-lien-ke => [
        //         '5biet-thu-lien-ke',
        //         '11cho-thue-can-ho',
        //         '85can-mua-biet-thu-lien-ke'
        //         '33can-thue-biet-thu'
        //     ]
        //     42cao-oc-van-phong=> [
        //         '3can-ho-chung-cu',
        //         '14cho-thue-van-phong',
        //         '21can-mua-can-ho'
        //         '30can-thue-can-ho'
        //     ]
        // ]
    }
}
