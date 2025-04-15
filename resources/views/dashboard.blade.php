@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-3">
            <h3 class="my-3">Категории</h3>
            <select class="form-select">
                <option selected value="all">Все категории</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->category_id }}">{{ $category->description->name }}</option>
                @endforeach
            </select>

            <h3 class="my-3">Производители</h3>
            <select class="form-select">
                <option selected value="all">Все Производители</option>
                @foreach ($manufacturers as $manufacturer)
                    <option value="{{ $manufacturer->manufacturer_id }}">{{ $manufacturer->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-9">
            <div id="example-table"></div>
        </div>
    </div>
</div>
@endsection