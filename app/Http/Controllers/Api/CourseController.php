<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

        /**
     * عرض جميع الدورات.
     *
     * @return \Illuminate\Http\JsonResponse
     */
 public function index()
{
    $courses = $this->courseService->getAllCourses();
    return CourseResource::collection($courses); // سيتم تحويل الكائنات إلى JSON بشكل صحيح
}

        /**
     * إضافة دورة جديدة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', Course::class);  // التحقق من صلاحية المستخدم

        $course = $this->courseService->createCourse($request);
        return new CourseResource($course);
    }

    public function show($id)
{
    $course = $this->courseService->getCourseById($id);

    if (!$course) {
        return response()->json(['message' => 'Course not found'], 404);
    }

    $this->authorize('view', $course); // التحقق من صلاحية المستخدم لعرض الدورة

    return new CourseResource($course);
}

        /**
     * تحديث دورة معينة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $course = $this->courseService->getCourseById($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $this->authorize('update', $course);  // التحقق من صلاحية المستخدم

        $updatedCourse = $this->courseService->updateCourse($request, $id);
        return new CourseResource($updatedCourse);
    }

     /**
     * حذف دورة معينة.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $course = $this->courseService->getCourseById($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $this->authorize('delete', $course);  // التحقق من صلاحية المستخدم

        $this->courseService->deleteCourse($id);
        return response()->json(['message' => 'Course deleted successfully']);
    }

}
