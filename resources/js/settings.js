export function updateNanufacturersSettings() {
    let checkboxes = document.querySelectorAll('.check-manufacturers-settings');

    // Теперь навесим обработчик события на каждый чекбокс
    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // тянем атрибут
            let manufacturerId = this.getAttribute('id');
            let active = this.checked === true ? 1 : 0;

            axios.post(`/api/settings/update/manufacturers/`, {
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

export function updateCategoriesSettings() {
    let checkboxes = document.querySelectorAll('.check-categories-settings');

    checkboxes.forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // тянем атрибут
            let categoryId = this.getAttribute('id');
            let active = this.checked === true ? 1 : 0;

            axios.post(`/api/settings/update/categories/`, {
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
