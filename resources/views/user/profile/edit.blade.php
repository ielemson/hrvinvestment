@extends('layouts.admin')
@section('title', 'Edit Profile')

@section('content')
    <div class="content-wrapper">


        {{-- GLOBAL ALERTS --}}
        @include('user.partials.alerts')

        @php
            $user = auth()->user();

            $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : asset('admin/images/faces/face1.jpg');

            $kycStatus = optional($user->kyc)->status ?? 'pending';
        @endphp

        <div class="row">

            {{-- LEFT PROFILE SUMMARY --}}
            <div class="col-lg-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{ $avatarUrl }}" class="img-lg rounded-circle mb-3" alt="profile">

                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-2">{{ $user->email }}</p>

                        <span class="badge {{ $kycStatus === 'approved' ? 'badge-success' : 'badge-warning' }}">
                            KYC: {{ strtoupper($kycStatus) }}
                        </span>

                        <div class="mt-4">
                            <a href="{{ route('user.dashboard') }}" class="btn btn-light btn-sm">
                                Back to Dashboard
                            </a>
                            {{-- If you have KYC route, you can enable this --}}
                            {{-- <a href="{{ route('user.kyc.index') }}" class="btn btn-outline-primary btn-sm ml-1">Update KYC</a> --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT SETTINGS --}}
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Account Management</h4>
                        <p class="card-description text-muted">
                            Update your profile details, password, and platform preferences.
                        </p>

                        {{-- TABS --}}
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#profile" role="tab">
                                    <i class="mdi mdi-account-outline mr-1"></i> Edit Profile
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#password" role="tab">
                                    <i class="mdi mdi-lock-outline mr-1"></i> Password
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#preferences" role="tab">
                                    <i class="mdi mdi-tune mr-1"></i> Preferences
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content pt-4">

                            {{-- ================= EDIT PROFILE ================= --}}
                            <div class="tab-pane fade show active" id="profile" role="tabpanel">

                                {{-- AVATAR UPLOAD (uses existing ProfileController route: profile.avatar) --}}
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card card-statistics">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-1">
                                                            <i class="mdi mdi-account-circle-outline mr-1"></i>
                                                            Profile Photo
                                                        </h5>
                                                        <p class="text-muted mb-0">
                                                            JPG/PNG/WebP. Max 2MB. Use a clear headshot.
                                                        </p>
                                                    </div>
                                                </div>

                                                <form class="mt-3" method="POST"
                                                    action="{{ route('user.profile.avatar') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PATCH')

                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $avatarUrl }}" class="img-sm rounded-circle mr-3"
                                                            alt="avatar">

                                                        <div class="flex-grow-1">
                                                            <div class="form-group mb-2">
                                                                <input type="file" name="avatar"
                                                                    class="form-control @error('avatar') is-invalid @enderror"
                                                                    accept=".jpg,.jpeg,.png,.webp" required>
                                                                @error('avatar')
                                                                    <div class="invalid-feedback d-block">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <button type="submit"
                                                                class="btn btn-outline-primary btn-sm btn-icon-text">
                                                                <i class="mdi mdi-cloud-upload btn-icon-prepend"></i>
                                                                Upload Photo
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- PROFILE UPDATE FORM --}}
                                <form method="POST" action="{{ route('user.profile.update') }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                        <small class="text-muted">Email cannot be changed for security reasons.</small>
                                    </div>

                                    <div class="form-group">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                            name="phone" value="{{ old('phone', $user->phone) }}"
                                            placeholder="+2348012345678">
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            name="address" value="{{ old('address', $user->address) }}"
                                            placeholder="Residential address">
                                        @error('address')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-icon-text">
                                        <i class="mdi mdi-content-save btn-icon-prepend"></i>
                                        Save Changes
                                    </button>
                                </form>
                            </div>

                            {{-- ================= UPDATE PASSWORD ================= --}}
                            <div class="tab-pane fade" id="password" role="tabpanel">
                                <form method="POST" action="{{ route('user.profile.password') }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="form-group">
                                        <label>Current Password</label>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            name="current_password" required>
                                        @error('current_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Confirm New Password</label>
                                        <input type="password" class="form-control" name="password_confirmation"
                                            required>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-icon-text">
                                        <i class="mdi mdi-lock-reset btn-icon-prepend"></i>
                                        Update Password
                                    </button>
                                </form>
                            </div>

                            {{-- ================= PREFERENCES ================= --}}
                            <div class="tab-pane fade" id="preferences" role="tabpanel">
                                <form method="POST" action="{{ route('user.profile.preferences') }}">
                                    @csrf
                                    @method('PATCH')

                                    <div class="alert alert-info">
                                        <i class="mdi mdi-information-outline"></i>
                                        Control how you receive loan updates and reminders.
                                    </div>

                                    <div class="form-check form-check-flat form-check-primary mb-3">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="notify_email"
                                                value="1"
                                                {{ old('notify_email', $user->notify_email) ?? true ? 'checked' : '' }}>
                                            Email Notifications
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>

                                    <div class="form-check form-check-flat form-check-primary mb-3">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="notify_app"
                                                value="1"
                                                {{ old('notify_app', $user->notify_app) ?? true ? 'checked' : '' }}>
                                            In-App Notifications
                                            <i class="input-helper"></i>
                                        </label>
                                    </div>

                                    <div class="mt-4">
                                        <h5 class="mb-2">Recommended Next Steps</h5>
                                        <ul class="list-arrow text-muted">
                                            <li>Complete KYC to unlock higher loan limits</li>
                                            <li>Add bank account to enable disbursement</li>
                                            <li>Enable reminders to avoid overdue penalties</li>
                                        </ul>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-icon-text">
                                        <i class="mdi mdi-tune btn-icon-prepend"></i>
                                        Save Preferences
                                    </button>
                                </form>
                            </div>

                        </div> {{-- tab-content --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
