@extends('layouts.dashboard')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-2">
                <h3>Errors-seeds.ua</h3>
            </div>
            <div class="col-10">
                <section class="p-x-5">
                    <h4>Статистика внутреннего поиска</h4>
                    <div class="row #select-block">
                        <div class="col-2">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">
                                    <img
                                        src="{{ Vite::asset('resources/icons/calendar.svg')}}"
                                        alt="calendar"
                                        width='20px'
                                    ></span>
                                <input
                                    type="text"
                                    class="form-control datepicker site-search"
                                    name="date_from"
                                    placeholder="from"
                                    id="search_from"
                                    value=""
                                >
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">
                                    <img
                                        src="{{ Vite::asset('resources/icons/calendar.svg')}}"
                                        alt="calendar"
                                        width='20px'
                                    ></span>
                                <input
                                    type="text"
                                    class="form-control datepicker site-search"
                                    name="date_to"
                                    placeholder="to"
                                    id="search_to"
                                    value=""
                                >
                            </div>
                        </div>
                        <div class="col-2">
                            <select class="form-select site-search" aria-label="Default select example" id="search_select">
                                <option value="5" selected>5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-success disabled" id="export_pdf">Экспорт в PDF</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <table class="table mt-5" id="resultsTable">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Тег</th>
                                    <th scope="col">Количество</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>

        let pdf_button = document.getElementById('export_pdf');

        document.querySelectorAll('.site-search').forEach(el => {
            el.addEventListener('change', handler);
        });

        async function handler(e) {
            let limit = document.getElementById('search_select').value;
            let from = document.getElementById('search_from').value;
            let to = document.getElementById('search_to').value;

            if (from && to != "" ) {
                pdf_button.classList.remove('disabled');

                const res = await fetch('/api/statistic/search/show', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        limit: limit,
                        from: from,
                        to: to,
                    })
                });

                if (!res.ok) {
                    console.error('Request failed:', res.status);
                    return;
                }

                const data = await res.json(); // получаем все это говно

                renderTable(data);
            } else {
                pdf_button.classList.add('disabled');
            }
        }

        function renderTable(data) {
            const tbody = document.querySelector('#resultsTable tbody');

            tbody.innerHTML = '';

            if (!Array.isArray(data)) {
                console.log('это не массив');
                return;
            }

            const html = data.map((row, index) => `
                <tr>
                  <td>${index + 1}</td>
                  <td>${escapeHtml(row.search ?? '')}</td>
                  <td>${escapeHtml(row.count ?? '')}</td>
                </tr>
              `).join('');

            tbody.innerHTML = html;
        }

        // минимальная экранизация, чтобы не словить XSS
        function escapeHtml(v) {
            return String(v)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        document.getElementById('export_pdf').addEventListener('click', async () => {
            const from  = document.getElementById('search_from').value;
            const to    = document.getElementById('search_to').value;
            const limit = document.getElementById('search_select').value ?? 50;

            if (!from || !to) return alert('Выберите период');

            const res = await fetch('/api/statistic/search/export/pdf', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ from, to, limit }),
            });

            if (!res.ok) return alert('Ошибка экспорта PDF');

            const blob = await res.blob();

            let filename = 'export.pdf';
            const cd = res.headers.get('content-disposition');
            const m = cd && cd.match(/filename="?(.*?)"?$/i);
            if (m?.[1]) filename = m[1];

            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        });
    </script>

@endsection
