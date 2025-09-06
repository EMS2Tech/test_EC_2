<?php

namespace App\Http\Controllers;

use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
            'required_documents' => 'required|array', // Validate as array
            'required_documents.*' => 'in:ol_certificate,al_certificate,diploma_certificates,degree_certificate,transcript_certificate,other_certificates', // Ensure valid document types
        ]);

        $studyProgram = StudyProgram::create([
            'code' => $request->code,
            'program_name' => $request->program_name,
            'required_documents' => $request->required_documents, // Store as array, will be cast to JSON
        ]);

        return redirect()->back()->with('success', 'Study program created successfully.');
    }

    public function edit($id)
    {
        $program = StudyProgram::findOrFail($id);
        return view('frontend.study-program-edit', compact('program'));
    }

    public function update(Request $request, $id)
    {
        $program = StudyProgram::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:255',
            'program_name' => 'required|string|max:255',
            'required_documents' => 'required|array',
            'required_documents.*' => 'in:ol_certificate,al_certificate,diploma_certificates,degree_certificate,transcript_certificate',
        ]);

        $program->update([
            'code' => $request->code,
            'program_name' => $request->program_name,
            'required_documents' => $request->required_documents,
        ]);

        return Redirect::route('admin.add-studyprogram')->with('status', 'Study program updated successfully!');
    }

    public function destroy($id)
    {
        $program = StudyProgram::findOrFail($id);
        $program->delete();
        return Redirect::route('admin.add-studyprogram')->with('status', 'Study program deleted successfully!');
    }
}