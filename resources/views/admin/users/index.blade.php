@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Users</h4>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">Create User</a>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive mt-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Created</th>
                                    <th width="160">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @forelse($user->getRoleNames() as $r)
                                                <span class="badge badge-info">{{ $r }}</span>
                                            @empty
                                                <span class="text-muted">None</span>
                                            @endforelse
                                        </td>
                                        <td>{{ $user->created_at?->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="btn btn-sm btn-warning">Edit</a>

                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
