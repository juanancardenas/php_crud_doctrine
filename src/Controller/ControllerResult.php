<?php
/**
 * Controller for the entity RESULT
 *
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

use MiW\Results\Entity\User;
use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;

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
