@extends('layouts.admin')
@section('title', 'Notifications')

@section('content')
    <div class="content-wrapper">



        {{-- FLASH --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="mdi mdi-check-circle-outline"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- CONTENT --}}
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">All Notifications</h4>
                            <span class="badge badge-info">
                                {{ auth()->user()->unreadNotifications()->count() }} Unread
                            </span>
                        </div>

                        {{-- LIST --}}
                        @forelse($notifications as $notification)
                            @php
                                $isUnread = is_null($notification->read_at);

                                // Icon & color mapping
                                $icon = 'mdi-bell-outline';
                                $badge = 'badge-secondary';

                                if (str_contains($notification->type, 'LoanSubmitted')) {
                                    $icon = 'mdi-file-send-outline';
                                    $badge = 'badge-warning';
                                }

                                if (str_contains($notification->type, 'LoanStatusChanged')) {
                                    $icon = 'mdi-cash-check';
                                    $badge = 'badge-info';
                                }

                                if (
                                    isset($notification->data['new_status']) &&
                                    in_array($notification->data['new_status'], ['rejected', 'defaulted'])
                                ) {
                                    $badge = 'badge-danger';
                                }

                                if (
                                    isset($notification->data['new_status']) &&
                                    in_array($notification->data['new_status'], ['approved', 'completed'])
                                ) {
                                    $badge = 'badge-success';
                                }
                            @endphp

                            <div
                                class="d-flex align-items-start p-3 mb-2 border rounded
                                    {{ $isUnread ? 'bg-light' : '' }}">

                                <div class="mr-3">
                                    <i class="mdi {{ $icon }} mdi-24px text-primary"></i>
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-1 font-weight-medium">
                                            {{ $notification->data['message'] ?? 'Notification' }}
                                        </p>

                                        <span class="badge {{ $badge }}">
                                            {{ $isUnread ? 'UNREAD' : 'READ' }}
                                        </span>
                                    </div>

                                    <small class="text-muted">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>

                                    {{-- EXTRA CONTEXT --}}
                                    @if (isset($notification->data['loan_id']))
                                        <div class="mt-1">
                                            <small>
                                                Loan ID:
                                                <strong>#{{ $notification->data['loan_id'] }}</strong>
                                            </small>
                                        </div>
                                    @endif
                                </div>

                                {{-- ACTION --}}
                                <div class="ml-3">
                                    @if ($isUnread)
                                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @else
                                        <i class="mdi mdi-check text-success"></i>
                                    @endif
                                </div>
                            </div>

                        @empty
                            <div class="text-center py-5">
                                <i class="mdi mdi-bell-off-outline mdi-48px text-muted"></i>
                                <p class="mt-3 text-muted">
                                    You have no notifications yet.
                                </p>
                            </div>
                        @endforelse

                        {{-- PAGINATION --}}
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
