<?php

namespace App\Http\Requests\Department;

use App\Enums\DepartmentTypeEnum;
use App\Http\Requests\Shared\BaseFormRequest;
use App\Http\Requests\Shared\CommonRules;

class UpdateDepartmentRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $id = $this->route('department');

        return [
            'name'            => array_merge(CommonRules::name(), ["unique:departments,name,{$id}"]),
            'name_urdu'       => CommonRules::urduName(),
            'code'            => array_merge(CommonRules::code(), ["unique:departments,code,{$id}"]),
            'type'            => ['required', 'string', 'in:' . implode(',', array_column(DepartmentTypeEnum::cases(), 'value'))],
            'hod_name'        => CommonRules::name(false),
            'hod_designation' => ['nullable', 'string', 'max:100'],
            'hod_photo'       => array_merge(['sometimes'], CommonRules::image()),
            'hod_message'     => CommonRules::richText(),
            'description'     => CommonRules::description(),
            'vision'          => CommonRules::richText(),
            'mission'         => CommonRules::richText(),
            'banner_image'    => array_merge(['sometimes'], CommonRules::image()),
            'email'           => CommonRules::email(),
            'phone'           => CommonRules::phone(),
            'sort_order'      => CommonRules::sortOrder(),
            'is_active'       => CommonRules::isActive(),
            'show_on_website' => CommonRules::isActive(),
        ];
    }

    public function attributes(): array
    {
        return [
            'name'            => 'Department Name',
            'name_urdu'       => 'Department Name (Urdu)',
            'code'            => 'Department Code',
            'type'            => 'Department Type',
            'hod_name'        => 'HOD Name',
            'hod_designation' => 'HOD Designation',
            'hod_photo'       => 'HOD Photo',
            'hod_message'     => 'Message from HOD',
            'description'     => 'Description',
            'banner_image'    => 'Banner Image',
            'email'           => 'Email Address',
            'phone'           => 'Phone Number',
            'sort_order'      => 'Sort Order',
        ];
    }
}
