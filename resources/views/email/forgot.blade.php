<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación de Contraseña</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
        }

        p {
            color: #555;
        }

        .code {
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            font-size: 20px;
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Recuperación de Contraseña</h1>
        <p>Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. Utiliza el siguiente código para completar el proceso:</p>

        <div class="code">{{ $code }}</div>

        <p>Si no has solicitado esta recuperación, puedes ignorar este correo.</p>

        <div class="footer">
            <p>Este correo electrónico fue enviado automáticamente. Por favor, no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>