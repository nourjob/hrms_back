<?php
namespace App\Services;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseService
{
    /**
     * إنشاء دورة جديدة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Course
     */
    public function createCourse(Request $request): Course
    {
        return Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'instructor' => $request->instructor,
            'available' => true,  // الدورة متاحة بشكل افتراضي
        ]);
    }

    /**
     * تحديث دورة معينة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \App\Models\Course|null
     */
    public function updateCourse(Request $request, int $id): ?Course
    {
        $course = Course::find($id);

        if (!$course) {
            return null;  // الدورة غير موجودة
        }

        $course->update($request->only([
            'name', 'description', 'start_date', 'end_date', 'location', 'instructor', 'available'
        ]));

        return $course;
    }

    /**
     * حذف دورة معينة.
     *
     * @param  int  $id
     * @return bool
     */
    public function deleteCourse(int $id): bool
    {
        $course = Course::find($id);

        if (!$course) {
            return false;  // الدورة غير موجودة
        }

        $course->delete();

        return true;
    }

    /**
     * الحصول على جميع الدورات.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllCourses()
    {
        return Course::all();
    }

    /**
     * الحصول على دورة معينة.
     *
     * @param  int  $id
     * @return \App\Models\Course|null
     */
    public function getCourseById(int $id): ?Course
    {
        return Course::find($id);
    }
}
