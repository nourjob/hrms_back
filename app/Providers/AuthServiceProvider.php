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
        Request::class => RequestPolicy::class,


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
