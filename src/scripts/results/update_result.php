<?php
/**
 * Realiza la actualización de un usuario que se le pasa por parámetro
 * src/scripts/user/update_user.php
 *
 * @category Scripts
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use MiW\Results\Entity\Result;
use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Mostrar ayuda
if (in_array('--help', $argv, true)) {
    echo <<<HELP
USE:
  php .\src\scripts\results\update_result.php [ID] [--json]

DESCRIPTION:
  Updates the data of a result whose ID is passed as a parameter.
  Use Doctrine to query the database and update the data of this result.

PARAMETERS:
  id            Numeric identifier of the result to be updated. Mandatory. If not provided, the script will display an error.
  result        Result value (int)
  time          Time of the result
  userId        Userid who scores the result (int)

OPTIONS:
  --json        Display the output in JSON format
  --help        Display this message and exit

EXAMPLES:
  php .\src\scripts\results\update_result.php 5 224 "2025-12-01 19:00:00" 2
  php .\src\scripts\results\update_result.php 6 373 "2025-12-01 09:14:42" 3 --json

HELP;
    exit(0);
}

// Carga las variables de entorno y el entity manager
Utils::loadEnv(dirname(__DIR__, 3));
$entityManager = DoctrineConnector::getEntityManager();

// Definición de variables
$id = 0; $result = 0; $userId = 0; // Valores del usuario a actualizar
/** @var DateTime|null $time */
$time = null;
$formatoJson = false;

// Control formato json o texto
if (in_array('--json',$argv,true)) {
    $formatoJson = true;
}

// Control de argumentos (6 para json / 5 para texto)
$argsValidos = ($argc === ($formatoJson ? 6 : 5));
if (!$argsValidos) {
    echo "Enter the parameters correctly, use --help for support" . PHP_EOL;
    exit(0);
}

// Leer y validar los parámetros de entrada
$paramSchema = [
    1 => ['name' => 'id', 'type' => 'int'],
    2 => ['name' => 'result', 'type' => 'int'],
    3 => ['name' => 'time',   'type' => 'datetime'],
    4 => ['name' => 'userId', 'type' => 'int']
];

try {
    foreach ($argv as $key => $value) {
        if (!isset($paramSchema[$key])) continue;

        $type = $paramSchema[$key]['type'];
        $name = $paramSchema[$key]['name'];

        switch ($type) {
            case 'int':
                if (!ctype_digit($value)) {
                    throw new InvalidArgumentException("Parameter '{$name}' must be an integer.");
                }
                $$name = (int) $value;
                break;

            case 'datetime':
                $dt = DateTime::createFromFormat('Y-m-d H:i:s', $value);
                if ($dt === false) {
                    throw new InvalidArgumentException(
                        "Parameter '{$name}' must be a valid datetime in format 'Y-m-d H:i:s'"
                    );
                }
                $$name = $dt;
                break;

            default:
                throw new UnexpectedValueException("Unsupported type '{$type}'");
        }
    }
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Obtener el objeto user para la clave foránea
$user = $entityManager->getRepository(User::class)->find($userId);
if (!$user) {
    echo "Error: user ID $userId does not exist." . PHP_EOL;
    exit(1);
}

// Obtener resultado a ser modificado
$resultRepository = $entityManager->getRepository(Result::class);
$objResult = $resultRepository->find($id);
if (!$objResult) {
    if ($formatoJson) {
        echo json_encode(
            ["error" => "Result with ID #{$id} not found"], JSON_PRETTY_PRINT );
    } else {
        echo "Result with ID #$id not found" . PHP_EOL;
    }
} else {
    try {
        $objResult->setResult($result);
        $objResult->setTime($time);
        $objResult->setUserId($user);

        // Persistir el resultado con los cambios aplicados
        $entityManager->persist($objResult);
        $entityManager->flush();
        if ($formatoJson) {
            echo json_encode(
                ["OK" => "Updated Result with ID #{$objResult->getId()}"], JSON_PRETTY_PRINT);
        } else {
            echo 'Updated Result with ID #' . $objResult->getId() . PHP_EOL;
        }
    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
    }
}
