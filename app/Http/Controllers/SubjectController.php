<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::paginate(10);
        return view('frontend.add_subject', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|string|unique:subjects,subject_id|max:255',
            'subject_name' => 'required|string|max:255',
        ]);

        Subject::create([
            'subject_id' => $request->subject_id,
            'subject_name' => $request->subject_name,
        ]);

        return redirect()->back()->with('success', 'Subject created successfully.');
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $subjects = Subject::paginate(10); // Pass paginated list for the table
        return view('frontend.subject-edit', compact('subject', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);

        $request->validate([
            'subject_id' => 'required|string|max:255|unique:subjects,subject_id,' . $subject->id,
            'subject_name' => 'required|string|max:255',
        ]);

        $subject->update([
            'subject_id' => $request->subject_id,
            'subject_name' => $request->subject_name,
        ]);

        return Redirect::route('admin.add-subject')->with('success', 'Subject updated successfully!');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        return Redirect::route('admin.add-subject')->with('success', 'Subject deleted successfully!');
    }
}