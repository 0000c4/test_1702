<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Загрузка файла</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Загрузите .txt файл</h1>
        <form id="uploadForm" enctype="multipart/form-data">
            <input type="file" name="uploadedFile" accept=".txt" required><br>

            <label>
                <input type="checkbox" id="useSpaceAsDelimiter" checked>
                Использовать пробел в качестве разделителя(Снимите галочку если хотите использовать другой разделитель)
            </label><br>

            <input type="text" name="delimiter" id="delimiterInput" placeholder="Введите разделитель" style="display: none;">
            <button type="submit">Загрузить</button>
        </form>

        <div class="analysis" id="analysisResult"></div>
    </div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const useSpaceAsDelimiter = document.getElementById('useSpaceAsDelimiter').checked;
            const delimiterInput = document.getElementById('delimiterInput');

            const formData = new FormData(this);

            // Удаляем поле разделителя из formData, если чекбокс активен
            if (useSpaceAsDelimiter) {
                delimiterInput.value = '';
                formData.delete('delimiter');
            } else {
                delimiterInput.value = delimiterInput.value || ' '; // Устанавливаем значение по умолчанию как пробел, если поле пустое
            }

            try {
                const response = await fetch('upload.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    alert(errorData.message || 'Произошла ошибка при загрузке файла.');
                    throw new Error(errorData.message);
                }

                const data = await response.json();

                // Обработка и отображение результата
                displayAnalysis(data);
            } catch (error) {
                console.error('Ошибка:', error);
            }
        });

        // Отображение или скрытие поля ввода разделителя в зависимости от состояния чекбокса
        document.getElementById('useSpaceAsDelimiter').addEventListener('change', function() {
            const delimiterInput = document.getElementById('delimiterInput');
            delimiterInput.style.display = this.checked ? 'none' : 'block';
        });

        function displayAnalysis(data) {
            const analysisResultDiv = document.getElementById('analysisResult');
            analysisResultDiv.innerHTML = ''; // Очистка предыдущего анализа

            if (data.status === 'success') {
                data.analysis.forEach(item => {
                    const div = document.createElement('div');
                    div.textContent = `${item.item} = ${item.digitCount}`;
                    analysisResultDiv.appendChild(div);
                });
            } else {
                analysisResultDiv.textContent = 'Произошла ошибка при загрузке файла.';
                alert('Ошибка: ' + (data.message || 'Неизвестная ошибка.'));
            }
        }
    </script>

</body>

</html>