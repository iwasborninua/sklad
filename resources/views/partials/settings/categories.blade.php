<button id="update_categories" type="button" class="btn btn-secondary">Обновить категории</button>

<div class="row">
    @foreach($categories as $category)
        <div class="mt-3 col-3">
            <div class="form-check form-switch">
                <input class="form-check-input check-categories-settings" type="checkbox" id="{{ $category->category_id }}" {{ $category->active ? 'checked' : '' }}>
                <label class="form-check-label" for="flexSwitchCheckDefault">{{ $category->name }}</label>
            </div>
        </div>
    @endforeach
</div>
