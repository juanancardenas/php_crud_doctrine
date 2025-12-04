<?php
/**
 * Realiza el borrado de un usuario que se le pasa por parámetro
 * src/scripts/delete_user.php
 *
 * @category Scripts
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://www.etsisi.upm.es/ ETS de Ingeniería de Sistemas Informáticos
 */

require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Mostrar ayuda
if (in_array('--help', $argv, true)) {
    echo <<<HELP
USE:
  php .\src\scripts\user\delete_user.php id [--json]

DESCRIPTION:
 Delete the user included in the request from the database using Doctrine

PARAMETERS:
  id            Id of the user (int)

OPTIONS:
  --json        Display the output in JSON format
  --help        Display this message and exit

EXAMPLES:
  php .\src\scripts\user\delete_user 124

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

// Recorrer los parámetros buscando el id de usuario y formato json
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
    echo "Please enter the user ID" . PHP_EOL;
    exit(0);
}

// Obtener usuario
$userRepository = $entityManager->getRepository(User::class);
$user = $userRepository->find($id);

if (!$user) {
    echo "User not found" . PHP_EOL;
    exit(0);
} else {
    try {
        // Borrar usuario
        $id_user = $user->getId();
        $entityManager->remove($user);
        $entityManager->flush();
        if ($formatoJson) {
            echo json_encode(
                ["OK" => "Deleted User with ID #{$id_user}"], JSON_PRETTY_PRINT );
        } else {
            echo 'Deleted User with ID #' . $id_user . PHP_EOL;
        }
    } catch (Throwable $exception) {
        echo $exception->getMessage() . PHP_EOL;
    }
}
