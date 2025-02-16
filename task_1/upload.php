<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['uploadedFile'])) {
        $file = $_FILES['uploadedFile'];
        
        // Проверка на ошибки загрузки
        if ($file['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $file['tmp_name'];
            $fileName = basename($file['name']);
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

            // Проверка расширения файла
            if (strtolower($fileExtension) !== 'txt') {
                http_response_code(400); // Неверное расширение файла
                echo json_encode(['status' => 'error', 'message' => 'Неверное расширение файла.']);
                exit();
            } else {
                $uploadDir = __DIR__ . '/files/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $destination = $uploadDir . $fileName;

                if (move_uploaded_file($fileTmpPath, $destination)) {
                    // Чтение содержимого файла
                    $content = file_get_contents($destination);

                    // Получаем разделитель из POST
                    $delimiter = isset($_POST['delimiter']) ? trim($_POST['delimiter']) : ' '; // Получаем значение разделителя

                    // Проверка на пустой разделитель(пробел не считается)
                    if (empty($delimiter)) {
                        http_response_code(400); // Разделитель не может быть пустым
                        echo json_encode(['status' => 'error', 'message' => 'Разделитель не может быть пустым.']);
                        exit();
                    }

                    // Разделяем содержимое файла по указанному разделителю
                    $array = explode($delimiter, $content);

                    // Проверяем, был ли массив успешно разделен
                    if (count($array) <= 1 && strpos($content, $delimiter) === false) {
                        http_response_code(400); // Не удалось разделить строку
                        echo json_encode(['status' => 'error', 'message' => 'Не удалось разделить строку с использованием указанного разделителя.']);
                        exit();
                    }

                    // Подготавливаем результаты анализа
                    $analysisResults = [];
                    foreach ($array as $item) {
                        $trimmed = trim($item);
                        preg_match_all('/\d/', $trimmed, $matches);
                        $digitCount = count($matches[0]);
                        $analysisResults[] = [
                            'item' => htmlspecialchars($trimmed),
                            'digitCount' => $digitCount
                        ];
                    }

                    // Успешный ответ
                    http_response_code(200);
                    echo json_encode(['status' => 'success', 'file' => $fileName, 'analysis' => $analysisResults]);
                    exit();
                } else {
                    http_response_code(500); // Ошибка перемещения файла
                    echo json_encode(['status' => 'error', 'message' => 'Не удалось переместить загруженный файл.']);
                    exit();
                }
            }
        } else {
            http_response_code(400); // Ошибка загрузки файла
            echo json_encode(['status' => 'error', 'message' => 'Ошибка загрузки файла.']);
            exit();
        }
    }
}

http_response_code(400); // Некорректный запрос
echo json_encode(['status' => 'error', 'message' => 'Некорректный запрос.']);
exit();