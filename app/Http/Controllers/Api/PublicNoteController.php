<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NoteResource;
use App\Models\Note;

class PublicNoteController extends Controller
{
    public function index()
    {
        $notes = Note::with('tags')
            ->where('is_public', true)
            ->latest()
            ->paginate(10);

        return NoteResource::collection($notes);
    }

    public function show(Note $note)
    {
        if (! $note->is_public) {
            abort(404);
        }

        $note->load('tags');

        return new NoteResource($note);
    }
}
