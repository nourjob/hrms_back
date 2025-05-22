<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use App\Models\SurveyResponse;
use App\Models\SurveyQuestion;
use App\Models\Survey;
use App\Models\SurveyAnswer;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
{
    $this->configureRateLimiting();

    // ✅ Custom model binding (قبل routes)
    Route::model('response', SurveyResponse::class);

    // ✅ Ensure the answer belongs to the survey response
    Route::bind('answer', function ($value) {
        $responseId = request()->route('response')?->id ?? request()->route('response');
        return SurveyAnswer::where('id', $value)
            ->where('survey_response_id', $responseId)
            ->firstOrFail();
    });

    // ✅ Ensure question belongs to the right survey
    Route::bind('question', function ($value, $route) {
        $surveyParam = $route->parameter('survey');

        $survey = $surveyParam instanceof \App\Models\Survey
            ? $surveyParam
            : \App\Models\Survey::findOrFail($surveyParam);

        return \App\Models\SurveyQuestion::where('survey_id', $survey->id)
            ->where('id', $value)
            ->firstOrFail();
    });

    // ✅ Register routes
    $this->routes(function () {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    });
}


    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
