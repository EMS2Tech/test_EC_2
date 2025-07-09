<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with('course')->paginate(10);
        $courses = Course::all();

        return view('frontend.add_batch', compact('batches', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_no' => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Batch::create([
            'course_id' => $request->course_id,
            'batch_no' => $request->batch_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->back()->with('success', 'Batch created successfully.');
    }

    public function edit($id)
    {
        $batch = Batch::with('course')->findOrFail($id);
        return view('frontend.batch-edit', compact('batch'));
    }

    public function update(Request $request, $id)
    {
        $batch = Batch::findOrFail($id);

        $request->validate([
            'batch_no' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'course_id' => 'required|exists:courses,id',
        ]);

        $batch->update([
            'batch_no' => $request->batch_no,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'course_id' => $request->course_id,
        ]);

        return Redirect::route('admin.add-batch')->with('status', 'Batch updated successfully!');
    }

    public function destroy($id)
    {
        $batch = Batch::findOrFail($id);
        $batch->delete();
        return Redirect::route('admin.add-batch')->with('status', 'Batch deleted successfully!');
    }
}