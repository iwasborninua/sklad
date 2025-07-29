<div class="row">
    @foreach($options as $option)
        <div class="mt-3 col-3">
            <div class="form-check form-switch">
                <input onchange="updateOptionsSettings(this)" class="form-check-input check-options-settings" type="checkbox" id="{{ $option->option_id }}" {{ $option->active ? 'checked' : '' }}>
                <label class="form-check-label" for="flexSwitchCheckDefault">{{$option->name}}</label>
            </div>
        </div>
    @endforeach
</div>
