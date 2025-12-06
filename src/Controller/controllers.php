<?php
/**
 * ResultsDoctrine - controllers.php
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;

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

/* ********************   USERS   ******************** */

/*
 * Path /users/create
 * Methods GET y POST
 * Para GET navega a la página de introducción de datos
 * Para POST persiste el nuevo usuario y vuelve a listado de usuarios
 */
function userCreate(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = new User(
            $_POST['username'],
            $_POST['email'],
            $_POST['password'],
            isset($_POST['enabled']),
            isset($_POST['admin'])
        );

        // Create the user
        try {
            $em = DoctrineConnector::getEntityManager();
            $em->persist($user);
            $em->flush();
        } catch (Throwable $exception) {
            echo $exception->getMessage() . PHP_EOL;
            exit;
        }

        header(HOMEPATH_USER);
        exit;
    }

    require dirname(__DIR__, 1) . '/views/user/create_user.php';
}

/*
 * Path /users
 * Methods GET
 * Muestra un listado de usuarios
 */
function userList(): void
{
    try {
        // Real all user
        $em = DoctrineConnector::getEntityManager();
        $users = $em->getRepository(User::class)->findAll();

    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
        exit;
    }

    require dirname(__DIR__, 1) . '/views/user/list_user.php';
}

/*
 * Path /users/{id}
 * Methods GET
 * Muestra el detalle de un usuario
 */
function user(int $id): void
{
    try {
        // Read the user
        $em = DoctrineConnector::getEntityManager();
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            // User not found
            echo USER_NOT_FOUND;
            exit;
        }
    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
        exit;
    }

    require dirname(__DIR__, 1) . '/views/user/user.php';
}

/*
 * Path /users/{id}/edit
 * Methods GET
 * Edita la información de un usuario
 */
function userUpdate(int $id): void
{
    try {
        // Read the user
        $em = DoctrineConnector::getEntityManager();
        $user = $em->find(User::class, $id);
        if (!$user) {
            // User not found
            echo USER_NOT_FOUND;
            return;
        }
    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setIsEnabled(isset($_POST['enabled']));
        $user->setIsAdmin(isset($_POST['admin']));

        if (!empty($_POST['password'])) {
            $user->setPassword($_POST['password']);
        }

        // Update the user
        try {
            $em->persist($user);
            $em->flush();

        } catch (Throwable $exception) {
            echo $exception->getMessage() . PHP_EOL;
            return;
        }

        header(HOMEPATH_USER);
        exit;
    }

    require dirname(__DIR__, 1) . '/views/user/update_user.php';
}

/*
 * Path /users/{id}/delete
 * Methods GET
 * Borra un usuario
 */
function userDelete(int $id): void
{
    try {
        // Read the user
        $em = DoctrineConnector::getEntityManager();
        $user = $em->find(User::class, $id);
        if (!$user) {
            echo USER_NOT_FOUND;
            exit;
        }

        // Delete the user
        $em->remove($user);
        $em->flush();

    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
        exit;
    }

    header(HOMEPATH_USER);
}

/* *******************   RESULTS   ******************* */

/*
 * Path /results/create
 * Methods GET y POST
 * GET navega a la página de introducción de datos
 * POST persiste el nuevo resultado y vuelve a listado de resultados
 */
function resultCreate(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $userId = (int) $_POST['user'];
            // Read the user
            $em = DoctrineConnector::getEntityManager();
            $user = $em->getRepository(User::class)->find($userId);
            if (!$user) {
                // User not found
                echo RESULT_NOT_FOUND;
                exit;
            }

            $time = new \DateTime($_POST['time']);

            $result = new Result(
                (int) $_POST['result'],
                $user,
                $time
            );

            // Crete the result
            $em->persist($result);
            $em->flush();

        } catch (Throwable $exception) {
           echo $exception->getMessage() . PHP_EOL;
           exit;
        }

        header(HOMEPATH_RESULTS);
        exit;
    }

    // Necesitamos lista de usuarios para poder seleccionar en la página
    $em = DoctrineConnector::getEntityManager();
    $users = $em->getRepository(User::class)->findAll();

    require dirname(__DIR__, 1) . '/views/result/create_result.php';
}

/*
 * Path /results
 * Methods GET
 * Muestra un listado de resultados
 */
function resultList(): void
{
    try {
        // Read all results
        $em = DoctrineConnector::getEntityManager();
        $results = $em->getRepository(Result::class)->findAll();

    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
        exit;
    }

    require dirname(__DIR__, 1) . '/views/result/list.php';
}

/*
 * Path /results/{id}
 * Methods GET
 * Muestra los detalles de un resultado
 */
function result(int $id): void
{
    try {
        // Read the result
        $em = DoctrineConnector::getEntityManager();
        $result = $em->getRepository(Result::class)->find($id);
        if(!$result) {
            // Result not found
            echo RESULT_NOT_FOUND;
            exit;
        }

    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
        exit;
    }

    require dirname(__DIR__, 1) . '/views/result/result.php';
}

function resultUpdate(int $id): void
{
    try {
        // Read the result
        $em = DoctrineConnector::getEntityManager();
        $result = $em->find(Result::class, $id);
        if (!$result) {
            // Result not found
            echo RESULT_NOT_FOUND;
            exit;
        }

    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $userId = (int) $_POST['user'];
            // Read the user
            $user = $em->getRepository(User::class)->find($userId);
            if (!$user) {
                // User not found
                echo USER_NOT_FOUND;
                exit;
            }

            $time = new \DateTime($_POST['time']);

            $result->setResult((int) $_POST['result']);
            $result->setUserId($user);
            $result->setTime($time);

            // Update the result
            $em->persist($result);
            $em->flush();

        } catch (Throwable $exception) {
            echo $exception->getMessage() . PHP_EOL;
            exit;
        }

        header(HOMEPATH_RESULTS);
        exit;
    }

    // Necesitamos lista de usuarios para poder seleccionar en la página
    $users = $em->getRepository(User::class)->findAll();

    require dirname(__DIR__, 1) . '/views/result/update_result.php';
}

/*
 * Path /users/{id}/delete
 * Methods GET
 * Borra un resultado
 */
function resultDelete(int $id): void
{
    try {
        // Read the result
        $em = DoctrineConnector::getEntityManager();
        $result = $em->find(Result::class, $id);
        if (!$result) {
            // Result not found
            echo RESULT_NOT_FOUND;
            exit;
        }

        // Delete the result
        $em->remove($result);
        $em->flush();

    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
        exit;
    }

    header(HOMEPATH_RESULTS);
}
