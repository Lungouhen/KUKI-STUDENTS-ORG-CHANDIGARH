@extends('layouts.admin')

@section('title', 'Contact Messages | KSO CMS')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white p-3">
        <h5 class="fw-bold text-primary mb-0"><i class="fa-solid fa-envelope-open-text me-2"></i> Contact Inquiries</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 extra-small">
                <thead class="table-light">
                    <tr>
                        <th>Sender Name</th>
                        <th>Subject</th>
                        <th>Message Content</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $m)
                        <tr>
                            <td class="fw-bold text-dark">{{ $m->name }}<br><small class="text-muted">{{ $m->phone }}</small></td>
                            <td class="fw-bold text-primary">{{ $m->subject }}</td>
                            <td>{{ $m->message }}</td>
                            <td>{{ $m->created_at ? $m->created_at->format('Y-m-d H:i') : '' }}</td>
                            <td><span class="badge {{ $m->status === 'Unread' ? 'bg-danger' : 'bg-success' }}">{{ $m->status }}</span></td>
                            <td>
                                @if($m->status === 'Unread')
                                    <form action="{{ route('admin.messages.updateStatus', $m->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="status" value="Resolved">
                                        <button class="btn btn-sm btn-outline-success">Mark Resolved</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">
            {{ $messages->links() }}
        </div>
    </div>
</div>

@endsection
