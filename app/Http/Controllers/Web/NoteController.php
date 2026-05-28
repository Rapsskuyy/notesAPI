<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->notes()->with('tags');

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        $notes = $query->latest()->paginate(10);
        $allTags = Tag::orderBy('name')->get();
        $activeTag = $request->tag;

        return view('notes.index', compact('notes', 'allTags', 'activeTag'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'body'      => ['required', 'string'],
            'is_public' => ['sometimes', 'boolean'],
            'tags'      => ['sometimes', 'nullable', 'string'],
        ]);

        $note = Auth::user()->notes()->create([
            'title'     => $data['title'],
            'body'      => $data['body'],
            'is_public' => $request->boolean('is_public', false),
        ]);

        if (! empty($data['tags'])) {
            $tagNames = array_filter(array_map('trim', explode(',', $data['tags'])));
            $tagIds = collect($tagNames)->map(function ($name) {
                return Tag::firstOrCreate(['name' => strtolower($name)])->id;
            });
            $note->tags()->sync($tagIds);
        }

        return redirect()->route('notes.index')->with('success', 'Note created successfully!');
    }

    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'body'      => ['required', 'string'],
            'is_public' => ['sometimes', 'boolean'],
            'tags'      => ['sometimes', 'nullable', 'string'],
        ]);

        $note->update([
            'title'     => $data['title'],
            'body'      => $data['body'],
            'is_public' => $request->boolean('is_public', false),
        ]);

        $tagNames = array_filter(array_map('trim', explode(',', $data['tags'] ?? '')));
        $tagIds = collect($tagNames)->map(function ($name) {
            return Tag::firstOrCreate(['name' => strtolower($name)])->id;
        });
        $note->tags()->sync($tagIds);

        return redirect()->route('notes.index')->with('success', 'Note updated successfully!');
    }

    public function destroy(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Note deleted successfully!');
    }
}
