<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SurveyAnswerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'question' => [
                'id' => $this->question->id ?? null,
                'text' => $this->question->question_text ?? null,
                'type' => $this->question->question_type ?? null,
            ],
            'answer' => $this->question && $this->question->question_type === 'file'
                ? null // أو يمكن حذفه لو لا تريد عرض الإجابة كمسار نصي
                : $this->answer,

            'file' => $this->question && $this->question->question_type === 'file'
                ? asset('storage/' . $this->answer)
                : null,

            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}