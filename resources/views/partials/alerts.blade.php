@if (session('success') || session('error') || session('warning') || session('info') || $errors->any())

    <div class="container mt-3">

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert custom-alert custom-alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR --}}
        @if (session('error'))
            <div class="alert custom-alert custom-alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- WARNING --}}
        @if (session('warning'))
            <div class="alert custom-alert custom-alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        {{-- INFO --}}
        @if (session('info'))
            <div class="alert custom-alert custom-alert-info">
                {{ session('info') }}
            </div>
        @endif

        {{-- VALIDATION ERRORS --}}
        @if ($errors->any())
            <div class="alert custom-alert custom-alert-danger">
                <strong>Please correct the following:</strong>
                <ul class="mb-0 mt-2 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>

@endif
