 @if (in_array($kycStatus, ['pending', 'rejected']))
     <div class="row mb-4">
         <div class="col-12">
             <div class="alert alert-warning alert-dismissible fade show bg-gradient-warning text-white shadow-lg border-0"
                 role="alert">
                 <div class="d-flex align-items-center">
                     <i class="mdi mdi-alert-circle-outline me-3" style="font-size: 1.5rem;"></i>
                     <div class="flex-grow-1">
                         <h5 class="alert-heading mb-1">
                             <strong>KYC Verification Required</strong>
                         </h5>
                         <p class="mb-2">
                             @if ($kycStatus === 'pending')
                                 Your account is not fully verified. Complete KYC to access loans and full features.
                             @else
                                 KYC Rejected: {{ $kyc->rejection_reason ?? 'Please re-submit valid documents.' }}
                             @endif
                         </p>
                         <a href="{{ route('user.loans.create') }}" class="btn btn-light btn-sm fw-bold">
                             <i class="mdi mdi-upload me-1"></i> Complete KYC Now
                         </a>
                     </div>
                     {{-- <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button> --}}
                 </div>
             </div>
         </div>
     </div>
 @endif
