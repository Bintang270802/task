@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header mb-4">
    <h1 class="page-title">
        <i class="bi bi-folder"></i> Project Management
    </h1>
</div>

<div class="row">
    <!-- Add Project Card -->
    <div class="col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-plus-circle"></i> Create New Project
            </div>
            <div class="card-body">
                <form action="{{ route('projects.store') }}" method="POST" id="projectForm">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">
                            Project Name <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="text" 
                            class="form-control @error('name') is-invalid @enderror" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            placeholder="Enter project name"
                            required
                        >
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Create Project
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Projects List -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-ul"></i> All Projects</span>
                <span class="badge bg-secondary">{{ $projects->count() }} Total</span>
            </div>
            <div class="card-body">
                @forelse($projects as $project)
                    <div class="project-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="project-name mb-1">
                                    <i class="bi bi-folder-fill text-primary"></i>
                                    {{ $project->name }}
                                </div>
                                <div class="project-meta">
                                    <i class="bi bi-list-task"></i> {{ $project->tasks_count }} task(s)
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-calendar3"></i> Created {{ $project->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-folder-x"></i>
                        <p>No projects yet. Create your first project to get started!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this project? All tasks in this project will also be deleted.')) {
                this.submit();
            }
        });
    });

    // Auto-focus on project name input
    document.getElementById('name').focus();
</script>
@endpush
