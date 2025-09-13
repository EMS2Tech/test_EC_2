<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Models\Student;

class StudentController extends Controller
{
    public function export(Request $request)
    {
        Log::info('Students export initiated', $request->all());

        $query = Student::select(
            'students.student_id',
            'students.full_name',
            DB::raw('COALESCE(applications.nic_number, applications.passport_number, "N/A") as nic_or_passport'),
            DB::raw('COALESCE(applications.contact_number, "N/A") as contact_number'),
            DB::raw('COALESCE((SELECT COUNT(*) FROM course_applications WHERE course_applications.user_id = students.user_id), 0) as no_of_courses')
        )
        ->leftJoin('applications', 'students.user_id', '=', 'applications.user_id')
        ->leftJoin('users', 'students.user_id', '=', 'users.id');

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('students.student_id', 'like', "%{$search}%")
                  ->orWhere('students.full_name', 'like', "%{$search}%")
                  ->orWhere('applications.nic_number', 'like', "%{$search}%")
                  ->orWhere('applications.passport_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('study_program')) {
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                  ->from('course_applications')
                  ->whereColumn('course_applications.user_id', 'students.user_id')
                  ->where('course_applications.study_programme_id', $request->input('study_program'));
            });
        }

        if ($request->filled('course')) {
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                  ->from('course_applications')
                  ->whereColumn('course_applications.user_id', 'students.user_id')
                  ->where('course_applications.course_id', $request->input('course'));
            });
        }

        if ($request->filled('batch')) {
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                  ->from('course_applications')
                  ->whereColumn('course_applications.user_id', 'students.user_id')
                  ->where('course_applications.batch_id', $request->input('batch'));
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereExists(function ($q) use ($request) {
                $q->select(DB::raw(1))
                  ->from('applications')
                  ->whereColumn('applications.user_id', 'students.user_id')
                  ->whereBetween('applications.created_at', [$request->input('start_date'), $request->input('end_date')]);
            });
        }

        if ($request->filled('no_of_courses')) {
            $noOfCourses = $request->input('no_of_courses');
            $query->whereExists(function ($q) use ($request, $noOfCourses) {
                $q->select(DB::raw(1))
                  ->from('course_applications')
                  ->whereColumn('course_applications.user_id', 'students.user_id')
                  ->groupBy('course_applications.user_id');
                if ($noOfCourses === '6+') {
                    $q->havingRaw('COUNT(*) >= 6');
                } else {
                    $q->havingRaw('COUNT(*) = ?', [(int)$noOfCourses]);
                }
            });
        }

        $students = $query->get();
        Log::info('Students fetched for export', ['count' => $students->count()]);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($students) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Student ID', 'Full Name', 'NIC or Passport', 'Contact Number', 'No of Courses']);
            foreach ($students as $student) {
                fputcsv($output, [
                    $student->student_id ?? 'N/A',
                    $student->full_name ?? 'N/A',
                    $student->nic_or_passport ?? 'N/A',
                    $student->contact_number ?? 'N/A',
                    $student->no_of_courses ?? 0,
                ]);
            }
            fclose($output);
        };

        return Response::stream($callback, 200, $headers);
    }
}
