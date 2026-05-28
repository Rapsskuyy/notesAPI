@extends('layouts.app')
@section('title', 'My Notes')

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:28px; flex-wrap:wrap; gap:12px;">
    <h2 style="font-size:1.8rem; font-weight:900; letter-spacing:-1px;">MY NOTES</h2>
    <button class="btn btn-yellow" onclick="openModal('create-modal')">+ NEW NOTE</button>
</div>

{{-- ===== TAG FILTER ===== --}}
@if($allTags->count())
<div class="filter-bar">
    <span class="filter-label">Filter:</span>
    <a href="{{ route('notes.index') }}" class="tag-badge {{ !$activeTag ? 'active' : '' }}">All</a>
    @foreach($allTags as $tag)
        <a href="{{ route('notes.index', ['tag' => $tag->name]) }}"
           class="tag-badge {{ $activeTag === $tag->name ? 'active' : '' }}">
            {{ $tag->name }}
        </a>
    @endforeach
</div>
@endif

{{-- ===== NOTES GRID ===== --}}
@if($notes->count())
    <div class="notes-grid">
        @foreach($notes as $note)
        <div class="note-card">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:8px;">
                <div class="note-card-title">{{ $note->title }}</div>
                @if($note->is_public)
                    <span class="note-public-badge">PUBLIC</span>
                @endif
            </div>

            <div class="note-card-body">
                {{ Str::limit($note->body, 100) }}
            </div>

            @if($note->tags->count())
            <div class="note-card-tags">
                @foreach($note->tags as $tag)
                    <a href="{{ route('notes.index', ['tag' => $tag->name]) }}" class="tag-badge" style="font-size:0.7rem; padding:2px 8px;">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
            @endif

            <div class="note-card-actions">
                <button
                    class="btn btn-yellow btn-sm"
                    onclick="openEditModal(
                        {{ $note->id }},
                        {{ json_encode($note->title) }},
                        {{ json_encode($note->body) }},
                        {{ $note->is_public ? 'true' : 'false' }},
                        {{ json_encode($note->tags->pluck('name')->join(', ')) }}
                    )"
                >Edit</button>

                <form action="{{ route('notes.destroy', $note) }}" method="POST"
                      onsubmit="return confirm('Delete this note?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-red btn-sm">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($notes->hasPages())
    <div class="pagination">
        {{-- Previous --}}
        @if($notes->onFirstPage())
            <span style="opacity:0.4;">← Prev</span>
        @else
            <a href="{{ $notes->previousPageUrl() }}">← Prev</a>
        @endif

        {{-- Page numbers --}}
        @foreach($notes->getUrlRange(1, $notes->lastPage()) as $page => $url)
            @if($page == $notes->currentPage())
                <span class="active-page">{{ $page }}</span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next --}}
        @if($notes->hasMorePages())
            <a href="{{ $notes->nextPageUrl() }}">Next →</a>
        @else
            <span style="opacity:0.4;">Next →</span>
        @endif
    </div>
    @endif

@else
    <div class="empty-state">
        <div style="font-size:3rem;">📭</div>
        <p>
            @if($activeTag)
                No notes found with tag <strong>"{{ $activeTag }}"</strong>.
                <a href="{{ route('notes.index') }}" style="color:#000;">Clear filter</a>
            @else
                No notes yet. Create your first note!
            @endif
        </p>
    </div>
@endif


{{-- ===== CREATE MODAL ===== --}}
<div class="modal-overlay" id="create-modal">
    <div class="modal-box">
        <div class="modal-title">CREATE NEW NOTE</div>
        <form action="{{ route('notes.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label" for="create-title">Title *</label>
                <input type="text" id="create-title" name="title" class="form-control"
                       placeholder="Note title" required value="{{ old('title') }}">
                @error('title')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="create-body">Body *</label>
                <textarea id="create-body" name="body" class="form-control"
                          placeholder="Write your note here..." required rows="5">{{ old('body') }}</textarea>
                @error('body')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="create-tags">Tags</label>
                <input type="text" id="create-tags" name="tags" class="form-control"
                       placeholder="work, meeting, personal (comma-separated)"
                       value="{{ old('tags') }}">
                <div style="font-size:0.75rem; font-weight:700; color:#666; margin-top:4px;">
                    Separate tags with commas
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="create-is_public" name="is_public" value="1"
                           {{ old('is_public') ? 'checked' : '' }}>
                    <label for="create-is_public">Make this note public</label>
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn btn-yellow" style="flex:1;">SAVE NOTE</button>
                <button type="button" class="btn" onclick="closeModal('create-modal')" style="flex:1;">CANCEL</button>
            </div>
        </form>
    </div>
</div>


{{-- ===== EDIT MODAL ===== --}}
<div class="modal-overlay" id="edit-modal">
    <div class="modal-box">
        <div class="modal-title">EDIT NOTE</div>
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="edit-title">Title *</label>
                <input type="text" id="edit-title" name="title" class="form-control"
                       placeholder="Note title" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="edit-body">Body *</label>
                <textarea id="edit-body" name="body" class="form-control"
                          placeholder="Write your note here..." required rows="5"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label" for="edit-tags">Tags</label>
                <input type="text" id="edit-tags" name="tags" class="form-control"
                       placeholder="work, meeting, personal (comma-separated)">
                <div style="font-size:0.75rem; font-weight:700; color:#666; margin-top:4px;">
                    Separate tags with commas
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="edit-is_public" name="is_public" value="1">
                    <label for="edit-is_public">Make this note public</label>
                </div>
            </div>

            <div style="display:flex; gap:10px; margin-top:8px;">
                <button type="submit" class="btn btn-yellow" style="flex:1;">UPDATE NOTE</button>
                <button type="button" class="btn" onclick="closeModal('edit-modal')" style="flex:1;">CANCEL</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
        document.body.style.overflow = '';
    }

    function openEditModal(id, title, body, isPublic, tags) {
        const form = document.getElementById('edit-form');
        form.action = '/notes/' + id;

        document.getElementById('edit-title').value = title;
        document.getElementById('edit-body').value = body;
        document.getElementById('edit-tags').value = tags;
        document.getElementById('edit-is_public').checked = isPublic;

        openModal('edit-modal');
    }

    // Close modal on overlay click
    document.querySelectorAll('.modal-overlay').forEach(function(overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                overlay.classList.remove('open');
                document.body.style.overflow = '';
            }
        });
    });

    // Auto-open create modal if there are validation errors for create form
    @if($errors->any() && old('_token'))
        openModal('create-modal');
    @endif
</script>
@endsection
