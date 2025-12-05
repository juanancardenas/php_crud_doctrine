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

function funcionListadoUsuarios(): void
{
    $entityManager = DoctrineConnector::getEntityManager();

    $userRepository = $entityManager->getRepository(User::class);
    $users = $userRepository->findAll();
    var_dump($users);
}

function funcionUsuario(string $name): void
{
    echo $name;
}

function userList(): void
{
    $em = DoctrineConnector::getEntityManager();
    $users = $em->getRepository(User::class)->findAll();

    require dirname(__DIR__, 1) . '/views/user/list.php';
}

function user(int $id): void
{
    $em = DoctrineConnector::getEntityManager();
    $user = $em->getRepository(User::class)->find($id);

    require dirname(__DIR__, 1) . '/views/user/user.php';
}



function resultList(): void
{
    $em = DoctrineConnector::getEntityManager();
    $results = $em->getRepository(Result::class)->findAll();

    require dirname(__DIR__, 1) . '/views/result/list.php';
}

function result(int $id): void
{
    $em = DoctrineConnector::getEntityManager();
    $result = $em->getRepository(Result::class)->find($id);

    require dirname(__DIR__, 1) . '/views/result/result.php';
}
