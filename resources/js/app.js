import './bootstrap';
import {initTabulator} from './table';
import {updateNanufacturersSettings, updateCategoriesSettings} from './settings.js';

window.updateNanufacturersSettings = updateNanufacturersSettings;
window.updateCategoriesSettings    = updateCategoriesSettings;
window.initTabulator                   = initTabulator;
