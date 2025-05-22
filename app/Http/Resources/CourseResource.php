<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,  // تأكد من أن `name` هو الحقل الصحيح في الجدول
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'instructor' => $this->instructor,
            'available' => (bool) $this->available,
            'created_at' => $this->created_at->toDateString(),
        ];
    }
}
