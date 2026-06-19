<?php

namespace App\Http\Requests\AcademicProgram;

use App\Enums\AdmissionCategoryEnum;
use App\Enums\DegreeTypeEnum;
use App\Http\Requests\Shared\BaseFormRequest;
use App\Http\Requests\Shared\CommonRules;

class UpdateAcademicProgramRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $id = $this->route('academic_program');

        return [
            'department_id'      => CommonRules::foreignKey(required: false),
            'name'               => [...CommonRules::name(), "unique:academic_programs,name,{$id}"],
            'short_name'         => ['nullable', 'string', 'max:50'],
            'name_urdu'          => ['nullable', 'string', 'max:200'],
            'slug'               => [...CommonRules::slug(), "unique:academic_programs,slug,{$id}"],
            'code'               => [...CommonRules::code(), "unique:academic_programs,code,{$id}"],
            'degree_type'        => ['required', 'string', 'in:' . implode(',', array_column(DegreeTypeEnum::cases(), 'value'))],
            'admission_category' => ['required', 'string', 'in:' . implode(',', array_column(AdmissionCategoryEnum::cases(), 'value'))],
            'duration_years'     => ['required', 'integer', 'min:1', 'max:10'],
            'total_semesters'    => ['required', 'integer', 'min:1', 'max:20'],
            'total_credit_hours' => ['nullable', 'integer', 'min:1', 'max:300'],
            'description'        => ['nullable', 'string', 'min:10', 'max:3000'],
            'eligibility'        => ['nullable', 'string', 'max:3000'],
            'scope'              => ['nullable', 'string', 'max:3000'],
            'banner_image'       => CommonRules::image(),
            'is_active'          => CommonRules::isActive(),
            'show_on_website'    => CommonRules::isActive(),
            'sort_order'         => CommonRules::sortOrder(),
        ];
    }
}
