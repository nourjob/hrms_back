<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $approvable_type
 * @property int $approvable_id
 * @property int $approved_by
 * @property string $role
 * @property string $status
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $approvable
 * @property-read \App\Models\User $approver
 * @method static \Illuminate\Database\Eloquent\Builder|Approval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Approval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Approval query()
 * @method static \Illuminate\Database\Eloquent\Builder|Approval whereApprovableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Approval whereApprovableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Approval whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Approval whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Approval whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Approval whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Approval whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Approval whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Approval whereUpdatedAt($value)
 */
	class Approval extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $attachable_type
 * @property int $attachable_id
 * @property string $file_path
 * @property string $file_type
 * @property int $uploaded_by
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $attachable
 * @property-read \App\Models\User $uploader
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereAttachableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereAttachableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUploadedBy($value)
 */
	class Attachment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string $start_date
 * @property string $end_date
 * @property string|null $location
 * @property string $instructor
 * @property int $available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Approval> $approvals
 * @property-read int|null $approvals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CourseRequest> $courseRequests
 * @property-read int|null $course_requests_count
 * @method static \Illuminate\Database\Eloquent\Builder|Course newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Course query()
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereInstructor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Course whereUpdatedAt($value)
 */
	class Course extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $course_id
 * @property string|null $custom_course_title
 * @property string|null $custom_course_provider
 * @property string|null $link
 * @property string|null $reason
 * @property string $status
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\Course|null $course
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereCustomCourseProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereCustomCourseTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CourseRequest whereUserId($value)
 */
	class CourseRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $manager_id
 * @property-read \App\Models\User|null $manager
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Department newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Department query()
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Department whereUpdatedAt($value)
 */
	class Department extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $subtype
 * @property string $start_date
 * @property string $end_date
 * @property string $reason
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Approval> $approvals
 * @property-read int|null $approvals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereSubtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeaveRequest whereUserId($value)
 */
	class LeaveRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $body
 * @property string $type
 * @property string $related_type
 * @property int $related_id
 * @property int $is_read
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $related
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereRelatedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereRelatedType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $subtype
 * @property string $reason
 * @property string $status
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest whereSubtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StatementRequest whereUserId($value)
 */
	class StatementRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $type
 * @property string|null $url
 * @property int|null $target_department_id
 * @property array|null $target_roles
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property \Illuminate\Support\Carbon|null $end_date
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Department|null $department
 * @property-read mixed $survey_response_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SurveyQuestion> $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SurveyResponse> $responses
 * @property-read int|null $responses_count
 * @method static \Illuminate\Database\Eloquent\Builder|Survey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Survey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Survey query()
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereTargetDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereTargetRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Survey whereUrl($value)
 */
	class Survey extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $survey_response_id
 * @property int $survey_question_id
 * @property array|null $answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $file_path
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\SurveyQuestion $question
 * @property-read \App\Models\SurveyResponse $response
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyAnswer whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyAnswer whereSurveyQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyAnswer whereSurveyResponseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyAnswer whereUpdatedAt($value)
 */
	class SurveyAnswer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $survey_id
 * @property string $question_text
 * @property string $question_type
 * @property array|null $options
 * @property string|null $additional_data
 * @property bool $required
 * @property int $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $file_path
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SurveyAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Survey $survey
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereAdditionalData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereQuestionText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereSurveyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyQuestion whereUpdatedAt($value)
 */
	class SurveyQuestion extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $survey_id
 * @property int $user_id
 * @property string|null $submitted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SurveyAnswer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\Survey $survey
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyResponse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyResponse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyResponse query()
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyResponse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyResponse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyResponse whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyResponse whereSurveyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyResponse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SurveyResponse whereUserId($value)
 */
	class SurveyResponse extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $name
 * @property string $job_number
 * @property string $status
 * @property int|null $department_id
 * @property int|null $manager_id
 * @property string|null $marital_status
 * @property int|null $number_of_children
 * @property string|null $qualification
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $university
 * @property string|null $graduation_year
 * @property string|null $salary_details
 * @property string|null $status_details
 * @property int $survey_completed
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Approval> $approvals
 * @property-read int|null $approvals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CourseRequest> $courseRequests
 * @property-read int|null $course_requests_count
 * @property-read \App\Models\Department|null $department
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LeaveRequest> $leaveRequests
 * @property-read int|null $leave_requests_count
 * @property-read User|null $manager
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StatementRequest> $statementRequests
 * @property-read int|null $statement_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SurveyResponse> $surveyResponses
 * @property-read int|null $survey_responses_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGraduationYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereJobNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereManagerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNumberOfChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereQualification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSalaryDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatusDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurveyCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUniversity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $subtype
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string|null $reason
 * @property string $status
 * @property string|null $proof_file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Approval> $approvals
 * @property-read int|null $approvals_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereProofFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereSubtype($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRequest whereUserId($value)
 */
	class UserRequest extends \Eloquent {}
}

