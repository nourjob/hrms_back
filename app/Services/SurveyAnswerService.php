<?php

namespace App\Services;

use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SurveyAnswerService
{
    public function createSurveyAnswer($response, $data)
    {

        $question = SurveyQuestion::find($data['survey_question_id']);
        if (!$question) {

        return response()->json(['message' => 'السؤال غير موجود.'], 404);
            
        }


        $answerData = [
            'survey_response_id' => $response->id,
            'survey_question_id' => $question->id,
        ];

        switch ($question->question_type) {
            case 'file':
                if (!isset($data['file'])) {
                    throw new \Exception('الملف مطلوب لهذا النوع من الأسئلة.');
                }

                $file = $data['file'];
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('survey_files', $filename, 'public');
                $answerData['answer'] = $path;
                break;

            case 'rating':
                if ($data['answer'] < 1 || $data['answer'] > 10) {
                    throw new \Exception('التقييم يجب أن يكون بين 1 و 10.');
                }
                $answerData['answer'] = $data['answer'];
                break;

            case 'boolean':
                if (!in_array($data['answer'], ['true', 'false', true, false, 1, 0, '1', '0'], true)) {
                    throw new \Exception('يجب أن تكون الإجابة true أو false.');
                }
                $answerData['answer'] = filter_var($data['answer'], FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
                break;

            case 'multiple_choice':
                if (!is_string($data['answer'])) {
                    throw new \Exception('الاختيار يجب أن يكون نصًا.');
                }
                $answerData['answer'] = $data['answer'];
                break;

            case 'multiple_boolean':
                if (!is_array($data['answer'])) {
                    throw new \Exception('الإجابة يجب أن تكون مصفوفة من القيم.');
                }

                // يمكن لاحقاً التأكد أن الإجابات متوافقة مع الاختيارات
                $answerData['answer'] = $data['answer'];
                break;

            case 'range':
                if (!is_numeric($data['answer'])) {
                    throw new \Exception('الإجابة يجب أن تكون رقمًا.');
                }

                $answerData['answer'] = $data['answer'];
                break;

            case 'date':
                if (!strtotime($data['answer'])) {
                    throw new \Exception('صيغة التاريخ غير صحيحة.');
                }
                $answerData['answer'] = $data['answer'];
                break;

            case 'text':
            default:
                $answerData['answer'] = $data['answer'];
                break;
        }

        return SurveyAnswer::create($answerData);
    }
}