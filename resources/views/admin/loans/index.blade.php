@extends('layouts.admin')

@section('content')
    <div class="content-wrapper">

        <div class="content">
            {{-- Stats Cards --}}
            {{-- Liberty UI Stats Cards - Fixed Readability --}}
            <div class="row mb-4 mx-auto">


                {{-- Under Review --}}
                <div class="col-xl-2 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-shadow-lg">
                        <div class="card-body text-center py-4 position-relative">
                            <div class="gradient-warning position-absolute top-0 left-0 w-100 h-60 rounded-top"></div>
                            <i
                                class="mdi mdi-account-search-outline display-4 text-warning mb-3 position-relative z-index-1"></i>
                            <h3 class="fw-bold mb-1 position-relative z-index-1">
                                {{ $statusCounts['under_review'] ?? \App\Models\Loan::where('status', 'under_review')->count() }}
                            </h3>
                            <p class="text-muted mb-0 small fw-semibold">Under Review</p>
                        </div>
                    </div>
                </div>

                {{-- Approved --}}
                <div class="col-xl-2 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-shadow-lg">
                        <div class="card-body text-center py-4 position-relative">
                            <div class="gradient-success position-absolute top-0 left-0 w-100 h-60 rounded-top"></div>
                            <i
                                class="mdi mdi-check-circle-outline display-4 text-success mb-3 position-relative z-index-1"></i>
                            <h3 class="fw-bold mb-1 position-relative z-index-1">
                                {{ $statusCounts['approved'] ?? \App\Models\Loan::where('status', 'approved')->count() }}
                            </h3>
                            <p class="text-muted mb-0 small fw-semibold">Approved</p>
                        </div>
                    </div>
                </div>

                {{-- Disbursed --}}
                <div class="col-xl-2 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-shadow-lg">
                        <div class="card-body text-center py-4 position-relative">
                            <div class="gradient-info position-absolute top-0 left-0 w-100 h-60 rounded-top"></div>
                            <i class="mdi mdi-cash-check-outline display-4 text-info mb-3 position-relative z-index-1"></i>
                            <h3 class="fw-bold mb-1 position-relative z-index-1">
                                {{ $statusCounts['disbursed'] ?? \App\Models\Loan::where('status', 'disbursed')->count() }}
                            </h3>
                            <p class="text-muted mb-0 small fw-semibold">Disbursed</p>
                        </div>
                    </div>
                </div>

                {{-- Rejected --}}
                <div class="col-xl-2 col-sm-6 mb-3">
                    <div class="card border-0 shadow-sm h-100 hover-shadow-lg">
                        <div class="card-body text-center py-4 position-relative">
                            <div class="gradient-danger position-absolute top-0 left-0 w-100 h-60 rounded-top"></div>
                            <i
                                class="mdi mdi-close-circle-outline display-4 text-danger mb-3 position-relative z-index-1"></i>
                            <h3 class="fw-bold mb-1 position-relative z-index-1">
                                {{ $statusCounts['rejected'] ?? \App\Models\Loan::where('status', 'rejected')->count() }}
                            </h3>
                            <p class="text-muted mb-0 small fw-semibold">Rejected</p>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Filters --}}
            <div class="card">
                <div class="card-body">
                    <form id="filterForm" method="GET" action="{{ route('admin.loans.index') }}">
                        <div class="row align-items-end mb-4">
                            <div class="col-md-3 mb-3">
                                <label>Search</label>
                                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                                    placeholder="User name or email">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All ({{ $loans->total() }})</option>
                                    @php
                                        use App\Models\Loan;
                                        $statuses = ['submitted', 'under_review', 'approved', 'disbursed', 'rejected'];
                                    @endphp
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                            ({{ Loan::where('status', $status)->count() }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Loans Table --}}
            <div class="card">
                <div class="card-body">
                    @if ($loans->count())
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Term</th>
                                        <th>Status</th>
                                        <th>Applied</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loans as $loan)
                                        <tr>
                                            {{-- Refined User Avatar Section --}}
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative mr-3">
                                                        @php
                                                            $avatarUrl = $loan->user->avatar
                                                                ? asset('storage/' . $loan->user->avatar)
                                                                : asset('admin/images/faces/user.png');
                                                        @endphp
                                                        <img src="{{ $avatarUrl }}"
                                                            class="rounded-circle shadow-sm border border-light"
                                                            width="42" height="42" alt="{{ $loan->user->name }}">
                                                        {{-- Online status indicator --}}
                                                        <span
                                                            class="position-absolute bottom-0 end-0 badge border border-white rounded-circle bg-success"
                                                            style="width: 12px; height: 12px;"></span>
                                                    </div>
                                                    <div class="flex-grow-1 min-width-0">
                                                        <div class="fw-semibold text-truncate" style="max-width: 150px;"
                                                            title="{{ $loan->user->name }}">
                                                            {{ Str::limit($loan->user->name, 20) }}
                                                        </div>
                                                        <small class="text-muted text-truncate d-block"
                                                            style="max-width: 180px;" title="{{ $loan->user->email }}">
                                                            {{ Str::limit($loan->user->email, 25) }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>

                                            <td><strong>${{ number_format($loan->amount, 0) }}</strong></td>
                                            <td>{{ $loan->term }} months</td>
                                            <td>
                                                @php
                                                    $statusConfig = [
                                                        'submitted' => ['label' => 'Submitted', 'class' => 'warning'],
                                                        'under_review' => [
                                                            'label' => 'Under Review',
                                                            'class' => 'info',
                                                        ],
                                                        'approved' => ['label' => 'Approved', 'class' => 'success'],
                                                        'disbursed' => ['label' => 'Disbursed', 'class' => 'primary'],
                                                        'rejected' => ['label' => 'Rejected', 'class' => 'danger'],
                                                    ];
                                                    $config = $statusConfig[$loan->status] ?? [
                                                        'label' => ucfirst($loan->status),
                                                        'class' => 'secondary',
                                                    ];
                                                @endphp
                                                <span class="badge badge-{{ $config['class'] }} px-3 py-2">
                                                    <i
                                                        class="mdi mdi-{{ $loan->status == 'approved' ? 'check-circle' : ($loan->status == 'disbursed' ? 'cash-check' : 'clock-outline') }} mr-1"></i>
                                                    {{ $config['label'] }}
                                                </span>
                                                @if ($loan->rejection_reason)
                                                    <br><small
                                                        class="text-muted mt-1 d-block">{{ Str::limit($loan->rejection_reason, 50) }}</small>
                                                @endif
                                            </td>
                                            <td><small class="text-muted">{{ $loan->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.loans.show', $loan) }}"
                                                    class="btn btn-sm btn-outline-primary">View</a>
                                                @if (in_array($loan->status, ['submitted', 'under_review']))
                                                    <a href="{{ route('admin.loans.approve', $loan) }}"
                                                        class="btn btn-sm btn-success">Approve</a>
                                                    <a href="{{ route('admin.loans.reject', $loan) }}"
                                                        class="btn btn-sm btn-danger">Reject</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $loans->appends(request()->query())->links() }}
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-cash-remove display-1 text-muted mb-3"></i>
                            <h4>No loans found</h4>
                            <p class="text-muted">Try adjusting your filters or create a new loan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
