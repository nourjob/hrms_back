<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveyAnswerResource;
use App\Models\SurveyResponse;
use App\Services\SurveyAnswerService;
use Illuminate\Http\Request;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;

class SurveyAnswerController extends Controller
{
    protected $surveyAnswerService;

    public function __construct(SurveyAnswerService $surveyAnswerService)
    {
        $this->surveyAnswerService = $surveyAnswerService;
    }

    /**
     * إضافة إجابة لسؤال
     */
    public function store(Request $request, SurveyResponse $response)
    {
        // ✅ تحويل البيانات إذا كانت على الشكل المبسط
        if ($request->has('survey_question_id') && is_array($request->input('answers'))) {
            // 👇 نحصل على السؤال ونتأكد من نوعه
        $question = \App\Models\SurveyQuestion::find($request->input('survey_question_id'));

        if ($question && $question->question_type === 'multiple_boolean') {
        // ✅ نمرر كل المصفوفة كإجابة واحدة
        $convertedAnswers = [[
            'survey_question_id' => $request->input('survey_question_id'),
            'answer' => $request->input('answers'),
        ]];
        } else {
        // 🔁 النوع الآخر: نقسم كل قيمة لإجابة مستقلة
        $convertedAnswers = collect($request->input('answers'))->map(function ($answer) use ($request) {
            return [
                'survey_question_id' => $request->input('survey_question_id'),
                'answer' => $answer,
            ];
        })->toArray();
        }   


            // دمج البيانات الجديدة داخل الـ request
            $request->merge([
                'answers' => $convertedAnswers,
            ]);
        }

        // ✅ الفاليديشن المعتاد
        $data = $request->validate([
            'answers' => 'required|array',
            'answers.*.survey_question_id' => 'required|exists:survey_questions,id',
            'answers.*.answer' => 'nullable',
            'answers.*.file' => 'nullable|file',
        ]);

        $savedAnswers = [];

        foreach ($data['answers'] as $index => $answerData) {
            // ✅ إذا لم يتم إدخال إجابة ولا ملف، تجاوز هذا الإدخال
            if (empty($answerData['answer']) && !$request->hasFile("answers.$index.file")) {
                continue;
            }
            try {
                $answerData['survey_response_id'] = $response->id;

                // تمرير الملف إذا كان موجودًا
                if ($request->hasFile("answers.$index.file")) {
                    $answerData['file'] = $request->file("answers.$index.file");
                }

                $savedAnswers[] = $this->surveyAnswerService->createSurveyAnswer($response, $answerData);

            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'فشل في حفظ إحدى الإجابات.',
                    'error' => $e->getMessage(),
                    'question_id' => $answerData['survey_question_id'],
                ], 422);
            }
        }

        return response()->json([
            'message' => 'تم حفظ جميع الإجابات بنجاح.',
            'answers' => $savedAnswers,
        ]);
    }





    /**
     * عرض إجابة معينة
     */
    public function show(SurveyResponse $response, SurveyAnswer $answer)
    {
        if ($answer->survey_response_id !== $response->id) {
            return response()->json(['message' => 'الإجابة لا تتبع هذه الاستجابة.'], 404);
        }
    
        return new SurveyAnswerResource($answer);
    }
}