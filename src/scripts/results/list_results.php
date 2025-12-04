<?php
/**
 * Realiza la lectura de todos los resultados de la base de datos
 * src/scripts/result/list_results.php
 *
 * @category Scripts
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Mostrar ayuda
if (in_array('--help', $argv, true)) {
    echo <<<HELP
USE:
  php .\src\scripts\results\list_users.php [--json]

DESCRIPTION:
  Displays all results registered in the database using Doctrine.

OPTIONS:
  --json     Display output in JSON format.
  --help     Display this help message.

OUTPUT:
  - In text mode: displays a table with ID, Result, Username, and Time.
  - In JSON mode: returns a JSON array with all results or an error if they do not exist.

EXAMPLES:
  php .\src\scripts\results\list_results.php
  php .\src\scripts\results\list_results.php --json

HELP;
    exit(0);
}

// Carga las variables de entorno y el entity manager
Utils::loadEnv(dirname(__DIR__, 3));
$entityManager = DoctrineConnector::getEntityManager();

// Lee todos los resultados
$resultsRepository = $entityManager->getRepository(Result::class);
$results = $resultsRepository->findAll();

if (in_array('--json', $argv, true)) {
    if (!$results) {
        echo json_encode(
            ['info' => "There are no results registered in the database"],
            JSON_PRETTY_PRINT
        );
    } else {
        echo json_encode($results, JSON_PRETTY_PRINT);
    }

} else {
    // Salida en texto
    if (!$results) {
        echo "There are no results registered in the database" . PHP_EOL;
        exit(0);
    }

    echo PHP_EOL
        . sprintf('%3s - %6s - %22s - %s', 'Id', 'Res', 'username', 'time')
        . PHP_EOL;

    foreach ($results as $result) {
        echo  $result . PHP_EOL;
    }

    // Se indica el número total de resultados
    $total = count($results);
    echo PHP_EOL . "Total: $total results." . PHP_EOL . PHP_EOL;
}
