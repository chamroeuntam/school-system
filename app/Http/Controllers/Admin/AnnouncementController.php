<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::orderByDesc('created_at')->paginate(20);
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();

        if ($request->has('is_published') && $request->is_published) {
            $validated['published_at'] = now();
        }

        Announcement::create($validated);

        Activity::log(
            'announcement_created',
            "Announcement created: {$validated['title']}",
            auth()->id()
        );

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'is_published' => 'boolean',
        ]);

        if ($request->has('is_published') && $request->is_published && !$announcement->is_published) {
            $validated['published_at'] = now();
        }

        $announcement->update($validated);

        Activity::log(
            'announcement_updated',
            "Announcement updated: {$announcement->title}",
            auth()->id(),
            $announcement
        );

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        Activity::log(
            'announcement_deleted',
            "Announcement deleted: {$announcement->title}",
            auth()->id()
        );
        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }
}
