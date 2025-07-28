<div class="mb-4">
    @if ($label)
        <label class="form-label" for="{{ $name }}">
            {{ $label }} @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif


    {{ $slot }}

    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
