//скрываем пробелы между полями(некрасиво)
hideSpacingElements();

const select = document.getElementsByName("type_val")[0]; // Получаем select

// Скрываем лишние поля в зависимости от начального значения
const defaultValue = select.value ? select.value : 1 //первоначально скрываем лишние поля
hideUnnecessaryInputs(defaultValue);

// Добавляем обработчик события на изменение значения select
select.addEventListener('change', event => {
    hideUnnecessaryInputs(event.target.value);
});

function hideUnnecessaryInputs(number) {
    const inputs = document.querySelectorAll('input[type="text"]');
    inputs.forEach(input => {
        const elementNumber = input.name.split('_')[1];
        input.parentElement.style.display = (number === elementNumber) ? "block" : "none";
    });

    const buttons = document.querySelectorAll('input[type="button"]');
    buttons.forEach(button => {
        const elementNumber = button.value.split(' ')[1];
        button.parentElement.style.display = (number === elementNumber) ? "block" : "none";
    });
}

function hideSpacingElements(){
    const spacingElements = document.querySelectorAll('p'); // Находим все элементы <p>
    spacingElements.forEach(p => {
        // Убираем отступы, если они находятся между полями
        if (p.innerHTML.trim() === '&nbsp;') {
            p.style.display = 'none'; // Скрываем элемент
        } else {
            p.style.display = 'block'; // Показываем элемент, если он не отступ 
        }
    });
}