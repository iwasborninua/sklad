@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-2 settings-sidebar border-end">
            <h5 class="my-3 mx-3">
                <a href="{{ route('partial.settings.manufacturers') }}" onclick="loadContent(event, this)" class="settings-link text-decoration-none text-uppercase">Производители</a>
            </h5>
            <h5 class="my-3 mx-3">
                <a href="{{ route('partial.settings.categories') }}" onclick="loadContent(event, this)" class="text-decoration-none text-uppercase settings-link">
                    Категории
                </a>
            </h5>
            <h5 class="my-3 mx-3">
                <a href="{{ route('partial.settings.options') }}" onclick="loadContent(event, this)" class="text-decoration-none text-uppercase settings-link">
                    Опции
                </a>
            </h5>
        </div>
        <div id="dynamic-setting-section" class="col-10 my-3">
            <p>На данной странице вы можете управлять настройками склада.</p>
            <p>Выберите нужный раздел слева, чтобы начать.</p>
            <p>На данный момент функционал расширяется и разрабатывается...</p>
        </div>
    </div>
</div>

@endsection
