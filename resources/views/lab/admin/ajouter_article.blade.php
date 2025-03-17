<!-- Date de publication -->
<div class="form-group">
    <label for="datePubArt" class="font-weight-bold">Date de publication</label>
    <input type="date" class="form-control @error('datePubArt') is-invalid @enderror"
           id="datePubArt" name="datePubArt" value="{{ old('datePubArt') }}">
    @error('datePubArt')
        <span class="invalid-feedback">{{ $message }}</span>
    @enderror
</div>
