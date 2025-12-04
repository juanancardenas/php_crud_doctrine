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
  php .\src\scripts\result\list_results.php id [--json]

DESCRIPTION:
  Displays a specific result from the database using Doctrine.

PARAMETERS:
  result_id    ID of the result to display (integer)

OPTIONS:
  --json       Display the output in JSON format
  --help       Display this help message

OUTPUT:
  - In text mode: prints a table with Id, Result, Username, and Time
  - In JSON mode: returns a JSON object with the result or an error if it does not exist

EXAMPLES:
  php .\src\scripts\results\list_result.php 12
  php .\src\scripts\results\list_result.php 4 --json

HELP;
    exit(0);
}

// Carga las variables de entorno y el entity manager
Utils::loadEnv(dirname(__DIR__, 3));
$entityManager = DoctrineConnector::getEntityManager();

// Validación de parámetros
if ($argc < 2) {
    echo "Enter the parameters correctly, use --help for support" . PHP_EOL;
    exit(0);
}

// Definición de variables
$id=null;
$formatoJson=false;
$encontrado=false;

// Recorrer los parámetros buscando el id de resultado y formato json
foreach ($argv as $index => $value) {
    if ($index == 0) { // ruta del script
        continue;
    }

    if ($value === '--json') {
        $formatoJson = true;
    } elseif (ctype_digit($value)) {
        $id = (int) $value;
        $encontrado=true;
    }
}

if (!$encontrado) {
    echo "Please enter the result ID" . PHP_EOL;
    exit(0);
}

// Obtener resultado
$resultRepository = $entityManager->getRepository(Result::class);
$result = $resultRepository->find($id);

// Salida en JSON
if ($formatoJson) {
    if (!$result) {
        echo json_encode(
            ['error' => "The result with id #{$id} does not exist"],
            JSON_PRETTY_PRINT
        );
    } else {
        echo json_encode($result->jsonSerialize(), JSON_PRETTY_PRINT);
    }
}
else {
    // Salida en texto
    if (!$result) {
        echo "The result with id #$id does not exist" . PHP_EOL;
        exit(0);
    }

    echo PHP_EOL
        . sprintf('%3s - %6s - %22s - %s', 'Id', 'Res', 'username', 'time')
        . PHP_EOL;

    echo $result->__toString() . PHP_EOL;
}
