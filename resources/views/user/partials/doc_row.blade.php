@php
    $labelOld = $oldDoc['label'] ?? '';
    $typeOld = $oldDoc['type'] ?? '';
@endphp

<div class="doc-row border rounded p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <strong class="doc-title">Document #{{ $i + 1 }}</strong>

        <button type="button" class="btn btn-sm btn-outline-danger remove-doc {{ $isFirst ? 'd-none' : '' }}">
            Remove
        </button>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label>Document Name (Label) <span class="text-danger">*</span></label>
                <input type="text" name="documents[{{ $i }}][label]"
                    value="{{ old("documents.$i.label", $labelOld) }}"
                    class="form-control @error("documents.$i.label") is-invalid @enderror"
                    placeholder="e.g. International Passport, Bank Statement">
                @error("documents.$i.label")
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group mb-2">
                <label>Document Category <span class="text-danger">*</span></label>
                <select name="documents[{{ $i }}][type]"
                    class="form-control @error("documents.$i.type") is-invalid @enderror">
                    <option value="">-- Select --</option>
                    <option value="passport" {{ old("documents.$i.type", $typeOld) === 'passport' ? 'selected' : '' }}>
                        International Passport</option>
                    <option value="id_card" {{ old("documents.$i.type", $typeOld) === 'id_card' ? 'selected' : '' }}>
                        Government ID</option>
                    <option value="proof_of_income"
                        {{ old("documents.$i.type", $typeOld) === 'proof_of_income' ? 'selected' : '' }}>Proof of Income
                    </option>
                    <option value="proof_of_address"
                        {{ old("documents.$i.type", $typeOld) === 'proof_of_address' ? 'selected' : '' }}>Proof of
                        Address</option>
                    <option value="other" {{ old("documents.$i.type", $typeOld) === 'other' ? 'selected' : '' }}>Other
                    </option>
                </select>
                @error("documents.$i.type")
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-group mb-0">
        <label>Upload File <span class="text-danger">*</span></label>

        <input type="file" name="documents[{{ $i }}][file]"
            class="file-upload-default @error("documents.$i.file") is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf">

        <div class="input-group col-xs-12">
            <input type="text" class="form-control file-upload-info" disabled placeholder="Choose file...">
            <span class="input-group-append">
                <button class="file-upload-browse btn btn-primary" type="button">Upload</button>
            </span>
        </div>

        @error("documents.$i.file")
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>
