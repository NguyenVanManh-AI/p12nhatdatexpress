<?php
return [
    'validate_message' => [
        'required' => '*Vui lòng nhập :attribute',
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
        'username' => 'Tên đăng nhập',
        'fullname' => 'Tên hiển thị',
        'password' => 'Mật khẩu',
        're_password' => 'Mật khẩu',
        'phone_number' => 'Số điện thoại',
        'email' => 'Email',
        'birthday' => 'Ngày sinh',
        'personal_id' => 'Số CMND',
        'tax_number' => 'Mã số thuế',
        'province' => 'Tỉnh/thành phố',
        'district' => 'Quận/huyện',
        'ward' => 'Phường/xã',
        'source' => 'Nguồn',

        // project
        'group_name' => 'Tên danh mục',
        'group_url'=>'Đường dẫn tĩnh',
        'group_description'=>'Mô tả ngắn',
        'image_url'=>'Hình ảnh',

    ]
];
