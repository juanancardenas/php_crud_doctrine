<?php
/**
 * Realiza la lectura de un usuario que se le pasa por parámetro
 * src/scripts/user/list_user.php
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
  php .\src\scripts\user\list_user.php [ID] [--json]

DESCRIPTION:
  Displays the information of a user whose ID is passed as a parameter.
  Use Doctrine to query the database.

PARAMETERS:
  ID
      Numeric identifier of the user you wish to query.
      This is mandatory. If not provided, the script will display an error.

OPTIONS:
  --json        Display the output in JSON format
  --help        Display this message and exit

EXAMPLES:
  php .\src\scripts\user\list_user.php 5
  php .\src\scripts\user\list_user.php 12 --json

OUTPUT:
    • If the user exists:
        - In text: ID, username, email, and enabled status.
        - In JSON: JSON object of the user.

    • If the user does NOT exist:
        - In text: message “No user with id = X exists.”
        - In JSON: {“error”: “No user with id = X exists”}

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

// Salida en JSON
if ($formatoJson) {
    if (!$user) {
        echo json_encode(
            ['error' => "The user with id #{$id} does not exist"],
            JSON_PRETTY_PRINT
        );
    } else {
        echo json_encode($user, JSON_PRETTY_PRINT);
    }
}
else {
    // Salida en texto
    if (!$user) {
        echo "The user with id #$id does not exist" . PHP_EOL;
        exit(0);
    }

    echo PHP_EOL . sprintf(
            '  %2s: %20s %30s %7s %7s' . PHP_EOL,
            'Id', 'Username', 'Email', 'Enabled', 'Admin'
        );

    echo sprintf(
            '- %2d: %20s %30s %7s %7s',
            $user->getId(),
            $user->getUsername(),
            $user->getEmail(),
            $user->isEnabled() ? 'true' : 'false',
            $user->isAdmin() ? 'true' : 'false'
        ) . PHP_EOL;
}
