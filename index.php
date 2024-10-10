<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма отправки сообщений</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 90%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="file"] {
            width: 95.6%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus {
            border-color: #007BFF;
            outline: none;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        .file-input {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .file-input label {
            flex-grow: 1;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Отправить сообщение</h2>
    <form id="ajaxForm" enctype="multipart/form-data">
        <label for="name">Имя</label>
        <input type="text" id="name" name="name" required>

        <label for="phone">Телефон</label>
        <input type="tel" id="phone" name="phone" required>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="file1">Прикрепить файл 1</label>
        <input type="file" id="file1" name="file1">

        <label for="file2">Прикрепить файл 2</label>
        <input type="file" id="file2" name="file2">

        <button type="submit">Отправить</button>

        <!-- Контейнер для отображения сообщения об отправке -->
        <div id="formMessage" style="margin-top: 15px;"></div>
    </form>
</div>

    <script>
        $(document).ready(function() {
            $('#ajaxForm').on('submit', function(e) {
                e.preventDefault();

                // Очищаем предыдущее сообщение
                $('#formMessage').html('');

                // Создаем объект FormData для передачи данных формы и файлов
                var formData = new FormData(this);

                $.ajax({
                    url: '/mail.php',  // Укажите путь к вашему PHP-скрипту
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#formMessage').html('<span style="color: green;">Сообщение отправлено успешно!</span>');
                        $('#ajaxForm')[0].reset();  // Сбрасываем форму
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $('#formMessage').html('<span style="color: red;">Ошибка отправки формы: ' + textStatus + '</span>');
                    }
                });
            });
        });
    </script>
</body>
</html>
