import { TabulatorFull as Tabulator } from 'tabulator-tables';

// Делаем Tabulator глобальным, если нужно в Blade
window.Tabulator = Tabulator;

let table = null;

// Загружаем товары и создаём таблицу
async function firstLoadTable() {
    try {
        const response = await fetch('/api/products');
        const data = await response.json();

        // колонки для таблицы
        let columns = getTableColums('general');

        table = createTable(data, columns);

        table.on("cellEdited", function (cell) {
            const count = cell.getValue();
            const identifier = cell.getData().identifier;

            const url = `/api/product/${identifier}/${count}`;

            if (!identifier) {
                alert('Можно редактировать только товары с идентификатором!');
                return;
            }

            fetch(url, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    count: count,
                    identifier: identifier,
                }),
            });
        });

        console.log('Таблица создана:', table);
    } catch (error) {
        console.error('Ошибка загрузки данных:', error);
    }
}

// Меняем данные таблицы при смене фильтров
function changeTableData() {
    const categoryElement = document.getElementById('category_dropdown');
    const manufacturerElement = document.getElementById('manufacturer_dropdown');

    if (!categoryElement || !manufacturerElement) return;

    const category_id = categoryElement.value;
    const manufacturer_id = manufacturerElement.value;

    const url = `/api/products?category_id=${category_id}&manufacturer_id=${manufacturer_id}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            table.setData(data);
        })
        .catch(error => console.error('Ошибка при обновлении данных:', error));

    console.log('Выбраны:', category_id, manufacturer_id);
}

function createTable(data, columns) {
    if (table) {
        table.destroy();
    }

    return new Tabulator("#example-table", {
        data: data,
        index: "id",
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 20,
        columns: columns,
    });
}

function getTableColums(type) {
    if (type == 'general') {
        return [
            { title: "Название", field: "name" },
            { title: "Количество", field: "quantity", width: 150, editor: "number" },
            { title: "Идентификатор", field: "identifier", width: 150 }
        ];
    } else if (type == 'options') {
        return [
            { title: "Название", field: "name" },
            { title: "Идентификатор", field: "identifier", width: 150 },
        ];
    }
}


export function initTabulator() {
    if (table) {
        table.destroy();
    }

    // Навешиваем события на селекты
    ['category_dropdown', 'manufacturer_dropdown'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('change', changeTableData);
        }
    });
    // Загружаем товары и создаём таблицу (если не создана)
    firstLoadTable();
}
