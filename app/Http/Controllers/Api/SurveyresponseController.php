<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SurveyResponse;
use App\Http\Resources\SurveyResponseResource;
use Illuminate\Http\Request;

class SurveyResponseController extends Controller
{
    /**
     * 🔹 عرض جميع استجابات الموظفين على استبيان معين
     */
    public function index(Request $request)
    {
        $request->validate([
            'survey_id' => 'required|exists:surveys,id',
        ]);

        $surveyId = $request->survey_id;

        $responses = SurveyResponse::with([
            'user',                  // بيانات الموظف
            'answers.question' // الأسئلة المرتبطة بالإجابات
        ])
        ->where('survey_id', $surveyId)
        ->get();

        return SurveyResponseResource::collection($responses);
    }

    /**
     * 🔹 إنشاء استجابة لاستبيان
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'survey_id' => 'required|exists:surveys,id',
        ]);

        $data['user_id'] = auth()->id();

        $surveyResponse = SurveyResponse::create($data);

        // تحميل العلاقات
        $surveyResponse->load(['survey.questions', 'answers']);

        return new SurveyResponseResource($surveyResponse);
    }

    /**
     * 🔹 عرض استجابة واحدة لموظف معيّن
     */
    public function show(SurveyResponse $surveyResponse)
    {
        $surveyResponse->load('answers');
        return new SurveyResponseResource($surveyResponse);
    }
}