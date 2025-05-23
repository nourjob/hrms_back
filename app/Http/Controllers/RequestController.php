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
        $type = $request->input('type');
        $status = $request->input('status');
        $date = $request->input('date');

        // استعلامات جاهزة
        $leaveRequests = collect();
        $statementRequests = collect();
        $courseRequests = collect();

        // استعلامات عند الطلب فقط
        if (!$type || $type === 'leave') {
            $query = LeaveRequest::with(['user', 'attachments', 'approvals']);
            if ($status) $query->where('status', $status);
            if ($date) $query->whereDate('created_at', $date);
            $leaveRequests = $query->get();
        }

        if (!$type || $type === 'statement') {
            $query = StatementRequest::with(['user', 'attachments']);
            if ($status) $query->where('status', $status);
            if ($date) $query->whereDate('created_at', $date);
            $statementRequests = $query->get();
        }

        if (!$type || $type === 'course') {
            $query = CourseRequest::with(['user', 'attachments', 'course']);
            if ($status) $query->where('status', $status);
            if ($date) $query->whereDate('created_at', $date);
            $courseRequests = $query->get();
        }

        // الإرجاع النهائي
        return response()->json([
            'leaveRequests' => LeaveRequestResource::collection($leaveRequests),
            'statementRequests' => StatementRequestResource::collection($statementRequests),
            'courseRequests' => CourseRequestResource::collection($courseRequests),
        ]);
    }
}
