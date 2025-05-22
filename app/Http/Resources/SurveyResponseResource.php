<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyResponseResource extends JsonResource
{
    public function toArray($request)
    { 
        return [
            'id' => $this->id,
            'survey_id' => $this->survey_id,
            'user_id' => $this->user_id,
            'user_name' => $this->user?->name,
            'survey' => new SurveyResource(
                $this->whenLoaded('survey')
            ),
            'answers' => SurveyAnswerResource::collection(
                $this->whenLoaded('answers')  // تحميل العلاقة مسبقًا
            ),
            'created_at' => optional($this->created_at)->toDateTimeString(),
            'updated_at' => optional($this->updated_at)->toDateTimeString(),
        ];
    }
}