import { TabulatorFull as Tabulator } from 'tabulator-tables';

window.Tabulator = Tabulator;

 let response = await fetch('/api/products');
 let data = await response.json();

//create Tabulator on DOM element with id "example-table"
var table = new Tabulator("#example-table", {
    data:data, //assign data to table
    layout:"fitColumns", //fit columns to width of table (optional)
    pagination:"local", //enable local pagination.
    paginationSize:20, //allow 10 rows per page of data
    columns:[ //Define Table Columns
        {title:"Название",   field:"name", },
        {title:"Количество", field:"quantity", width:150},
        {title:"Идентификатор", field:"identifier", width:150},
    ],
});

function changeTableData() {
    let category_id = document.getElementById('category_dropdown').value;
    let manufacturer_id = document.getElementById('manufacturer_dropdown').value;

    let url = '/api/products?category_id=' + category_id + '&manufacturer_id=' + manufacturer_id;
    fetch(url)
        .then(response => response.json())
        .then(data => {
            table.setData(data);
        })
        .catch(error => console.error('Error fetching data:', error));

    console.log(category_id, manufacturer_id);
}

document.querySelectorAll('#category_dropdown, #manufacturer_dropdown').forEach(function (element) {
    element.addEventListener('change', function () {
        changeTableData();
    });
 });