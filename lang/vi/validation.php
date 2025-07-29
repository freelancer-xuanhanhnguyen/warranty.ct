<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'phải được chấp nhận.',
    'active_url' => 'không phải là một URL hợp lệ.',
    'after' => 'phải là một ngày sau :date.',
    'after_or_equal' => 'phải là một ngày sau hoặc bằng :date.',
    'alpha' => 'chỉ có thể chứa chữ cái.',
    'alpha_dash' => 'chỉ có thể chứa chữ cái, số, dấu gạch ngang và gạch dưới.',
    'alpha_num' => 'chỉ có thể chứa chữ cái và số.',
    'array' => 'phải là một mảng.',
    'before' => 'phải là một ngày trước :date.',
    'before_or_equal' => 'phải là một ngày trước hoặc bằng :date.',
    'between' => [
        'numeric' => 'phải nằm giữa :min và :max.',
        'file' => 'phải có kích thước từ :min đến :max kilobytes.',
        'string' => 'phải từ :min đến :max ký tự.',
        'array' => 'phải có từ :min đến :max phần tử.',
    ],
    'boolean' => 'phải là true hoặc false.',
    'confirmed' => 'xác nhận không khớp.',
    'date' => 'không phải là ngày hợp lệ.',
    'date_equals' => 'phải là một ngày bằng :date.',
    'date_format' => 'không khớp với định dạng :format.',
    'different' => 'và :other phải khác nhau.',
    'digits' => 'phải gồm :digits chữ số.',
    'digits_between' => 'phải có từ :min đến :max chữ số.',
    'dimensions' => 'có kích thước ảnh không hợp lệ.',
    'distinct' => 'có giá trị trùng lặp.',
    'email' => 'phải là một địa chỉ email hợp lệ.',
    'ends_with' => 'phải kết thúc bằng một trong những giá trị sau: :values.',
    'exists' => 'không hợp lệ.',
    'file' => 'phải là một tệp tin.',
    'filled' => 'không được để trống.',
    'gt' => [
        'numeric' => 'phải lớn hơn :value.',
        'file' => 'phải lớn hơn :value kilobytes.',
        'string' => 'phải lớn hơn :value ký tự.',
        'array' => 'phải có nhiều hơn :value phần tử.',
    ],
    'gte' => [
        'numeric' => 'phải lớn hơn hoặc bằng :value.',
        'file' => 'phải lớn hơn hoặc bằng :value kilobytes.',
        'string' => 'phải lớn hơn hoặc bằng :value ký tự.',
        'array' => 'phải có :value phần tử trở lên.',
    ],
    'image' => 'phải là một ảnh.',
    'in' => 'không hợp lệ.',
    'in_array' => 'không tồn tại trong :other.',
    'integer' => 'phải là số nguyên.',
    'ip' => 'phải là địa chỉ IP hợp lệ.',
    'ipv4' => 'phải là địa chỉ IPv4 hợp lệ.',
    'ipv6' => 'phải là địa chỉ IPv6 hợp lệ.',
    'json' => 'phải là chuỗi JSON hợp lệ.',
    'lt' => [
        'numeric' => 'phải nhỏ hơn :value.',
        'file' => 'phải nhỏ hơn :value kilobytes.',
        'string' => 'phải nhỏ hơn :value ký tự.',
        'array' => 'phải có ít hơn :value phần tử.',
    ],
    'lte' => [
        'numeric' => 'phải nhỏ hơn hoặc bằng :value.',
        'file' => 'phải nhỏ hơn hoặc bằng :value kilobytes.',
        'string' => 'phải nhỏ hơn hoặc bằng :value ký tự.',
        'array' => 'không được có nhiều hơn :value phần tử.',
    ],
    'max' => [
        'numeric' => 'không được lớn hơn :max.',
        'file' => 'không được lớn hơn :max kilobytes.',
        'string' => 'không được lớn hơn :max ký tự.',
        'array' => 'không được có nhiều hơn :max phần tử.',
    ],
    'mimes' => 'phải là một tệp loại: :values.',
    'mimetypes' => 'phải là một tệp loại: :values.',
    'min' => [
        'numeric' => 'phải tối thiểu là :min.',
        'file' => 'phải có tối thiểu :min kilobytes.',
        'string' => 'phải có tối thiểu :min ký tự.',
        'array' => 'phải có tối thiểu :min phần tử.',
    ],
    'not_in' => 'không hợp lệ.',
    'not_regex' => 'có định dạng không hợp lệ.',
    'numeric' => 'phải là một số.',
    'present' => 'phải tồn tại.',
    'regex' => 'có định dạng không hợp lệ.',
    'required' => 'là bắt buộc.',
    'required_if' => 'là bắt buộc khi :other là :value.',
    'required_unless' => 'là bắt buộc trừ khi :other nằm trong :values.',
    'required_with' => 'là bắt buộc khi có :values.',
    'required_with_all' => 'là bắt buộc khi có tất cả :values.',
    'required_without' => 'là bắt buộc khi không có :values.',
    'required_without_all' => 'là bắt buộc khi không có bất kỳ :values nào.',
    'same' => 'và :other phải giống nhau.',
    'size' => [
        'numeric' => 'phải bằng :size.',
        'file' => 'phải có dung lượng :size kilobytes.',
        'string' => 'phải có :size ký tự.',
        'array' => 'phải chứa :size phần tử.',
    ],
    'starts_with' => 'phải bắt đầu bằng một trong các giá trị sau: :values.',
    'string' => 'phải là chuỗi.',
    'timezone' => 'phải là một múi giờ hợp lệ.',
    'unique' => 'đã được sử dụng.',
    'uploaded' => 'tải lên thất bại.',
    'url' => 'không đúng định dạng URL.',
    'uuid' => 'phải là UUID hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'tùy chỉnh-thông báo',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

//    'attributes' => [],
    'attributes' => function ($key) {
        return 'Trường';
    },

];
