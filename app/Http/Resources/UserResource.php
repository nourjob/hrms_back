<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * تحويل البيانات إلى JSON.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'job_number' => $this->job_number,
            'status' => $this->status,
            'department' => $this->department ? $this->department->name : null,  // تحقق من وجود department
            'manager' => $this->manager ? $this->manager->name : null,  // تحقق من وجود manager
            'marital_status' => $this->marital_status,
            'number_of_children' => $this->number_of_children,
            'qualification' => $this->qualification,
            'phone' => $this->phone,
            'address' => $this->address,
            'university' => $this->university,
            'graduation_year' => $this->graduation_year,
            'created_at' => $this->created_at->toDateString(),
            'updated_at' => $this->updated_at->toDateString(),
        ];
    }
}
