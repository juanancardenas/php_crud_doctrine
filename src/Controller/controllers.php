<?php
/**
 * ResultsDoctrine - controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

const HOMEPATH_USER = "Location: /users";
const HOMEPATH_RESULTS = "Location: /results";

const USER_NOT_FOUND = "El usuario no existe";
const RESULT_NOT_FOUND = "El resultado no existe";

/*
 * Path (/)
 * Method GET
 * Homepage que muestra dos enlaces, uno para acceder al CRUD de user
 * y otro para acceder al CRUD de los resultados
 */
function funcionHomePage(): void
{
    global $routes;

    $rutaListado = $routes->get('user_list')->getPath();
    echo <<< MARCA_FIN
    <ul>
        <li><a href="$rutaListado">Acceder a la Gestión de Usuarios</a></li>
    </ul>
    MARCA_FIN;

    $rutaListado = $routes->get('result_list')->getPath();
    echo <<< MARCA_FIN
    <ul>
        <li><a href="$rutaListado">Acceder a la Gestión de Resultados</a></li>
    </ul>
    MARCA_FIN;
}
