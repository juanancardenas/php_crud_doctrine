<?php
/**
 * Realiza la creación de un nuevo resultado
 * src/scripts/results/create_result.php
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
  php .\src\scripts\results\create_result.php result time userid [--json]

DESCRIPTION:
  Create a new result in the database using Doctrine

PARAMETERS:
  result        Result value (int)
  time          Time of the result
  userid        Userid who scores the result (int)

OPTIONS:
  --json        Display the output in JSON format
  --help        Display this message and exit

EXAMPLES:
  php .\src\scripts\results\create_result.php 370 "2025-12-04 15:30:00" 11
  php .\src\scripts\results\create_result.php 224 "2025-12-04 15:30:00" 10 --json

HELP;
    exit(0);
}

// Carga las variables de entorno y el entity manager
Utils::loadEnv(dirname(__DIR__, 3));
$entityManager = DoctrineConnector::getEntityManager();

// Definición de variables
$result = ""; $userId = ""; // Valores del usuario a insertar
/** @var DateTime|null $time */
$time = null;
$formatoJson = false;

// Control formato json o texto
if (in_array('--json',$argv,true)) {
    $formatoJson = true;
}

// Control de argumentos (4 para json / 3 para texto)
$argsValidos = ($argc === ($formatoJson ? 5 : 4));
if (!$argsValidos) {
    echo "Enter the parameters correctly, use --help for support" . PHP_EOL;
    exit(0);
}

// Leer y validar los parámetros de entrada
$paramSchema = [
    1 => ['name' => 'result', 'type' => 'int'],
    2 => ['name' => 'time',   'type' => 'datetime'],
    3 => ['name' => 'userId', 'type' => 'int']
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

// Persistir el nuevo resultado
$objResult = new Result($result, $user, $time);
try {
    $entityManager->persist($objResult);
    $entityManager->flush();
    if ($formatoJson) {
        echo json_encode(
            ["OK" => "Created Result with ID #{$objResult->getId()}"], JSON_PRETTY_PRINT );
    } else {
        echo 'Created Result with ID #' . $objResult->getId() . PHP_EOL;
    }
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
