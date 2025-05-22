<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveyQuestionResource;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Services\SurveyQuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SurveyQuestionController extends Controller
{
    protected $surveyQuestionService;

    public function __construct(SurveyQuestionService $surveyQuestionService)
    {
        $this->surveyQuestionService = $surveyQuestionService;
    }

    public function index(Survey $survey)
    {
        $this->authorize('view', $survey);

        return SurveyQuestionResource::collection($survey->questions);
    }

    public function show(Survey $survey, SurveyQuestion $question)
    {

        
        // تأكد أن السؤال ينتمي لنفس الاستبيان
        if ($question->survey_id !== $survey->id) {
            return response()->json([
                'message' => 'Question not found or does not belong to this survey.',
                'expected_survey_id' => $survey->id,
                'actual_survey_id' => $question->survey_id,
            ], 404);
            
        }

        $this->authorize('view', $survey);

        return new SurveyQuestionResource($question);
    }

    
    public function store(Request $request, Survey $survey)
{
    $this->authorize('create', $survey);

    $data = $request->validate([
        'question_text' => 'required|string|max:1000',
        'question_type' => 'required|in:text,multiple_choice,rating,boolean,long_text,multiple_boolean,range,date,file',
        'options' => 'nullable|array',
        'required' => 'boolean',
        'order' => 'nullable|integer',
    ]);

    // التحقق من وجود خيارات في حال كانت نوع السؤال يتطلب ذلك
    if (in_array($data['question_type'], ['multiple_choice', 'multiple_boolean']) && empty($data['options'])) {
        return response()->json(['message' => 'الرجاء إدخال خيارات السؤال.'], 422);
    }

    if (isset($data['options']) && !empty($data['options'])) {
        $data['options'] = json_encode($data['options']);
    }

    if ($data['question_type'] === 'file' && $request->hasFile('file')) {
        $data['file_path'] = $request->file('file')->store('uploads', 'public');
    }

    // إذا لم يتم إدخال ترتيب، احسبه تلقائياً كآخر سؤال
    if (empty($data['order'])) {
        $maxOrder = $survey->questions()->max('order') ?? 0;
        $data['order'] = $maxOrder + 1;
    }

    $question = $survey->questions()->create([
        'question_text' => $data['question_text'],
        'question_type' => $data['question_type'],
        'options' => $data['options'] ?? null,
        'file_path' => $data['file_path'] ?? null,
        'required' => $data['required'],
        'order' => $data['order'],
    ]);

    return new SurveyQuestionResource($question);
}



    public function update(Request $request, Survey $survey, SurveyQuestion $question)
    {
        if ($survey->start_date && now()->greaterThanOrEqualTo($survey->start_date)) {
            return response()->json([
                'message' => 'لا يمكن تعديل الأسئلة بعد بدء تاريخ الاستبيان.'
            ], 403);
        }

        $this->authorize('update', $survey);

        $data = $request->validate([
            'question_text' => 'required|string|max:1000',
            'question_type' => 'required|in:text,multiple_choice,rating,boolean,long_text,multiple_boolean,range,date,file',
            'options' => 'nullable|array',
            'file' => 'nullable|file',
            'required' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        // تحسين إضافي في update
        if ($data['question_type'] === 'file' && $request->hasFile('file')) {
            // حذف الملف القديم لو موجود
            if ($question->file_path) {
                Storage::disk('public')->delete($question->file_path);
            }
            $data['file_path'] = $request->file('file')->store('uploads', 'public');
        }

        // التعامل مع الخيارات إن وجدت
        if (in_array($data['question_type'], ['multiple_choice', 'multiple_boolean']) && empty($data['options'])) {
            return response()->json(['message' => 'الرجاء إدخال خيارات السؤال.'], 422);
        }

        if (isset($data['options']) && !empty($data['options'])) {
            $data['options'] = json_encode($data['options']);
        }

        // معالجة الملف إن وجد
        if ($data['question_type'] === 'file' && $request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('uploads', 'public');
        }

        $question = $this->surveyQuestionService->updateQuestion($question, $data);

        return new SurveyQuestionResource($question);
    }


    public function destroy(Survey $survey, SurveyQuestion $question)
{

    if ($question->survey_id !== $survey->id) {
        return response()->json(['message' => 'Question does not belong to this survey.'], 403);
    }

    $this->authorize('delete', $question);

    $this->surveyQuestionService->deleteQuestion($question);

    return response()->json(null, 204);
}

}