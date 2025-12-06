<?php
/**
 * Controller for the entity USER
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;

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
