@extends('layouts.dashboard')

@section('content')
<div class="container">
    <h4 class="my-3 text-center">Проверяем работу наших ресурсов на основе HTTP ответов</h4>
    <div class="row">
        @foreach($resources as $resource)
            <section class="col-6">
                <div id="{{$resource}}" class="justify-content-between align-items-center mb-3 p-3 border rounded d-flex">
                    <div style="font-size: 16px">{{$resource}}</div>
                    <div class="status-code"></div>
                </div>
            </section>
        @endforeach
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        domainCheck();
    });
</script>
