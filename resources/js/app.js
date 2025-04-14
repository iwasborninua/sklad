import './bootstrap';
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

//create Tabulator on DOM element with id "example-table"
var table = new Tabulator("#example-table", {
    data:tabledata, //assign data to table
    layout:"fitColumns", //fit columns to width of table (optional)
    columns:[ //Define Table Columns
        {title:"Название",   field:"name"},
        {title:"Количество", field:"count"},
        {title:"Identifier", field:"identifier"},
    ],
});
