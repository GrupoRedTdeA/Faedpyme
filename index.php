<?php
// Configuración de la conexión a la base de datos (sin cambios)
$servidor = "localhost";
$usuario = "root";
$clave = "";
$basededatos = "faedpyme";

try {
    $enlace = new PDO("mysql:host=$servidor;dbname=$basededatos;charset=utf8mb4", $usuario, $clave);
    $enlace->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    try {
        // 1. Insertar en la tabla empresas
        $stmt_empresa = $enlace->prepare("INSERT INTO empresas (empresa, email, sector, fundacion, tamano, familiar, genero, porcentaje_mujeres, estudios_director, porcentaje_ventas_internacionales, numero_paises) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_empresa->execute([
            $_POST['empresa'],
            $_POST['email'],
            $_POST['sector'],
            $_POST['fundacion'],
            $_POST['tamano'],
            $_POST['familiar'],
            $_POST['genero'],
            $_POST['mujeres'],
            $_POST['estudios'],
            $_POST['porcentaje_ventas_internacionales'],
            $_POST['numero_paises']
        ]);

        $empresa_id = $enlace->lastInsertId(); // Obtener el ID de la empresa insertada

        // 2. Insertar en la tabla evolucion_variables
        $variables = ['ventas', 'trabajadores', 'inversiones'];
        foreach ($variables as $variable) {
            $stmt_evolucion = $enlace->prepare("INSERT INTO evolucion_variables (empresa_id, variable, ultimo_ano, proximo_ano) VALUES (?, ?, ?, ?)");
            $stmt_evolucion->execute([
                $empresa_id,
                $variable,
                $_POST[$variable . '_ultimo'],
                $_POST[$variable . '_proximo']
            ]);
        }

        // 3. Insertar en la tabla indicadores_rendimiento
        $indicadores = [
            'calidad_productos',
            'eficiencia_procesos',
            'satisfaccion_clientes',
            'rapidez_adaptacion',
            'rapidez_crecimiento',
            'rentabilidad',
            'satisfaccion_empleados',
            'absentismo_laboral'
        ];
        foreach ($indicadores as $indicador) {
            $stmt_indicador = $enlace->prepare("INSERT INTO indicadores_rendimiento (empresa_id, indicador, valor) VALUES (?, ?, ?)");
            $stmt_indicador->execute([
                $empresa_id,
                $indicador,
                $_POST[$indicador]
            ]);
        }

        // 4. Insertar en la tabla innovaciones
        $innovaciones = [
            'innovacion_productos',
            'innovacion_lanzamiento',
            'nuevos_mercados',
            'alianzas',
            'mejoras_procesos',
            'nuevos_bienes',
            'mejoras_gestion',
            'mejoras_compras',
            'mejoras_comerciales'
        ];
        foreach ($innovaciones as $innovacion) {
            $stmt_innovacion = $enlace->prepare("INSERT INTO innovaciones (empresa_id, innovacion, valor) VALUES (?, ?, ?)");
            $stmt_innovacion->execute([
                $empresa_id,
                $innovacion,
                $_POST[$innovacion]
            ]);
        }


        header("Location: gracias.php");
        exit();
    } catch (PDOException $e) {
        echo "Error al insertar datos: " . $e->getMessage();
        //Puedes agregar un log para depuración más efectiva
        error_log("Error en el registro de la encuesta: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta Faedpyme 2024</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="logo-container">
            <img src="Logo_faedpyme.jpg" alt="Logo de Faedpyme">
        </div>
        <h1>ESCASEZ DE PERSONAL, PRODUCTIVIDAD Y RENDIMIENTO la MIPyME en IberoAmerica</h1>
        <p class="descripcion">(La información brindada a partir de este cuestionario será tratada con confidencialidad y para los usos pertinentes de la realización del estudio según lo establecido en la Ley Orgánica 3/2018 de 5 de diciembre de Protección de Datos Personales y Garantía de los Derechos Digitales)</p>
    </header>

    <main>
        <form action="#" name="faedpyme" method="post">
            <!-- Sección 1: Datos de la empresa -->
            <fieldset>
                <legend>Datos de la empresa</legend>
                <div class="form-group">
                    <label for="empresa">Empresa <span class="required">*</span></label>
                    <input type="text" id="empresa" name="empresa" placeholder="Ingrese el nombre de la empresa" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="Ingrese el correo electrónico" required>
                </div>

                <div class="form-group">
                    <label for="sector">1. Sector de actividad</label>
                    <input type="text" id="sector" name="sector" placeholder="Ingrese el sector de actividad" required>
                </div>

                <div class="form-group">
                    <label for="fundacion">2. ¿En qué año se fundó su empresa?</label>
                    <input type="text" id="fundacion" name="fundacion" placeholder="Ingrese el año de fundación" required>
                </div>

                <div class="form-group">
                    <label for="tamano">3. Tamaño de la empresa</label>
                    <select id="tamano" name="tamano">
                        <option value="">Seleccione una opción</option>
                        <option value="micro">Microempresa (6 a 9 empleados)</option>
                        <option value="pequena">Pequeña (10 a 49)</option>
                        <option value="mediana">Mediana (50 a 249)</option>
                    </select>
                </div>
            </fieldset>

            <!-- Sección 2: Información General Anual -->
            <fieldset>
                <legend>Información General Anual</legend>
                <div class="form-group">
                    <label for="familiar">4. ¿Considera usted que la empresa es familiar?</label>
                    <select id="familiar" name="familiar" required>
                        <option value="">Seleccione una opción</option>
                        <option value="si">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="genero">5. ¿Cuál es el género del director general/gerente?</label>
                    <select id="genero" name="genero" required>
                        <option value="">Seleccione una opción</option>
                        <option value="1">Masculino</option>
                        <option value="2">Femenino</option>
                        <option value="3">Otro</option>

                    </select>
                </div>

                <div class="form-group">
                    <label>6. ¿Proporción en el equipo directivo de mujeres? (%)</label>
                    <input type="text" name="mujeres" placeholder="%" required>
                </div>

                <!-- Tablas y otras secciones -->
                <h3>8. En su opinión, ¿cuál ha sido la evolución en su empresa en las siguientes variables?</h3>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th colspan="3">ÚLTIMO AÑO</th>
                            <th colspan="3">PRÓXIMO AÑO</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>Ha disminuido</th>
                            <th>Sigue igual</th>
                            <th>Ha aumentado</th>
                            <th>Disminuirá</th>
                            <th>Seguirá igual</th>
                            <th>Aumentará</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Ventas</td>
                            <td><input type="radio" name="ventas_ultimo" value="disminuido"></td>
                            <td><input type="radio" name="ventas_ultimo" value="igual"></td>
                            <td><input type="radio" name="ventas_ultimo" value="aumentado"></td>
                            <td><input type="radio" name="ventas_proximo" value="disminuira"></td>
                            <td><input type="radio" name="ventas_proximo" value="igual"></td>
                            <td><input type="radio" name="ventas_proximo" value="aumentara"></td>
                        </tr>
                        <tr>
                            <td>Número de trabajadores</td>
                            <td><input type="radio" name="trabajadores_ultimo" value="disminuido"></td>
                            <td><input type="radio" name="trabajadores_ultimo" value="igual"></td>
                            <td><input type="radio" name="trabajadores_ultimo" value="aumentado"></td>
                            <td><input type="radio" name="trabajadores_proximo" value="disminuira"></td>
                            <td><input type="radio" name="trabajadores_proximo" value="igual"></td>
                            <td><input type="radio" name="trabajadores_proximo" value="aumentara"></td>
                        </tr>
                        <tr>
                            <td>Inversiones</td>
                            <td><input type="radio" name="inversiones_ultimo" value="disminuido"></td>
                            <td><input type="radio" name="inversiones_ultimo" value="igual"></td>
                            <td><input type="radio" name="inversiones_ultimo" value="aumentado"></td>
                            <td><input type="radio" name="inversiones_proximo" value="disminuira"></td>
                            <td><input type="radio" name="inversiones_proximo" value="igual"></td>
                            <td><input type="radio" name="inversiones_proximo" value="aumentara"></td>
                        </tr>
                </table>

                <h3>9. Del total de sus ventas en 2023, ¿qué porcentaje de sus ventas proviene de mercados internacionales?</h3>
                <input type="number" name="porcentaje_ventas_internacionales" min="0" max="100"> %
                <h4>Si es superior a 0 ¿A cuántos países?</h4>
                <input type="number" name="numero_paises" min="0">


                <div class="question" id="pregunta10">
                    <h3>10. En comparación con sus competidores directos, indique cual es la posición de su empresa con los siguientes indicadores de rendimiento:</h3>
                    <table>
                        <tr>
                            <th>Indicador</th>
                            <th>Peor</th>
                            <th colspan="3">Igual</th>
                            <th>Mejor</th>
                        </tr>
                        <tr>
                            <td>1. Calidad de sus productos</td>
                            <td><input type="radio" name="calidad_productos" value="1" required><label>1</label></td>
                            <td><input type="radio" name="calidad_productos" value="2"><label>2</label></td>
                            <td><input type="radio" name="calidad_productos" value="3"><label>3</label></td>
                            <td><input type="radio" name="calidad_productos" value="4"><label>4</label></td>
                            <td><input type="radio" name="calidad_productos" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>2. Eficiencia de los procesos</td>
                            <td><input type="radio" name="eficiencia_procesos" value="1" required><label>1</label></td>
                            <td><input type="radio" name="eficiencia_procesos" value="2"><label>2</label></td>
                            <td><input type="radio" name="eficiencia_procesos" value="3"><label>3</label></td>
                            <td><input type="radio" name="eficiencia_procesos" value="4"><label>4</label></td>
                            <td><input type="radio" name="eficiencia_procesos" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>3. Satisfacción de clientes</td>
                            <td><input type="radio" name="satisfaccion_clientes" value="1" required><label>1</label></td>
                            <td><input type="radio" name="satisfaccion_clientes" value="2"><label>2</label></td>
                            <td><input type="radio" name="satisfaccion_clientes" value="3"><label>3</label></td>
                            <td><input type="radio" name="satisfaccion_clientes" value="4"><label>4</label></td>
                            <td><input type="radio" name="satisfaccion_clientes" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>4. Rapidez de adaptación a los cambios</td>
                            <td><input type="radio" name="rapidez_adaptacion" value="1" required><label>1</label></td>
                            <td><input type="radio" name="rapidez_adaptacion" value="2"><label>2</label></td>
                            <td><input type="radio" name="rapidez_adaptacion" value="3"><label>3</label></td>
                            <td><input type="radio" name="rapidez_adaptacion" value="4"><label>4</label></td>
                            <td><input type="radio" name="rapidez_adaptacion" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>5. Rapidez de crecimiento de las ventas</td>
                            <td><input type="radio" name="rapidez_crecimiento" value="1" required><label>1</label></td>
                            <td><input type="radio" name="rapidez_crecimiento" value="2"><label>2</label></td>
                            <td><input type="radio" name="rapidez_crecimiento" value="3"><label>3</label></td>
                            <td><input type="radio" name="rapidez_crecimiento" value="4"><label>4</label></td>
                            <td><input type="radio" name="rapidez_crecimiento" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>6. Rentabilidad</td>
                            <td><input type="radio" name="rentabilidad" value="1" required><label>1</label></td>
                            <td><input type="radio" name="rentabilidad" value="2"><label>2</label></td>
                            <td><input type="radio" name="rentabilidad" value="3"><label>3</label></td>
                            <td><input type="radio" name="rentabilidad" value="4"><label>4</label></td>
                            <td><input type="radio" name="rentabilidad" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>7. Satisfacción de los empleados</td>
                            <td><input type="radio" name="satisfaccion_empleados" value="1" required><label>1</label></td>
                            <td><input type="radio" name="satisfaccion_empleados" value="2"><label>2</label></td>
                            <td><input type="radio" name="satisfaccion_empleados" value="3"><label>3</label></td>
                            <td><input type="radio" name="satisfaccion_empleados" value="4"><label>4</label></td>
                            <td><input type="radio" name="satisfaccion_empleados" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>8. Grado de absentismo laboral</td>
                            <td><input type="radio" name="absentismo_laboral" value="1" required><label>1</label></td>
                            <td><input type="radio" name="absentismo_laboral" value="2"><label>2</label></td>
                            <td><input type="radio" name="absentismo_laboral" value="3"><label>3</label></td>
                            <td><input type="radio" name="absentismo_laboral" value="4"><label>4</label></td>
                            <td><input type="radio" name="absentismo_laboral" value="5"><label>5</label></td>
                        </tr>

                    </table>
                </div>

                <div class="question" id="pregunta11">
                    <h3>11. Indique si su empresa ha realizado, en los dos últimos años, las siguientes innovaciones y, en caso afirmativo, señale el grado de importancia de cada una de ellas:</h3>
                    <table>
                        <tr>
                            <th>Innovación</th>
                            <th colspan="1">No</th>
                            <th></th>
                            <th colspan="2">Poco Importante</th>
                            <th colspan="2">Muy Importante</th>
                        </tr>
                        <tr>
                            <td>1. Cambios o mejoras en productos/servicios existentes</td>
                            <td><input type="radio" name="innovacion_productos" value="0" required><label>0</label></td>
                            <td><input type="radio" name="innovacion_productos" value="1"><label>1</label></td>
                            <td><input type="radio" name="innovacion_productos" value="2"><label>2</label></td>
                            <td><input type="radio" name="innovacion_productos" value="3"><label>3</label></td>
                            <td><input type="radio" name="innovacion_productos" value="4"><label>4</label></td>
                            <td><input type="radio" name="innovacion_productos" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>2. Lanzamiento al mercado de nuevos productos/servicios</td>
                            <td><input type="radio" name="innovacion_lanzamiento" value="0" required><label>0</label></td>
                            <td><input type="radio" name="innovacion_lanzamiento" value="1"><label>1</label></td>
                            <td><input type="radio" name="innovacion_lanzamiento" value="2"><label>2</label></td>
                            <td><input type="radio" name="innovacion_lanzamiento" value="3"><label>3</label></td>
                            <td><input type="radio" name="innovacion_lanzamiento" value="4"><label>4</label></td>
                            <td><input type="radio" name="innovacion_lanzamiento" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>3. Acceso a nuevos mercados</td>
                            <td><input type="radio" name="nuevos_mercados" value="0" required><label>0</label></td>
                            <td><input type="radio" name="nuevos_mercados" value="1"><label>1</label></td>
                            <td><input type="radio" name="nuevos_mercados" value="2"><label>2</label></td>
                            <td><input type="radio" name="nuevos_mercados" value="3"><label>3</label></td>
                            <td><input type="radio" name="nuevos_mercados" value="4"><label>4</label></td>
                            <td><input type="radio" name="nuevos_mercados" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>4. Establecimiento nuevas alianzas o colaboraciones</td>
                            <td><input type="radio" name="alianzas" value="0" required><label>0</label></td>
                            <td><input type="radio" name="alianzas" value="1"><label>1</label></td>
                            <td><input type="radio" name="alianzas" value="2"><label>2</label></td>
                            <td><input type="radio" name="alianzas" value="3"><label>3</label></td>
                            <td><input type="radio" name="alianzas" value="4"><label>4</label></td>
                            <td><input type="radio" name="alianzas" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>5. Cambios o mejoras en los procesos</td>
                            <td><input type="radio" name="mejoras_procesos" value="0" required><label>0</label></td>
                            <td><input type="radio" name="mejoras_procesos" value="1"><label>1</label></td>
                            <td><input type="radio" name="mejoras_procesos" value="2"><label>2</label></td>
                            <td><input type="radio" name="mejoras_procesos" value="3"><label>3</label></td>
                            <td><input type="radio" name="mejoras_procesos" value="4"><label>4</label></td>
                            <td><input type="radio" name="mejoras_procesos" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>6. Adquisición de nuevos bienes de equipo o instalaciones</td>
                            <td><input type="radio" name="nuevos_bienes" value="0" required><label>0</label></td>
                            <td><input type="radio" name="nuevos_bienes" value="1"><label>1</label></td>
                            <td><input type="radio" name="nuevos_bienes" value="2"><label>2</label></td>
                            <td><input type="radio" name="nuevos_bienes" value="3"><label>3</label></td>
                            <td><input type="radio" name="nuevos_bienes" value="4"><label>4</label></td>
                            <td><input type="radio" name="nuevos_bienes" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>7. Nuevos cambios o mejoras en organización y/o gestión</td>
                            <td><input type="radio" name="mejoras_gestion" value="0" required><label>0</label></td>
                            <td><input type="radio" name="mejoras_gestion" value="1"><label>1</label></td>
                            <td><input type="radio" name="mejoras_gestion" value="2"><label>2</label></td>
                            <td><input type="radio" name="mejoras_gestion" value="3"><label>3</label></td>
                            <td><input type="radio" name="mejoras_gestion" value="4"><label>4</label></td>
                            <td><input type="radio" name="mejoras_gestion" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>8. Nuevos cambios o mejoras en compras y/o aprovisionamientos</td>
                            <td><input type="radio" name="mejoras_compras" value="0" required><label>0</label></td>
                            <td><input type="radio" name="mejoras_compras" value="1"><label>1</label></td>
                            <td><input type="radio" name="mejoras_compras" value="2"><label>2</label></td>
                            <td><input type="radio" name="mejoras_compras" value="3"><label>3</label></td>
                            <td><input type="radio" name="mejoras_compras" value="4"><label>4</label></td>
                            <td><input type="radio" name="mejoras_compras" value="5"><label>5</label></td>
                        </tr>
                        <tr>
                            <td>9. Nuevos cambios o mejoras en comercial y/o ventas</td>
                            <td><input type="radio" name="mejoras_comerciales" value="0" required><label>0</label></td>
                            <td><input type="radio" name="mejoras_comerciales" value="1"><label>1</label></td>
                            <td><input type="radio" name="mejoras_comerciales" value="2"><label>2</label></td>
                            <td><input type="radio" name="mejoras_comerciales" value="3"><label>3</label></td>
                            <td><input type="radio" name="mejoras_comerciales" value="4"><label>4</label></td>
                            <td><input type="radio" name="mejoras_comerciales" value="5"><label>5</label></td>
                        </tr>
                    </table>
                </div>
                <div class="submit">
                    <input type="submit" value="Enviar y finalizar" name="submit" />
                </div>
        </form>
</body>

</html>
