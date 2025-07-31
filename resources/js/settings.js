window.loadContent = loadContent;

function loadContent(event, element) {
    event.preventDefault();
    const url = element.getAttribute('href');
    const target = document.getElementById('dynamic-setting-section');

    if (target) {
        fetch(url)
            .then(response => response.text())
            .then(html => {
                target.innerHTML = html;
            }).then(function () {
            switch (true) {
                case url.includes("manufacturers"):
                    updateManufacturersSettings();
                    break;

                case url.includes("categories"):
                    updateCategoriesSettings();
                    break;

                case url.includes("options"):
                    updateOptionsSettings();
                    break;

                default:
                    console.log("Совпадений не найдено");
            }
        }).catch(error => console.error('Error loading content:', error));
    }
}


function updateManufacturersSettings() {
    let checkboxes = document.querySelectorAll('.check-manufacturers-settings');

    console.log(checkboxes);
    // Теперь навесим обработчик события на каждый чекбокс
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // тянем атрибут
            let manufacturerId = this.getAttribute('id');
            let active = this.checked === true ? 1 : 0;

            axios.post(`/api/settings/update/manufacturers`, {
                manufacturer_id: manufacturerId,
                active: active,
            }).then(response => {
                console.log(response.data);
            })
                .catch(function (error) {
                // Обработка ошибки
                console.error(`Ошибка при запросе обновления настроек для производителя ${manufacturerId}:`, error);
            });
        });
    });
}

function updateCategoriesSettings() {
    let checkboxes = document.querySelectorAll('.check-categories-settings');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // тянем атрибут
            let categoryId = this.getAttribute('id');
            let active = this.checked === true ? 1 : 0;

            axios.post(`/api/settings/update/categories`, {
                category_id: categoryId,
                active: active,
            }).then(response => {
                console.log(response.data);
            })
                .catch(function (error) {
                // Обработка ошибки
                console.error(`Ошибка при запросе обновления настроек для категории ${categoryId}:`, error);
            });
        });
    });
}

function updateOptionsSettings() {
    let checkboxes = document.querySelectorAll('.check-options-settings');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // тянем атрибут
            let categoryId = this.getAttribute('id');
            let active = this.checked === true ? 1 : 0;

            console.log(`Category ID: ${categoryId}, Active: ${active}`);

            axios.post(`/api/settings/update/options`, {
                option_id: categoryId,
                active: active,
            }).then(response => {
                console.log(response.data);
            })
                .catch(function (error) {
                    // Обработка ошибки
                    console.error(`Ошибка при запросе обновления настроек для категории ${categoryId}:`, error);
                });
        });
    });
}
