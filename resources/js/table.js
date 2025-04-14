import { TabulatorFull as Tabulator } from 'tabulator-tables';

window.Tabulator = Tabulator;

var tabledata = [
    {name:"test!", identifier: "0-6161516-es", count: 1},
    {name:"test!", identifier: "0-6161516-es", count: 1},
    {name:"test!", identifier: "0-6161516-es", count: 1},
    {name:"test!", identifier: "0-6161516-es", count: 1},
    {name:"test!", identifier: "0-6161516-es", count: 1},
    {name:"test!", identifier: "0-6161516-es", count: 1},
    {name:"test!", identifier: "0-6161516-es", count: 1},
];

let response = await fetch('/api/products');
let data = await response.json();

console.log(data); // Will be 200 if the request was successful

//create Tabulator on DOM element with id "example-table"
var table = new Tabulator("#example-table", {
    data:data, //assign data to table
    layout:"fitColumns", //fit columns to width of table (optional)
    columns:[ //Define Table Columns
        {title:"Название",   field:"name", },
        {title:"Количество", field:"quantity", width:150},
        {title:"Идентификатор", field:"identifier", width:150},
    ],
});