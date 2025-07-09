@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-2 settings-sidebar border-end">
            <h5 class="my-3 mx-3">
                <a href="{{ route('partial.settings.manufacturers') }}" class="settings-link text-decoration-none text-uppercase">Производители</a>
            </h5>
            <h5 class="my-3 mx-3">
                <a href="{{ route('partial.settings.categories') }}" class="text-decoration-none text-uppercase settings-link">
                    Категории
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('.settings-link');

        links.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();

                const url = this.getAttribute('href');

                axios.get(url)
                    .then(function(response) {
                        document.getElementById('dynamic-setting-section').innerHTML = response.data;
                        console.log('Settings loaded successfully:');
                    })
                    .catch(function(error) {
                        console.error('Error fetching settings:', error);
                        alert('Произошла ошибка при загрузке настроек. Пожалуйста, попробуйте позже.');
                    });
            });
        });
    });
</script>
@endsection
