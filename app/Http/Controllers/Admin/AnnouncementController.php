<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(15);
        return view('admin.announcement.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcement.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        Announcement::create([
            'title'     => $data['title'],
            'content'   => $data['content'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('announcement.index')
                         ->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcement.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $announcement->update([
            'title'     => $data['title'],
            'content'   => $data['content'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('announcement.index')
                         ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function toggle(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);

        return redirect()->route('announcement.index')
                         ->with('success', 'Status pengumuman berhasil diubah.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcement.index')
                         ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
