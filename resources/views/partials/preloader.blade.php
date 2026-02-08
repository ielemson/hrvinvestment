  @php
      $logo = $siteSettings->logo_path ?? 'assets/images/logo-342.png';
      $siteName = $siteSettings->site_name ?? 'HV Capitals';
  @endphp
  <div class="preloader">
      <div class="preloader-logo">
          <img src="{{ asset($logo) }}" alt="" width="171" height="39" srcset="{{ asset($logo) }} 2x" />
      </div>
      <div class="preloader-body">
          <div id="loadingProgressG">
              <div class="loadingProgressG" id="loadingProgressG_1"></div>
          </div>
      </div>
  </div>
