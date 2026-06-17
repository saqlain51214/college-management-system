<?php

namespace App\Http\Resources\AcademicProgram;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcademicProgramResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'department_id'      => $this->department_id,
            'department'         => $this->whenLoaded('department', fn() => [
                'id'   => $this->department->id,
                'name' => $this->department->name,
                'code' => $this->department->code,
            ]),
            'name'               => $this->name,
            'short_name'         => $this->short_name,
            'name_urdu'          => $this->name_urdu,
            'slug'               => $this->slug,
            'code'               => $this->code,
            'degree_type'        => $this->degree_type?->value,
            'degree_type_label'  => $this->degree_type?->label(),
            'duration_years'     => $this->duration_years,
            'total_semesters'    => $this->total_semesters,
            'total_credit_hours' => $this->total_credit_hours,
            'duration_label'     => $this->duration_label,
            'description'        => $this->description,
            'eligibility'        => $this->eligibility,
            'scope'              => $this->scope,
            'banner_image_url'   => $this->banner_image_url,
            'is_active'          => $this->is_active,
            'show_on_website'    => $this->show_on_website,
            'sort_order'         => $this->sort_order,
            'created_at'         => $this->created_at?->toDateString(),
        ];
    }
}
