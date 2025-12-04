<?php
/**
 * Realiza el borrado de un resultado en la base de datos
 * src/scripts/result/delete_result.php
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
  php .\src\scripts\results\delete_result.php [--json]

DESCRIPTION:
 Delete the result included in the request from the database using Doctrine

PARAMETERS:
  id            Id of the result (int)

OPTIONS:
  --json        Display the output in JSON format
  --help        Display this message and exit

EXAMPLES:
  php .\src\scripts\result\delete_result 4 --json
  php .\src\scripts\result\delete_result 5

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

// Obtener resutado
$resultRepository = $entityManager->getRepository(Result::class);
$result = $resultRepository->find($id);

if (!$result) {
    echo "Result not found" . PHP_EOL;
    exit(0);
} else {
    try {
        // Borrar resultado
        $id_result = $result->getId();
        $entityManager->remove($result);
        $entityManager->flush();

        if ($formatoJson) {
            echo json_encode(
                ["OK" => "Deleted Result with ID #{$id_result}"], JSON_PRETTY_PRINT );
        } else {
            echo 'Deleted Result with ID #' . $id_result . PHP_EOL;
        }
    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
    }
}
