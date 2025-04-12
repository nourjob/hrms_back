<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,

            // تحقق من نوع البيانات قبل تطبيق format
            'start_date' => $this->start_date instanceof Carbon ? $this->start_date->format('Y-m-d') : Carbon::parse($this->start_date)->format('Y-m-d'),
            'end_date' => $this->end_date instanceof Carbon ? $this->end_date->format('Y-m-d') : Carbon::parse($this->end_date)->format('Y-m-d'),

            'location' => $this->location,
            'instructor' => $this->instructor,
            'available' => $this->available,

            // استخدام toDateTimeString للتواريخ إذا كانت كائنات Carbon
            'created_at' => $this->created_at instanceof Carbon ? $this->created_at->toDateTimeString() : Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at' => $this->updated_at instanceof Carbon ? $this->updated_at->toDateTimeString() : Carbon::parse($this->updated_at)->toDateTimeString(),
        ];
    }
}
