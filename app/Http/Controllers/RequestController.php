<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\StatementRequest;
use App\Models\CourseRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Http\Resources\StatementRequestResource;
use App\Http\Resources\CourseRequestResource;

class RequestController extends Controller
{
    public function getAllFilteredRequests(Request $request)
    {
        // علاقات مشتركة
        $leaveRequestsQuery = LeaveRequest::with(['user', 'attachments', 'approvals']);
        $statementRequestsQuery = StatementRequest::with(['user', 'attachments']);
        $courseRequestsQuery = CourseRequest::with(['user', 'attachments', 'course']);

        // الفلترة حسب النوع (يُخفي الباقي)
        if ($request->has('type')) {
            $type = $request->type;
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
        }

        // فلترة الحالة
        if ($request->has('status')) {
            $leaveRequestsQuery = is_a($leaveRequestsQuery, 'Illuminate\\Database\\Eloquent\\Builder')
                ? $leaveRequestsQuery->where('status', $request->status) : $leaveRequestsQuery;
            $statementRequestsQuery = is_a($statementRequestsQuery, 'Illuminate\\Database\\Eloquent\\Builder')
                ? $statementRequestsQuery->where('status', $request->status) : $statementRequestsQuery;
            $courseRequestsQuery = is_a($courseRequestsQuery, 'Illuminate\\Database\\Eloquent\\Builder')
                ? $courseRequestsQuery->where('status', $request->status) : $courseRequestsQuery;
        }

        // فلترة التاريخ
        if ($request->has('date')) {
            $leaveRequestsQuery = is_a($leaveRequestsQuery, 'Illuminate\\Database\\Eloquent\\Builder')
                ? $leaveRequestsQuery->whereDate('created_at', $request->date) : $leaveRequestsQuery;
            $statementRequestsQuery = is_a($statementRequestsQuery, 'Illuminate\\Database\\Eloquent\\Builder')
                ? $statementRequestsQuery->whereDate('created_at', $request->date) : $statementRequestsQuery;
            $courseRequestsQuery = is_a($courseRequestsQuery, 'Illuminate\\Database\\Eloquent\\Builder')
                ? $courseRequestsQuery->whereDate('created_at', $request->date) : $courseRequestsQuery;
        }

        // تحميل النتائج
        $leaveRequests = is_a($leaveRequestsQuery, 'Illuminate\\Database\\Eloquent\\Builder') ? $leaveRequestsQuery->get() : collect();
        $statementRequests = is_a($statementRequestsQuery, 'Illuminate\\Database\\Eloquent\\Builder') ? $statementRequestsQuery->get() : collect();
        $courseRequests = is_a($courseRequestsQuery, 'Illuminate\\Database\\Eloquent\\Builder') ? $courseRequestsQuery->get() : collect();

        // الموارد النهائية (Resources)
        return response()->json([
            'leaveRequests' => LeaveRequestResource::collection($leaveRequests),
            'statementRequests' => StatementRequestResource::collection($statementRequests),
            'courseRequests' => CourseRequestResource::collection($courseRequests),
        ]);
    }
}
