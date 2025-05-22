<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SurveyResource;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller 
{

    public function index()
    {
        $this->authorize('viewAny', Survey::class);

        $user = Auth::user();

        // ✅ admin أو hr يشوفون كل الاستبيانات بدون فلترة
        if ($user->hasRole('admin') || $user->hasRole('hr')) {
            return SurveyResource::collection(
                Survey::latest()->get()
            );
        }

        // ✅ فلترة كاملة للمستخدمين الآخرين (مثل الموظفين)
        $userRoleNames = $user->roles->pluck('name')->toArray();

        $surveys = Survey::query()
            ->where('is_active', true)
            // ✅ شرط أن يكون start_date <= اليوم أو null
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhere('start_date', '<=', now()->startOfDay());
            })
            // ✅ شرط end_date لم تنتهي بعد أو null
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>', now()); 
            })
            // ✅ فلترة بالقسم
            ->where(function ($query) use ($user) {
                $query->whereNull('target_department_id')
                    ->orWhere('target_department_id', $user->department_id);
            })
            // ✅ فلترة بالأدوار
            ->where(function ($query) use ($userRoleNames) {
                $query->whereNull('target_roles')
                    ->orWhereJsonContains('target_roles', $userRoleNames);
            })
            ->latest()
            ->get();

        return SurveyResource::collection($surveys);
    }





    public function show(Survey $survey)
    {
        $this->authorize('view', $survey);

        return new SurveyResource($survey->load(['creator', 'department', 'questions']));
    }



    
    public function store(Request $request)
    {
        $this->authorize('create', Survey::class); 

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:internal,external',
            'url' => 'nullable|url|required_if:type,external',
            'target_department_id' => 'nullable|exists:departments,id',
            'target_roles' => 'nullable|array',
            'target_roles.*' => 'string',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date|string',
            'end_date' => 'nullable|date|string|after_or_equal:start_date',
        ]);

        

        $data['created_by'] = Auth::id();

        $survey = Survey::create($data);

        // بمجرد إنشاء الاستبيان، إذا كان مفعلًا، نترك إشعار الإشعارات للمرحلة التالية
        return new SurveyResource($survey->load(['creator', 'department']));
    }

    
    public function update(Request $request, Survey $survey)
    {
        $this->authorize('update', $survey);

        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'in:internal,external',
            'url' => 'nullable|url|required_if:type,external',
            'target_department_id' => 'nullable|exists:departments,id',
            'target_roles' => 'nullable|array',
            'target_roles.*' => 'string',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date|string',
            'end_date' => 'nullable|date|string|after_or_equal:start_date',
        ]);

        $wasInactive = !$survey->is_active;
        $survey->update($data);

        // في حال تم تفعيل الاستبيان
        if ($wasInactive && $survey->is_active) {
            // هنا يمكننا إرسال إشعارات أو تنفيذ أشياء لاحقًا إذا تطلب الأمر
        }

        return new SurveyResource($survey->load(['creator', 'department']));
    }
    
    public function destroy(Survey $survey)
    {
        $this->authorize('delete', $survey);
        $survey->delete();
        return response()->json(null, 204);
    }
        



}