<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use Illuminate\Http\Request;

class StudyProgramController extends Controller
{
    public function index()
    {
        $studyPrograms = StudyProgram::paginate(10);

        return view('frontend.add_studyprogram', compact('studyPrograms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:study_programs,code|max:10',
            'program_name' => 'required|string|max:255',
        ]);

        $studyProgram = StudyProgram::create([
            'code' => $request->code,
            'program_name' => $request->program_name,
        ]);

        return redirect()->back()->with('success', 'Study program created successfully.');
    }
}