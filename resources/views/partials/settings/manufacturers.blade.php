<button id="update_manufacturers" type="button" class="btn btn-secondary">Обновить производителей</button>

<div class="row">
    @foreach($manufacturers as $manufacturer)
        <div class="mt-3 col-3">
            <div class="form-check form-switch">
                <input class="form-check-input check-manufacturers-settings" type="checkbox" id="{{ $manufacturer->manufacturer_id }}" {{ $manufacturer->active ? 'checked' : '' }}>
                <label class="form-check-label" for="flexSwitchCheckDefault">{{ $manufacturer->name }}</label>
            </div>
        </div>
    @endforeach
</div>
