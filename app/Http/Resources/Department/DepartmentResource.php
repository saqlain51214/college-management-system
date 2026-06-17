<?php

namespace App\Http\Resources\Department;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'name_urdu'        => $this->name_urdu,
            'slug'             => $this->slug,
            'code'             => $this->code,
            'type'             => $this->type?->value,
            'type_label'       => $this->type?->label(),
            'hod_name'         => $this->hod_name,
            'hod_designation'  => $this->hod_designation,
            'hod_photo_url'    => $this->hod_photo_url,
            'hod_message'      => $this->hod_message,
            'description'      => $this->description,
            'vision'           => $this->vision,
            'mission'          => $this->mission,
            'banner_image_url' => $this->banner_image_url,
            'email'            => $this->email,
            'phone'            => $this->phone,
            'sort_order'       => $this->sort_order,
            'is_active'        => $this->is_active,
            'show_on_website'  => $this->show_on_website,
            'status'           => $this->status->label(),
            'created_at'       => $this->created_at?->toDateString(),
        ];
    }
}
