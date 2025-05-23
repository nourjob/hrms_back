<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\StatementRequest;
use App\Models\CourseRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Http\Resources\StatementRequestResource;
use App\Http\Resources\CourseRequestResource;
use Illuminate\Database\Eloquent\Builder;

class RequestController extends Controller
{
    public function getAllFilteredRequests(Request $request)
    {
        $userName = $request->input('user_name');
        $type = $request->input('type');
        $status = $request->input('status');
        $date = $request->input('date');

        // الاستعلامات الأساسية
$leaveRequestsQuery = LeaveRequest::with(['user', 'attachments', 'approvals']);
        $statementRequestsQuery = StatementRequest::with(['user', 'attachments']);
        $courseRequestsQuery = CourseRequest::with(['user', 'attachments', 'course']);

        // فلترة النوع - إذا تم تحديد النوع، نجعل الباقي Collections فارغة
        if ($type === 'leave') {
            $statementRequestsQuery = collect();
            $courseRequestsQuery = collect();
        } elseif ($type === 'statement') {
            $leaveRequestsQuery = collect();
            $courseRequestsQuery = collect();
        } elseif ($type === 'course') {
            $leaveRequestsQuery = collect();
            $statementRequestsQuery = collect();
        }

        // فلترة الحالة
        if ($status) {
            if ($leaveRequestsQuery instanceof Builder) {
                $leaveRequestsQuery->where('status', $status);
            }
            if ($statementRequestsQuery instanceof Builder) {
                $statementRequestsQuery->where('status', $status);
            }
            if ($courseRequestsQuery instanceof Builder) {
                $courseRequestsQuery->where('status', $status);
            }
        }

        // فلترة التاريخ
        if ($date) {
            if ($leaveRequestsQuery instanceof Builder) {
                $leaveRequestsQuery->whereDate('created_at', $date);
            }
            if ($statementRequestsQuery instanceof Builder) {
                $statementRequestsQuery->whereDate('created_at', $date);
            }
            if ($courseRequestsQuery instanceof Builder) {
                $courseRequestsQuery->whereDate('created_at', $date);
            }
        }


        // تنفيذ الاستعلام أو إرجاع الـ Collection مباشرة
        $leaveRequests = $leaveRequestsQuery instanceof Builder ? $leaveRequestsQuery->get() : $leaveRequestsQuery;
        $statementRequests = $statementRequestsQuery instanceof Builder ? $statementRequestsQuery->get() : $statementRequestsQuery;
        $courseRequests = $courseRequestsQuery instanceof Builder ? $courseRequestsQuery->get() : $courseRequestsQuery;

        // التجاوب النهائي
        return response()->json([
            'leaveRequests' => LeaveRequestResource::collection($leaveRequests),
            'statementRequests' => StatementRequestResource::collection($statementRequests),
            'courseRequests' => CourseRequestResource::collection($courseRequests),
        ]);
    }
}
