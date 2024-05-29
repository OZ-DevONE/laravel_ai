<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class AdminReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reports = QueryBuilder::for(Report::class)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('created_at'),
            ])
            ->paginate(10)
            ->appends($request->query());

        return view('admin.reports.index', compact('reports'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $report = Report::findOrFail($id);
        return view('admin.reports.edit', compact('report'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:' . implode(',', Report::STATUSES) . '|max:255',
            'admin_comment' => 'nullable|string|max:1000',
        ]);

        $report = Report::findOrFail($id);
        $report->update([
            'status' => $request->status,
            'admin_comment' => $request->admin_comment,
        ]);

        return redirect()->route('admin.reports.index')->with('success', 'Жалоба успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Жалоба успешно удалена.');
    }
}
