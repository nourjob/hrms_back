<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyQuestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'question_text' => $this->question_text,
            'question_type' => $this->question_type,
            'options' => $this->when(in_array($this->question_type, ['multiple_choice', 'multiple_boolean']), json_decode($this->options, true)),
            'required' => $this->required,
            'order' => $this->order,
            'survey_start_date' => optional($this->survey)->start_date?->toDateTimeString(),
        ];
    }
}