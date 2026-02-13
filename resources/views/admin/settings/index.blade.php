@extends('layouts.admin')

@section('content')
    @include('user.partials.alerts')
    <div class="row">

        {{-- BRANDING --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Branding</h4>
                    <p class="card-description">Update your site identity assets</p>

                    <form class="forms-sample" method="POST" action="{{ route('admin.settings.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Site Name --}}
                        <div class="form-group">
                            <label for="site_name">Site Name</label>
                            <input type="text" class="form-control @error('site_name') is-invalid @enderror"
                                id="site_name" name="site_name" value="{{ old('site_name', $settings->site_name) }}"
                                placeholder="e.g. HV Capitals">
                            @error('site_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Logo (Main) --}}
                        <div class="form-group">
                            <label>Main Logo</label>
                            <input type="file" name="logo" class="file-upload-default" accept="image/*">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled
                                    placeholder="Upload Main Logo">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                </span>
                            </div>

                            @if ($settings->logo_path)
                                <div class="mt-2">
                                    <small class="text-muted d-block mb-1">Current:</small>
                                    <img src="{{ asset($settings->logo_path) }}" alt="logo" style="height:40px">
                                </div>
                            @endif
                            @error('logo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Logo (Mini) --}}
                        <div class="form-group">
                            <label>Mini Logo</label>
                            <input type="file" name="logo_mini" class="file-upload-default" accept="image/*">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled
                                    placeholder="Upload Mini Logo">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                </span>
                            </div>

                            @if ($settings->logo_mini_path)
                                <div class="mt-2">
                                    <small class="text-muted d-block mb-1">Current:</small>
                                    <img src="{{ asset($settings->logo_mini_path) }}" alt="mini logo" style="height:40px">
                                </div>
                            @endif
                            @error('logo_mini')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Favicon --}}
                        <div class="form-group">
                            <label>Favicon</label>
                            <input type="file" name="favicon" class="file-upload-default" accept="image/*,.ico">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled
                                    placeholder="Upload Favicon (.png / .ico)">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                </span>
                            </div>

                            @if ($settings->favicon_path)
                                <div class="mt-2">
                                    <small class="text-muted d-block mb-1">Current:</small>
                                    <img src="{{ asset($settings->favicon_path) }}" alt="favicon"
                                        style="height:32px;width:32px">
                                </div>
                            @endif
                            @error('favicon')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">Save Branding</button>
                        <a href="javascript:;" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- CONTACT --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Contact</h4>
                    <p class="card-description">Set public contact details</p>

                    <form class="forms-sample" method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Contact Number --}}
                        <div class="form-group">
                            <label for="contact_phone">Contact Number</label>
                            <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                                id="contact_phone" name="contact_phone"
                                value="{{ old('contact_phone', $settings->contact_phone) }}"
                                placeholder="+234 800 000 0000">
                            @error('contact_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Contact Email --}}
                        <div class="form-group">
                            <label for="contact_email">Contact Email</label>
                            <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                id="contact_email" name="contact_email"
                                value="{{ old('contact_email', $settings->contact_email) }}"
                                placeholder="support@example.com">
                            @error('contact_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- About Us --}}
                        <div class="form-group">
                            <label for="contact_address">Contact Address</label>
                            <input class="form-control @error('contact_address') is-invalid @enderror"
                                id="contact_address" name="contact_address" placeholder="Contact Address"
                                value="{{ old('contact_address', $settings->contact_address) }}">

                            @error('contact_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                This content will appear on the About Us page and other public sections.
                            </small>
                        </div>

                        {{-- Actions --}}
                        <button type="submit" class="btn btn-primary mr-2">Save Settings</button>
                        <a href="{{ url()->previous() }}" class="btn btn-light">Cancel</a>
                    </form>

                </div>
            </div>
        </div>

        {{-- MONEY / CURRENCY --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Money / Currency</h4>
                    <p class="card-description">Set how money displays across the website</p>

                    <form class="forms-sample" method="POST" action="{{ route('admin.settings.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Currency --}}
                        <div class="form-group">
                            <label for="currency_code">Currency</label>
                            <select id="currency_code" name="currency_code"
                                class="form-control @error('currency_code') is-invalid @enderror">
                                @php
                                    $currencies = [
                                        ['code' => 'NGN', 'symbol' => '$', 'name' => 'Nigerian Naira'],
                                        ['code' => 'USD', 'symbol' => '$', 'name' => 'US Dollar'],
                                        ['code' => 'GBP', 'symbol' => '£', 'name' => 'British Pound'],
                                        ['code' => 'EUR', 'symbol' => '€', 'name' => 'Euro'],
                                        ['code' => 'CAD', 'symbol' => 'C$', 'name' => 'Canadian Dollar'],
                                        ['code' => 'AUD', 'symbol' => 'A$', 'name' => 'Australian Dollar'],
                                        ['code' => 'ZAR', 'symbol' => 'R', 'name' => 'South African Rand'],
                                        ['code' => 'KES', 'symbol' => 'KSh', 'name' => 'Kenyan Shilling'],
                                        ['code' => 'GHS', 'symbol' => 'GH₵', 'name' => 'Ghanaian Cedi'],
                                        ['code' => 'INR', 'symbol' => '₹', 'name' => 'Indian Rupee'],
                                    ];
                                    $selected = old('currency_code', $settings->currency_code ?? 'NGN');
                                @endphp

                                @foreach ($currencies as $c)
                                    <option value="{{ $c['code'] }}" data-symbol="{{ $c['symbol'] }}"
                                        {{ $selected === $c['code'] ? 'selected' : '' }}>
                                        {{ $c['code'] }} ({{ $c['symbol'] }}) — {{ $c['name'] }}
                                    </option>
                                @endforeach
                            </select>

                            @error('currency_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Currency Symbol --}}
                        <div class="form-group">
                            <label for="currency_symbol">Currency Symbol</label>
                            <input type="text" id="currency_symbol" name="currency_symbol"
                                class="form-control @error('currency_symbol') is-invalid @enderror"
                                value="{{ old('currency_symbol', $settings->currency_symbol ?? '$') }}"
                                placeholder="e.g. $ or $ or £">
                            @error('currency_symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tip: this can auto-fill when you select a currency.</small>
                        </div>

                        {{-- Symbol Position --}}
                        <div class="form-group">
                            <label for="currency_position">Symbol Position</label>
                            <select id="currency_position" name="currency_position"
                                class="form-control @error('currency_position') is-invalid @enderror">
                                <option value="before"
                                    {{ old('currency_position', $settings->currency_position ?? 'before') === 'before' ? 'selected' : '' }}>
                                    Before amount ($10,000)
                                </option>
                                <option value="after"
                                    {{ old('currency_position', $settings->currency_position ?? 'before') === 'after' ? 'selected' : '' }}>
                                    After amount (10,000$)
                                </option>
                            </select>
                            @error('currency_position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Decimal Places --}}
                        <div class="form-group">
                            <label for="decimal_places">Decimal Places</label>
                            <input type="number" id="decimal_places" name="decimal_places"
                                class="form-control @error('decimal_places') is-invalid @enderror"
                                value="{{ old('decimal_places', $settings->decimal_places ?? 0) }}" min="0"
                                max="4">
                            @error('decimal_places')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">0 for Naira-style, 2 for Dollar/Euro-style.</small>
                        </div>

                        {{-- Separators (Optional but useful) --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="thousand_separator">Thousand Separator</label>
                                    <input type="text" id="thousand_separator" name="thousand_separator"
                                        class="form-control @error('thousand_separator') is-invalid @enderror"
                                        value="{{ old('thousand_separator', $settings->thousand_separator ?? ',') }}"
                                        placeholder=",">
                                    @error('thousand_separator')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="decimal_separator">Decimal Separator</label>
                                    <input type="text" id="decimal_separator" name="decimal_separator"
                                        class="form-control @error('decimal_separator') is-invalid @enderror"
                                        value="{{ old('decimal_separator', $settings->decimal_separator ?? '.') }}"
                                        placeholder=".">
                                    @error('decimal_separator')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="interest_rate">Loan Interest Rate (%)</label>
                                    <input type="text" id="interest_rate" name="interest_rate"
                                        class="form-control @error('interest_rate') is-invalid @enderror"
                                        value="{{ old('interest_rate', $settings->interest_rate ?? '0') }}"
                                        placeholder=".">
                                    @error('decimal_separator')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>

                        {{-- Preview (Dummy) --}}
                        <div class="alert alert-light border">
                            <strong>Preview:</strong>
                            <span id="currency-preview">$10,000</span>
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">Save Currency</button>
                        <a href="javascript:;" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

        {{-- SEO / META --}}
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">SEO / Meta Tags</h4>
                    <p class="card-description">Search engine and social sharing settings</p>

                    <form class="forms-sample" method="POST" action="{{ route('admin.settings.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                        id="meta_title" name="meta_title"
                                        value="{{ old('meta_title', $settings->meta_title) }}"
                                        placeholder="Title for Google results">
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <input type="text"
                                        class="form-control @error('meta_keywords') is-invalid @enderror"
                                        id="meta_keywords" name="meta_keywords"
                                        value="{{ old('meta_keywords', $settings->meta_keywords) }}"
                                        placeholder="loan, finance, investment, nigeria">
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                name="meta_description" rows="4" placeholder="A short description for search engines">{{ old('meta_description', $settings->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- About Us --}}
                        <div class="form-group">
                            <label for="about_us">About Us</label>
                            <textarea class="form-control @error('about_us') is-invalid @enderror" id="about_us" name="about_us"
                                rows="6" placeholder="Write a brief description about your company...">{{ old('about_us', $settings->about_us) }}</textarea>

                            @error('about_us')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                This content will appear on the About Us page and other public sections.
                            </small>
                        </div>

                        {{-- Open Graph Image --}}
                        <div class="form-group">
                            <label>OG Image (Social share)</label>
                            <input type="file" name="og_image" class="file-upload-default" accept="image/*">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled
                                    placeholder="Upload OG Image">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
                                </span>
                            </div>

                            @if ($settings->og_image_path)
                                <div class="mt-2">
                                    <small class="text-muted d-block mb-1">Current:</small>
                                    <img src="{{ asset($settings->og_image_path) }}" alt="og image" style="height:60px">
                                </div>
                            @endif
                            @error('og_image')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary mr-2">Save SEO</button>
                        <a href="javascript:;" class="btn btn-light">Cancel</a>
                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        {{-- Small helper script: auto fill symbol + preview --}}
        <script>
            (function() {
                const currencySelect = document.getElementById('currency_code');
                const symbolInput = document.getElementById('currency_symbol');
                const positionSelect = document.getElementById('currency_position');
                const decimalsInput = document.getElementById('decimal_places');
                const thousandInput = document.getElementById('thousand_separator');
                const decimalSepInput = document.getElementById('decimal_separator');
                const preview = document.getElementById('currency-preview');

                if (!currencySelect || !symbolInput || !preview) return;

                function formatNumber(num, decimals, decSep, thouSep) {
                    const n = Number(num).toFixed(decimals);
                    const parts = n.split('.');
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thouSep);
                    return parts.length > 1 ? parts[0] + decSep + parts[1] : parts[0];
                }

                function updatePreview() {
                    const symbol = symbolInput.value || '';
                    const position = positionSelect.value || 'before';
                    const decimals = parseInt(decimalsInput.value || '0', 10);
                    const thouSep = (thousandInput.value || ',');
                    const decSep = (decimalSepInput.value || '.');

                    const amount = formatNumber(10000, decimals, decSep, thouSep);
                    preview.textContent = position === 'after' ? `${amount}${symbol}` : `${symbol}${amount}`;
                }

                currencySelect.addEventListener('change', function() {
                    const option = currencySelect.options[currencySelect.selectedIndex];
                    const symbol = option.getAttribute('data-symbol');
                    if (symbol && (!symbolInput.value || symbolInput.value.length < 1)) {
                        symbolInput.value = symbol;
                    }
                    updatePreview();
                });

                [symbolInput, positionSelect, decimalsInput, thousandInput, decimalSepInput].forEach(el => {
                    if (el) el.addEventListener('input', updatePreview);
                });

                updatePreview();
            })();
        </script>
    @endpush
@endsection
