<!DOCTYPE html>
<html>

<head>
    <title>Gracias</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            /* Alinea verticalmente */
            min-height: 100vh;
            /* Asegura que el cuerpo ocupe toda la altura de la ventana */
            margin: 0;
            /* Elimina márgenes predeterminados del body */
        }

        h1 {
            text-align: center;
            flex-grow: 1;
            /* Permite que el h1 ocupe el espacio disponible */
            display: flex;
            align-items: center;
            /* Centra verticalmente el texto dentro del h1 */
            justify-content: center;
            /* Centra horizontalmente el texto dentro del h1 */
        }

        .button-container {
            /* Contenedor para el botón */
            text-align: center;
            /* Centra el botón horizontalmente */
            padding-bottom: 20px;
            /* Espacio debajo del botón */
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            /* Color de fondo */
            color: white;
            /* Color del texto */
            text-decoration: none;
            /* Quita el subrayado del enlace */
            border-radius: 5px;
            /* Bordes redondeados */
            border: none;
            /* Quita el borde */
            cursor: pointer;
            /* Cambia el cursor al pasar por encima */
            transition: background-color 0.3s ease;
            /* Transición suave del color de fondo */
        }

        .button:hover {
            background-color: #45a049;
            /* Color de fondo al pasar el ratón */
        }
    </style>
</head>

<body>
    <h1>Gracias por completar y enviar el formulario</h1>
    <div class="button-container">
        <a href="index.php" class="button">Insertar Nuevos Datos</a>
    </div>
</body>

</html>