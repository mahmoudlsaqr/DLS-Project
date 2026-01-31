<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => 'يجب قبول :attribute',
    'active_url'           => ':attribute لا يُمثّل رابطًا صحيحًا',
    'after'                => 'يجب على :attribute أن يكون تاريخًا لاحقًا للتاريخ :date.',
    'after_or_equal'       => ':attribute يجب أن يكون تاريخاً لاحقاً أو مطابقاً للتاريخ :date.',
    'alpha'                => 'يجب أن لا يحتوي :attribute سوى على حروف',
    'alpha_dash'           => 'يجب أن لا يحتوي :attribute سوى على حروف، أرقام ومطّات.',
    'alpha_num'            => 'يجب أن يحتوي :attribute على حروفٍ وأرقامٍ فقط',
    'array'                => 'يجب أن يكون :attribute ًمصفوفة',
    'before'               => 'يجب على :attribute أن يكون تاريخًا سابقًا للتاريخ :date.',
    'before_or_equal'      => ':attribute يجب أن يكون تاريخا سابقا أو مطابقا للتاريخ :date',
    'between'              => [
        'numeric' => 'يجب أن تكون قيمة :attribute بين :min و :max.',
        'file'    => 'يجب أن يكون حجم الملف :attribute بين :min و :max كيلوبايت.',
        'string'  => 'يجب أن يكون عدد حروف النّص :attribute بين :min و :max',
        'array'   => 'يجب أن يحتوي :attribute على عدد من العناصر بين :min و :max',
    ],
    'boolean'              => 'يجب أن تكون قيمة :attribute إما true أو false ',
    'confirmed'            => 'حقل التأكيد غير مُطابق للحقل :attribute',
    'date'                 => ':attribute ليس تاريخًا صحيحًا',
    'date_format'          => 'لا يتوافق :attribute مع الشكل :format.',
    'different'            => 'يجب أن يكون الحقلان :attribute و :other مُختلفان',
    'digits'               => 'يجب أن يحتوي :attribute على :digits رقمًا/أرقام',
    'digits_between'       => 'يجب أن يحتوي :attribute بين :min و :max رقمًا/أرقام ',
    'dimensions'           => 'الـ :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct'             => 'للحقل :attribute قيمة مُكرّرة.',
    'email'                => 'يجب أن يكون :attribute عنوان بريد إلكتروني صحيح البُنية',
    'exists'               => 'القيمة المحددة :attribute غير موجودة',
    'file'                 => 'الـ :attribute يجب أن يكون ملفا.',
    'filled'               => ':attribute إجباري',
    'gt'                   => [
        'numeric' => 'يجب أن تكون قيمة :attribute أكبر من :max.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أكبر من :value كيلوبايت',
        'string'  => 'يجب أن يكون طول النّص :attribute أكثر من :value حروفٍ/حرفًا',
        'array'   => 'يجب أن يحتوي :attribute على أكثر من :value عناصر/عنصر.',
    ],
    'gte'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أكبر من :min.',
        'file'    => 'يجب أن يكون حجم الملف :attribute على الأقل :value كيلوبايت',
        'string'  => 'يجب أن يكون طول النص :attribute على الأقل :value حروفٍ/حرفًا',
        'array'   => 'يجب أن يحتوي :attribute على الأقل على :value عُنصرًا/عناصر',
    ],
    'image'                => 'يجب أن يكون :attribute صورةً',
    'in'                   => ':attribute غير موجود',
    'in_array'             => ':attribute غير موجود في :other.',
    'integer'              => 'يجب أن يكون :attribute عددًا صحيحًا',
    'ip'                   => 'يجب أن يكون :attribute عنوان IP صحيحًا',
    'ipv4'                 => 'يجب أن يكون :attribute عنوان IPv4 صحيحًا.',
    'ipv6'                 => 'يجب أن يكون :attribute عنوان IPv6 صحيحًا.',
    'json'                 => 'يجب أن يكون :attribute نصآ من نوع JSON.',
    'lt'                   => [
        'numeric' => 'يجب أن تكون قيمة :attribute أصغر من :max.',
        'file'    => 'يجب أن يكون حجم الملف :attribute أصغر من :value كيلوبايت',
        'string'  => 'يجب أن يكون طول النّص :attribute أقل من :value حروفٍ/حرفًا',
        'array'   => 'يجب أن يحتوي :attribute على أقل من :value عناصر/عنصر.',
    ],
    'lte'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أصغر من :max.',
        'file'    => 'يجب أن لا يتجاوز حجم الملف :attribute :max كيلوبايت',
        'string'  => 'يجب أن لا يتجاوز طول النّص :attribute :max حروفٍ/حرفًا',
        'array'   => 'يجب أن لا يحتوي :attribute على أكثر من :max عناصر/عنصر.',
    ],
    'max'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أصغر من :max.',
        'file'    => 'يجب أن لا يتجاوز حجم الملف :attribute :max كيلوبايت',
        'string'  => 'يجب أن لا يتجاوز طول النّص :attribute :max حروفٍ/حرفًا',
        'array'   => 'يجب أن لا يحتوي :attribute على أكثر من :max عناصر/عنصر.',
    ],
    'mimes'                => 'يجب أن يكون ملفًا من نوع : :values.',
    'mimetypes'            => 'يجب أن يكون ملفًا من نوع : :values.',
    'min'                  => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية أو أكبر من :min.',
        'file'    => 'يجب أن يكون حجم الملف :attribute على الأقل :min كيلوبايت',
        'string'  => 'يجب أن يكون طول النص :attribute على الأقل :min حروفٍ/حرفًا',
        'array'   => 'يجب أن يحتوي :attribute على الأقل على :min عُنصرًا/عناصر',
    ],
    'not_in'               => ':attribute موجود',
    'not_regex'            => 'صيغة :attribute غير صحيحة.',
    'numeric'              => 'يجب على :attribute أن يكون رقمًا',
    'present'              => 'يجب تقديم :attribute',
    'regex'                => 'صيغة :attribute .غير صحيحة',
    'required'             => ':attribute مطلوب.',
    'required_if'          => ':attribute مطلوب في حال ما إذا كان :other يساوي :value.',
    'required_unless'      => ':attribute مطلوب في حال ما لم يكن :other يساوي :values.',
    'required_with'        => ':attribute مطلوب إذا توفّر :values.',
    'required_with_all'    => ':attribute مطلوب إذا توفّر :values.',
    'required_without'     => ':attribute مطلوب إذا لم يتوفّر :values.',
    'required_without_all' => ':attribute مطلوب إذا لم يتوفّر :values.',
    'same'                 => 'يجب أن يتطابق :attribute مع :other',
    'size'                 => [
        'numeric' => 'يجب أن تكون قيمة :attribute مساوية لـ :size',
        'file'    => 'يجب أن يكون حجم الملف :attribute :size كيلوبايت',
        'string'  => 'يجب أن يحتوي النص :attribute على :size حروفٍ/حرفًا بالضبط',
        'array'   => 'يجب أن يحتوي :attribute على :size عنصرٍ/عناصر بالضبط',
    ],
    'string'               => 'يجب أن يكون :attribute نصآ.',
    'timezone'             => 'يجب أن يكون :attribute نطاقًا زمنيًا صحيحًا',
    'unique'               => 'قيمة :attribute مُستخدمة من قبل',
    'uploaded'             => 'فشل في تحميل الـ :attribute',
    'url'                  => 'صيغة الرابط :attribute غير صحيحة',

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
            'rule-name' => 'custom-message',
        ],

        'center_admin_mobile' => [
            'starts_with' => 'يجب أن يبدأ رقم جوال مدير المركز بـ 05.',
            'max' => 'يجب ألا يزيد رقم جوال مدير المركز عن 10 أرقام.',
            'min' => 'يجب ألا يقل رقم جوال مدير المركز عن 10 أرقام.',
        ],
        'center_mobile' => [
            'starts_with' => 'يجب أن يبدأ رقم جوال المركز بـ 05.',
            'max' => 'يجب ألا يزيد رقم جوال المركز عن 10 أرقام.',
            'min' => 'يجب ألا يقل رقم جوال المركز عن 10 أرقام.',
        ],
        'center_phone' => [
            'starts_with' => 'يجب أن يبدأ رقم الهاتف الأرضي بـ 01.',
            'max' => 'يجب ألا يزيد رقم الهاتف عن 10 أرقام.',
            'min' => 'يجب ألا يقل رقم الهاتف عن 10 أرقام.',
        ],
        'center_email' => [
            'email' => 'يجب إدخال بريد إلكتروني صالح للمركز.',
        ],
        'center_admin_email' => [
            'email' => 'يجب إدخال بريد إلكتروني صالح لمدير المركز.',
        ],
        'additional_mobile_1' => [
            'starts_with' => 'يجب أن يبدأ الجوال الإضافي 1 بـ 05.',
            'max' => 'يجب ألا يزيد الجوال الإضافي 1 عن 10 أرقام.',
            'min' => 'يجب ألا يقل الجوال الإضافي 1 عن 10 أرقام.',
        ],
        'additional_mobile_2' => [
            'starts_with' => 'يجب أن يبدأ الجوال الإضافي 2 بـ 05.',
            'max' => 'يجب ألا يزيد الجوال الإضافي 2 عن 10 أرقام.',
            'min' => 'يجب ألا يقل الجوال الإضافي 2 عن 10 أرقام.',
        ],
        'additional_mobile_3' => [
            'starts_with' => 'يجب أن يبدأ الجوال الإضافي 3 بـ 05.',
            'max' => 'يجب ألا يزيد الجوال الإضافي 3 عن 10 أرقام.',
            'min' => 'يجب ألا يقل الجوال الإضافي 3 عن 10 أرقام.',
        ],
        'additional_mobile_4' => [
            'starts_with' => 'يجب أن يبدأ الجوال الإضافي 4 بـ 05.',
            'max' => 'يجب ألا يزيد الجوال الإضافي 4 عن 10 أرقام.',
            'min' => 'يجب ألا يقل الجوال الإضافي 4 عن 10 أرقام.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name'                  => 'الاسم',
        'username'              => 'اسم المُستخدم',
        'email'                 => 'البريد الالكتروني',
        'first_name'            => 'الاسم الأول',
        'last_name'             => 'اسم العائلة',
        'password'              => 'كلمة السر',
        'password_confirmation' => 'تأكيد كلمة السر',
        'city'                  => 'المدينة',
        'country'               => 'الدولة',
        'address'               => 'عنوان السكن',
        'phone'                 => 'الهاتف',
        'mobile'                => 'الجوال',
        'age'                   => 'العمر',
        'sex'                   => 'الجنس',
        'gender'                => 'النوع',
        'day'                   => 'اليوم',
        'month'                 => 'الشهر',
        'year'                  => 'السنة',
        'hour'                  => 'ساعة',
        'minute'                => 'دقيقة',
        'second'                => 'ثانية',
        'title'                 => 'العنوان',
        'content'               => 'المُحتوى',
        'description'           => 'الوصف',
        'excerpt'               => 'المُلخص',
        'date'                  => 'التاريخ',
        'time'                  => 'الوقت',
        'available'             => 'مُتاح',
        'size'                  => 'الحجم',
        'code'                  => 'الكود',
        'tin'                   => 'الرقم الضريبي',
        'com_reg'               => 'السجل التجاري',
        'region_id'             => 'المنطقة',
        'district_id'           => 'الحي',
        'city_id'               => 'المدينة',
        'status'                => 'الحالة',
        'longitude'             => 'خط الطول',
        'latitude'              => 'خط العرض',
        'target'                => 'الهدف',
        'created_by'            => 'قام بالإضافة',
        'updated_by'            => 'قام بالتعديل',
        'case_id'               => 'الحالة',
        'type'                  => 'النوع',
        'file'                  => 'المرفق',
        'case_number'           => 'رقم الحالة',
        'patient_name'          => 'اسم المريض',
        'physician'             => 'الطبيب المعالج',
        'supervising'           => 'الطبيب المشرف',
        'note'                  => 'الملاحظة',
        'name_ar'               => 'الإسم عربي',
        'name_en'               => 'الإسم إنجليزي',
        'Numeric_3'             => 'كود الأيزو',
        'Alpha_2'               => 'الترميز الثنائي',
        'Alpha_3'               => 'الترميز الثلاثي',
        'Nat_Ar'                => 'إسم الجنسية عربي',
        'Nat_En'                => 'إسم الجنسية إنجليزي',
        'Country_Ar'            => 'إسم القرية عربي',
        'Country_EN'            => 'إسم القرية إنجليزي',
        "clinic_name"           => 'إسم العيادة',
        "amount"                => 'السعر',
        'population'            => 'السكان',
        "reason_for_rejection"  => 'سبب الرفض',
        "rejected_by"           => 'قام بالرفض',
        'role'                  => 'الصلاحية',
        'avatar'                => 'الصورة الرمزية',
        'last_active'           => 'آخر نشاط',
        'doctor_name'           => 'اسم الطبيب',
        'file_number'           => 'رقم الملف',
        'type_case'             => 'نوع الحالة',
        'number_teeth'          => 'عدد الأسنان',
        'color_code'            => 'رمز اللون',
        'duration_delivery'     => 'مدة التسليم',
        'crowns'                => 'تيجان',
        'bridges'               => 'جسور',
        'rpd' => 'RPD',
        'rcd' => 'RCD',
        'request_status' => 'حالة الطلب',
        'notes' => 'ملاحظات',
        'delivered_by' => 'تم التسليم بواسطة',
        'delivered_at' => 'تاريخ التسليم',
        'ip_address' => 'عنوان IP',

        'work_order_number' => 'رقم أمر العمل',
        'work_order_date' => 'تاريخ أمر العمل',
        'branch_id' => 'الفرع',
        'doctor_id' => 'الطبيب',
        'client_id' => 'العميل',
        'color_id' => 'اللون',
        'service_id' => 'الخدمة',
        'service_count' => 'عدد الخدمات',
        'service_price' => 'سعر الخدمة',
        'services_price' => 'إجمالي أسعار الخدمات',
        'service_discount' => 'نسبة خصم الخدمة',
        'service_discount_value' => 'قيمة خصم الخدمة',
        'services_discount_value' => 'إجمالي قيمة الخصومات',
        'service_total_net' => 'الصافي بعد الخصم',
        'service_tax' => 'نسبة الضريبة',
        'service_tax_value' => 'قيمة الضريبة',
        'service_total' => 'الإجمالي بعد الضريبة',
        'statement' => 'البيان',
        'phase_id' => 'المرحلة',

    ],
];
