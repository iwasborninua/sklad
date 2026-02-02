import './bootstrap';
import './table';
import './settings.js';
import './monitoring.js'
import flatpickr from "flatpickr";
import 'flatpickr/dist/flatpickr.min.css';

// локализация
import { Russian } from "flatpickr/dist/l10n/ru.js";
flatpickr.localize(Russian);

flatpickr(".datepicker", {
    dateFormat: "Y-m-d",
});
