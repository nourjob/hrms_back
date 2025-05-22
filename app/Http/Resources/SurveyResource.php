<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResource extends JsonResource
{
    public function toArray($request)
    { 
        return [
            'id' => $this->id,

            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,

            'survey_response_id' => $this->survey_response_id,

            'url' => $this->when($this->type === 'external', $this->url),

            'is_active' => $this->is_active,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),

            'target_department' => new DepartmentResource($this->whenLoaded('department')),
            'target_roles' => $this->target_roles,

            'created_by' => $this->creator?->name,

            'questions' => SurveyQuestionResource::collection($this->whenLoaded('questions')),

            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}