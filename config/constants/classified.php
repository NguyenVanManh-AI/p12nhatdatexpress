<?php
return [
    'validate_message' => [
        'required' => 'Vui lòng nhập :attribute',
        'alpha_num' => ':attribute chỉ chứa chữ cái và số',
        'unique' => ':attribute đã tồn tại',
        'min' => ':attribute chứa tối thiểu :min ký tự',
        'max' => ':attribute chứa tối đa :max ký tự',
        'alpha' => ':attribute chỉ chứa chữ cái',
        'same' => ':attribute không khớp',
        'exists' => ':attribute không hợp lệ',
        'numeric' => ':attribute chỉ chứa số',
        'between' => [
            'numeric' => ':attribute phải từ :min - :max',
            'file' => ':attribute phải từ :min - :max KB',
            'string' => ':attribute phải từ :min - :max ký tự',
            'array' => ':attribute phải từ :min - :max phần tử',
        ],
        'array' => ':attribute không hợp lệ',
        'regex' => ':attribute không hợp lệ',
        'integer' =>':attribute chỉ chứa số nguyên',
    ],
    'validate_attribute_alias' => [
        'title'=>'Tiêu đề',
        'noidung'=>'Nội dung',
        'duan'=>'Dự án',
        'classified_area'=>'Diện tích',
        'dientich'=>'Đơn vị diện tích',
        'giaban'=>'Giá bán',
        'donviban'=>'Đơn vị',
        'phaply'=>'Pháp lý',
        'phongngu'=>'Phòng ngủ',
        'phongvesinh'=>'Phòng vệ sinh',
        'huong'=>'Hướng',
        'duong'=>'Địa chỉ',
        'tinh' => 'Tỉnh/thành phố',
        'huyen' => 'Quận/huyện',
        'xa' => 'Phường/xã',
        'mohinh' => 'Mô hình',
        'tinhtrang' => 'Tình trạng',
        'noithat' => 'Nội thất',
        'giathue' => 'Giá thuê',
        'donvithue' => 'Đơn vị',
        'nguoitoida' => 'Số người tối đa',
        'coctruoc' => 'Cọc trước',
        'contact_name'=>'Họ tên',
        'contact_phone'=>'Số điện thoại',
        'contact_email'=>'Địa chỉ email',
        'contact_address'=>'Địa chỉ',
    ],

    'search' => [
        'price' => [
            'sell' => [
                [
                    'value' => '0-1',
                    'label' => 'Dưới 1 tỷ',
                    'mobile_label' => '<1 tỷ'
                ],
                [
                    'value' => '1-2',
                    'label' => 'Từ 1 đến 2 tỷ',
                    'mobile_label' => '1 - 2 tỷ',
                ],
                [
                    'value' => '2-3',
                    'label' => 'Từ 2 đến 3 tỷ',
                    'mobile_label' => '2 - 3 tỷ',
                ],
                [
                    'value' => '3-4',
                    'label' => 'Từ 3 đến 4 tỷ',
                    'mobile_label' => '3 - 4 tỷ',
                ],
                [
                    'value' => '4-6',
                    'label' => 'Từ 4 đến 6 tỷ',
                    'sub_label' => 'Trên 4 tỷ'
                ],
                [
                    'value' => '6-8',
                    'label' => 'Từ 6 đến 8 tỷ'
                ],
                [
                    'value' => '8-10',
                    'label' => 'Từ 8 đến 10 tỷ'
                ],
                [
                    'value' => '10-15',
                    'label' => 'Từ 10 đến 15 tỷ'
                ],
                [
                    'value' => '15-30',
                    'label' => 'Từ 15 đến 30 tỷ'
                ],
                [
                    'value' => '30-50',
                    'label' => 'Từ 30 đến 50 tỷ'
                ],
                [
                    'value' => '50-0',
                    'label' => 'Trên 50 tỷ'
                ],
            ],
            'rent' => [
                [
                    'value' => '0-2',
                    'label' => 'Dưới 2 triệu',
                    'mobile_label' => '<2 triệu'
                ],
                [
                    'value' => '2-4',
                    'label' => 'Từ 2 đến 4 triệu',
                    'mobile_label' => '2 - 4 triệu',
                ],
                [
                    'value' => '4-6',
                    'label' => 'Từ 4 đến 6 triệu',
                    'mobile_label' => '4 - 6 triệu',
                ],
                [
                    'value' => '6-8',
                    'label' => 'Từ 6 đến 8 triệu',
                    'mobile_label' => '6 - 8 triệu',
                ],
                [
                    'value' => '8-10',
                    'label' => 'Từ 8 đến 10 triệu',
                    'sub_label' => 'Trên 8 triêụ'
                ],
                [
                    'value' => '10-15',
                    'label' => 'Từ 10 đến 15 triệu'
                ],
                [
                    'value' => '15-20',
                    'label' => 'Từ 15 đến 20 triệu'
                ],
      
                [
                    'value' => '20-25',
                    'label' => 'Từ 20 đến 25 triệu'
                ],
                [
                    'value' => '25-50',
                    'label' => 'Từ 25 đến 50 triệu'
                ],
                [
                    'value' => '50-100',
                    'label' => 'Từ 50 đến 100 triệu'
                ],
                [
                    'value' => '100-0',
                    'label' => 'Trên 100 triệu'
                ],
            ],
            'project' => [
                [
                    'value' => '0-1',
                    'label' => 'Dưới 1 tỷ',
                    'mobile_label' => '<1 tỷ'
                ],
                [
                    'value' => '1-2',
                    'label' => 'Từ 1 đến 2 tỷ',
                    'mobile_label' => '1 - 2 tỷ',
                ],
                [
                    'value' => '2-3',
                    'label' => 'Từ 2 đến 3 tỷ',
                    'mobile_label' => '2 - 3 tỷ',
                ],
                [
                    'value' => '3-4',
                    'label' => 'Từ 3 đến 4 tỷ',
                    'mobile_label' => '3 - 4 tỷ',
                ],
                [
                    'value' => '4-6',
                    'label' => 'Từ 4 đến 6 tỷ',
                    'sub_label' => 'Trên 4 tỷ'
                ],
                [
                    'value' => '6-8',
                    'label' => 'Từ 6 đến 8 tỷ'
                ],
                [
                    'value' => '8-10',
                    'label' => 'Từ 8 đến 10 tỷ'
                ],
                [
                    'value' => '10-15',
                    'label' => 'Từ 10 đến 15 tỷ'
                ],
                [
                    'value' => '15-30',
                    'label' => 'Từ 15 đến 30 tỷ'
                ],
                [
                    'value' => '30-50',
                    'label' => 'Từ 30 đến 50 tỷ'
                ],
                [
                    'value' => '50-0',
                    'label' => 'Trên 50 tỷ'
                ],
            ],
            'project_area' => [
                [
                    'value' => '0-1',
                    'label' => 'Dưới 1 tỷ',
                    'mobile_label' => '<1 tỷ'
                ],
                [
                    'value' => '1-2',
                    'label' => 'Từ 1 đến 2 tỷ',
                    'mobile_label' => '1 - 2 tỷ',
                ],
                [
                    'value' => '2-3',
                    'label' => 'Từ 2 đến 3 tỷ',
                    'mobile_label' => '2 - 3 tỷ',
                ],
                [
                    'value' => '3-4',
                    'label' => 'Từ 3 đến 4 tỷ',
                    'mobile_label' => '3 - 4 tỷ',
                ],
                [
                    'value' => '4-6',
                    'label' => 'Từ 4 đến 6 tỷ',
                    'sub_label' => 'Trên 4 tỷ',
                ],
                [
                    'value' => '6-8',
                    'label' => 'Từ 6 đến 8 tỷ'
                ],
                [
                    'value' => '8-10',
                    'label' => 'Từ 8 đến 10 tỷ'
                ],
                [
                    'value' => '10-15',
                    'label' => 'Từ 10 đến 15 tỷ'
                ],
                [
                    'value' => '15-30',
                    'label' => 'Từ 15 đến 30 tỷ'
                ],
                [
                    'value' => '30-50',
                    'label' => 'Từ 30 đến 50 tỷ'
                ],
                [
                    'value' => '50-0',
                    'label' => 'Trên 50 tỷ'
                ],
            ],
        ],
        'area' => [
            'sell' => [
                [
                    'value' => '0-60',
                    'label' => 'Dưới 60 m2'
                ],
                [
                    'value' => '60-80',
                    'label' => '60 m2 đến 80 m2'
                ],
                [
                    'value' => '80-100',
                    'label' => '80 m2 đên 100 m2'
                ],
                [
                    'value' => '100-150',
                    'label' => '100 m2 đến 150 m2'
                ],
                [
                    'value' => '150-200',
                    'label' => '150 m2 đên 200 m2'
                ],
                [
                    'value' => '200-250',
                    'label' => '200 m2 đến 250 m2'
                ],
                [
                    'value' => '250-500',
                    'label' => '250 m2 đến 500 m2'
                ],
                [
                    'value' => '500-1000',
                    'label' => '500 m2 đến 1000 m2'
                ],
                [
                    'value' => '1000-0',
                    'label' => 'Trên 1000 m2'
                ],
            ],
            
            'rent' => [
                [
                    'value' => '0-20',
                    'label' => 'Dưới 20 m2'
                ],
                [
                    'value' => '20-35',
                    'label' => '20 m2 đến 35 m2'
                ],
                [
                    'value' => '35-50',
                    'label' => '35 m2 đên 50 m2'
                ],
                [
                    'value' => '50-70',
                    'label' => '50 m2 đến 70 m2'
                ],
                [
                    'value' => '70-100',
                    'label' => '70 m2 đên 100 m2'
                ],
                [
                    'value' => '100-150',
                    'label' => '100 m2 đến 150 m2'
                ],
                [
                    'value' => '150-200',
                    'label' => '150 m2 đến 200 m2'
                ],
                [
                    'value' => '200-250',
                    'label' => '200 m2 đến 250 m2'
                ],
                [
                    'value' => '250-300',
                    'label' => '250 m2 đến 300 m2'
                ],
                [
                    'value' => '300-400',
                    'label' => '300 m2 đến 400 m2'
                ],
                [
                    'value' => '400-500',
                    'label' => '400 m2 đến 500 m2'
                ],
                [
                    'value' => '500-0',
                    'label' => 'Trên 500 m2'
                ],
            ],
            'project_scale' => [
                [
                    'value' => '0-1',
                    'label' => 'Dưới 1ha'
                ],
                [
                    'value' => '1-2',
                    'label' => 'Từ 1ha đến 2ha'
                ],
                [
                    'value' => '2-3',
                    'label' => 'Từ 2ha đến 3ha'
                ],
                [
                    'value' => '3-4',
                    'label' => 'Từ 3ha đến 4ha'
                ],
                [
                    'value' => '4-5',
                    'label' => 'Từ 4ha đến 5ha'
                ],
                [
                    'value' => '5-0',
                    'label' => 'Trên 5ha'
                ],
            ],
            'project_area' => [
                [
                    'value' => '0-60',
                    'label' => 'Dưới 60 m2'
                ],
                [
                    'value' => '60-80',
                    'label' => '60 m2 đến 80 m2'
                ],
                [
                    'value' => '80-100',
                    'label' => '80 m2 đên 100 m2'
                ],
                [
                    'value' => '100-150',
                    'label' => '100 m2 đến 150 m2'
                ],
                [
                    'value' => '150-200',
                    'label' => '150 m2 đên 200 m2'
                ],
                [
                    'value' => '200-250',
                    'label' => '200 m2 đến 250 m2'
                ],
                [
                    'value' => '250-500',
                    'label' => '250 m2 đến 500 m2'
                ],
                [
                    'value' => '500-1000',
                    'label' => '500 m2 đến 1000 m2'
                ],
                [
                    'value' => '1000-0',
                    'label' => 'Trên 1000 m2'
                ],
            ],
        ],
        'sort' => [
            [
                'value' => 'binh-thuong',
                'label' => 'Bình thường'
            ],
            [
                'value' => 'luot-xem-nhieu-nhat',
                'label' => 'Lượt xem nhiều nhất'
            ],
            [
                'value' => 'gia-cao-nhat',
                'label' => 'Giá cao nhất'
            ],
            [
                'value' => 'gia-thap-nhat',
                'label' => 'Giá thấp nhất'
            ],
            [
                'value' => 'dien-tich-lon-nhat',
                'label' => 'Diện tích lớn nhất'
            ],
            [
                'value' => 'dien-tich-nho-nhat',
                'label' => 'Diện tích nhỏ nhất'
            ]
        ],  
        'featured_keyword' => [
            'max_keyword_special' => 8
        ]
    ],
    'comment' => [
        'limit_per_day' => 5,
        'limit_update_per_day' => 3,
        'limit_delete_per_day' => 3,
    ],
    'related' => [
        'limit' => 8
    ],
];
