<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\User;
use App\Models\Department;
use App\Models\Course;
use App\Policies\UserPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\CoursePolicy;
use App\Policies\RequestPolicy;
use App\Models\UserRequest;
use App\Models\StatementRequest;
use App\Policies\StatementRequestPolicy;
use App\Policies\LeaveRequestPolicy;
use App\Models\LeaveRequest;
use App\Models\Survey;
use App\Policies\SurveyPolicy;
use App\Models\SurveyQuestion;
use App\Policies\SurveyQuestionPolicy;
use App\Models\SurveyAnswer;
use App\Policies\SurveyAnswerPolicy;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */


    protected $policies = [
        User::class => UserPolicy::class,
        Course::class => CoursePolicy::class,
        Department::class => DepartmentPolicy::class,
        LeaveRequest::class => LeaveRequestPolicy::class,
        StatementRequest::class => StatementRequestPolicy::class,
        \App\Models\CourseRequest::class => \App\Policies\CourseRequestPolicy::class,
        Survey::class => SurveyPolicy::class,
        \App\Models\SurveyResponse::class => \App\Policies\SurveyResponsePolicy::class,
        SurveyAnswer::class => \App\Policies\SurveyAnswerPolicy::class,
        SurveyQuestion::class => SurveyQuestionPolicy::class,
        SurveyAnswer::class => SurveyAnswerPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
