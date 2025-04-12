<?php

namespace App\Services;

use App\Models\Approval;
use App\Models\UserRequest;
use App\Models\CourseRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApprovalController;
class ApprovalService
{
    public function storeRequestApproval($approvable_id, $status, $comment)
    {
        $approvable = UserRequest::find($approvable_id);

        if (!$approvable) {
            return response()->json(['message' => 'UserRequest not found'], 404);
        }

        if ($approvable->status == 'approved' || $approvable->status == 'rejected') {
            return response()->json(['message' => 'Request already processed'], 400);
        }

        $approvable->status = $status;
        $approvable->save();

        $approval = Approval::create([
            'approvable_id' => $approvable_id,
            'approvable_type' => UserRequest::class,
            'approved_by' => Auth::id(),
            'role' => Auth::user()->role,
            'status' => $status,
            'comment' => $comment,
        ]);

        return $approval;
    }

    public function storeCourseRequestApproval($approvable_id, $status, $comment)
    {
        $approvable = CourseRequest::find($approvable_id);

        if (!$approvable) {
            return response()->json(['message' => 'CourseRequest not found'], 404);
        }

        if ($approvable->status == 'approved' || $approvable->status == 'rejected') {
            return response()->json(['message' => 'CourseRequest already processed'], 400);
        }

        $approvable->status = $status;
        $approvable->save();

        $approval = Approval::create([
            'approvable_id' => $approvable_id,
            'approvable_type' => CourseRequest::class,
            'approved_by' => Auth::id(),
            'role' => Auth::user()->role,
            'status' => $status,
            'comment' => $comment,
        ]);

        return $approval;
    }

    public function updateApproval($approval_id, $status, $comment)
    {
        $approval = Approval::find($approval_id);
        if (!$approval) {
            return response()->json(['message' => 'Approval not found'], 404);
        }

        if ($approval->approved_by != Auth::id() && Auth::user()->role != 'admin') {
            return response()->json(['message' => 'Unauthorized to update this approval'], 403);
        }

        $approvable = null;
        if ($approval->approvable_type == UserRequest::class) {
            $approvable = UserRequest::find($approval->approvable_id);
        } elseif ($approval->approvable_type == CourseRequest::class) {
            $approvable = CourseRequest::find($approval->approvable_id);
        }

        if (!$approvable) {
            return response()->json(['message' => 'Approving request not found'], 404);
        }

        if (($approvable->status === 'approved' || $approvable->status === 'rejected') && Auth::user()->role == 'hr') {
            return response()->json(['message' => 'HR user cannot update approved or rejected requests'], 400);
        }

        $approvable->status = $status;
        $approvable->save();

        $approval->status = $status;
        $approval->comment = $comment ?? $approval->comment;
        $approval->save();

        return $approval;
    }
}
