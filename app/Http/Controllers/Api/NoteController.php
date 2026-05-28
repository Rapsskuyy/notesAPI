<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NoteRequest;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->notes()->with('tags');

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        $notes = $query->latest()->paginate(10);

        return NoteResource::collection($notes);
    }

    public function store(NoteRequest $request)
    {
        $note = $request->user()->notes()->create([
            'title'     => $request->title,
            'body'      => $request->body,
            'is_public' => $request->boolean('is_public', false),
        ]);

        if ($request->filled('tags')) {
            $tagIds = collect($request->tags)->map(function ($name) {
                return Tag::firstOrCreate(['name' => trim(strtolower($name))])->id;
            });
            $note->tags()->sync($tagIds);
        }

        $note->load('tags');

        return (new NoteResource($note))->response()->setStatusCode(201);
    }

    public function show(Request $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id) {
            abort(403, 'Forbidden.');
        }

        $note->load('tags');

        return new NoteResource($note);
    }

    public function update(NoteRequest $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id) {
            abort(403, 'Forbidden.');
        }

        $note->update([
            'title'     => $request->title,
            'body'      => $request->body,
            'is_public' => $request->boolean('is_public', $note->is_public),
        ]);

        if ($request->has('tags')) {
            $tagIds = collect($request->tags ?? [])->map(function ($name) {
                return Tag::firstOrCreate(['name' => trim(strtolower($name))])->id;
            });
            $note->tags()->sync($tagIds);
        }

        $note->load('tags');

        return new NoteResource($note);
    }

    public function destroy(Request $request, Note $note)
    {
        if ($note->user_id !== $request->user()->id) {
            abort(403, 'Forbidden.');
        }

        $note->delete();

        return response()->json(['message' => 'Note deleted successfully.']);
    }
}
