import {TabulatorFull as Tabulator} from 'tabulator-tables';
import data from "bootstrap/js/src/dom/data.js";

// Делаем Tabulator глобальным, если нужно в Blade
window.Tabulator = Tabulator;
window.buildTable  = buildTable ;

let table = null;

['category_dropdown', 'manufacturer_dropdown'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('change', buildTable);
    }
});

async function getOptions(manufacturerId = null) {
    let url = '/api/get-table-columns/';
    if (manufacturerId) {
        url += manufacturerId;
    }

    let options = await fetch(url)
        .then(response => response.json())
        .catch(error => {
            console.error('Error fetching options:', error);
            return [];
        });

    return options;
}

async function getProducts(categoryId = null, manufacturerId = null) {
    let url = null;

    const params = new URLSearchParams();

    if(categoryId) params.append('category_id', categoryId);
    if(manufacturerId) params.append('manufacturer_id', manufacturerId);

    if (categoryId == null && manufacturerId == null) {
        url = '/api/products';
    } else {
        url = `/api/products?${params.toString()}`;
    }

    let response = await fetch(url);
    return await response.json();
}

async function getTableColumns(options) {
    let columns = [
        { title: "Название", field: "name", width: 250 },
        { title: "Количество", field: "quantity", width: 100, editor: "number" },
    ];

    options.map(option => {
        columns.push({
            title: option.toString(),
            field: option.toString(),
            editor: "number",
        });
    });

    columns.push({ title: "Идентификатор", field: "identifier", width: 150 });

    return columns;
}

function createTable(columns, data) {
    table = new Tabulator("#example-table", {
        data: data,
        index: "id",
        layout: "fitColumns",
        pagination: "local",
        paginationSize: 20,
        columns: columns,
    });

    table.on("cellEdited", function (cell) {
        const rowData = cell.getData();        // Строка
        const field = cell.getField();         // Название ячейки, которую редактируем
        const newValue = cell.getValue();      // Новое значение

        if(field == 'quantity') {
            axios.put(`/api/product/${rowData.identifier}/${newValue}`)
                .then(response => {
                    console.log("Общее количество обновлено успешно", response.data);
                })
                .catch(error => {
                    console.error("Ошибка при обновлении общего количество", error);
                });
        } else {
            axios.put(`/api/option/${rowData.identifier}/${field}/${newValue}`)
                .then(response => {
                    console.log("Опция обновленна успешно", response.data);
                })
                .catch(error => {
                    console.error("Ошибка при обновлении опции", error);
                });
        }

        // console.log("Изменено поле:", field);
        // console.log("Новое значение:", newValue);
        // console.log("Вся строка:", rowData);
        // console.log(rowData.identifier);
    });
}


async function buildTable() {
    const manufacturerId = document.getElementById('manufacturer_dropdown').value == 'all' ? null : document.getElementById('manufacturer_dropdown').value;
    const categoryId = document.getElementById('category_dropdown').value == 'all' ? null : document.getElementById('category_dropdown').value;

    if (table) {
        table.destroy();
    }

    let options = await getOptions(manufacturerId);
    let products = await getProducts(categoryId, manufacturerId);
    let columns = await getTableColumns(options);

    createTable(columns, products);
}
