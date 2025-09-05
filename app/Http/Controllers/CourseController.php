<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudyProgram;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('studyProgram')->paginate(10);
        $studyPrograms = StudyProgram::all();
        $subjects = Subject::all();

        return view('frontend.add_course', compact('courses', 'studyPrograms', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:courses,code|max:10',
            'program_id' => 'required|exists:study_programs,id',
            'course_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'subject_count' => 'required|integer|min:1',
            'subjects' => 'required|array',
            'subjects.*' => 'required|exists:subjects,id',
        ]);

        $course = Course::create([
            'code' => $request->code,
            'program_id' => $request->program_id,
            'course_name' => $request->course_name,
            'short_name' => $request->short_name,
        ]);

        // Attach selected subjects up to subject_count
        $subjectsToAttach = array_slice($request->subjects, 0, $request->subject_count);
        $course->subjects()->attach($subjectsToAttach);

        return redirect()->back()->with('success', 'Course created successfully.');
    }

    public function edit($id)
{
    $course = Course::with('studyProgram', 'subjects')->findOrFail($id);
    $studyPrograms = StudyProgram::all();
    $subjects = Subject::all();
    return view('frontend.course-edit', compact('course', 'studyPrograms', 'subjects'));
}

    public function update(Request $request, $id)
{
    $course = Course::findOrFail($id);

    $request->validate([
        'code' => 'required|string|max:255',
        'course_name' => 'required|string|max:255',
        'short_name' => 'required|string|max:255',
        'study_program_id' => 'required|exists:study_programs,id',
        'subject_count' => 'required|integer|min:1',
        'subjects' => 'required|array',
        'subjects.*' => 'required|exists:subjects,id',
    ]);

    $course->update([
        'code' => $request->code,
        'course_name' => $request->course_name,
        'short_name' => $request->short_name,
        'study_program_id' => $request->study_program_id,
    ]);

    $subjectsToSync = array_slice($request->subjects, 0, $request->subject_count);
    $course->subjects()->sync($subjectsToSync);

    return Redirect::route('admin.add-course')->with('status', 'Course updated successfully!');
}

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->subjects()->detach();
        $course->delete();
        return Redirect::route('admin.add-course')->with('status', 'Course deleted successfully!');
    }
}