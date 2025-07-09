<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('studyProgram')->paginate(10);
        $studyPrograms = StudyProgram::all();

        return view('frontend.add_course', compact('courses', 'studyPrograms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:courses,code|max:10',
            'program_id' => 'required|exists:study_programs,id',
            'course_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
        ]);

        Course::create([
            'code' => $request->code,
            'program_id' => $request->program_id,
            'course_name' => $request->course_name,
            'short_name' => $request->short_name,
        ]);

        return redirect()->back()->with('success', 'Course created successfully.');
    }

    public function edit($id)
    {
        $course = Course::with('studyProgram')->findOrFail($id);
        return view('frontend.course-edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:255',
            'course_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'study_program_id' => 'required|exists:study_programs,id',
        ]);

        $course->update([
            'code' => $request->code,
            'course_name' => $request->course_name,
            'short_name' => $request->short_name,
            'study_program_id' => $request->study_program_id,
        ]);

        return Redirect::route('admin.add-course')->with('status', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return Redirect::route('admin.add-course')->with('status', 'Course deleted successfully!');
    }
}