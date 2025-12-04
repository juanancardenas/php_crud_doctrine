<?php
/**
 * Realiza la lectura de todos los usuarios de la base de datos
 * src/scripts/list_users.php
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
  php .\src\scripts\user\list_users.php [--json]

DESCRIPTION:
  Displays all users registered in the database using Doctrine.

OPTIONS:
  --json     Display output in JSON format.
  --help     Display this help message.

OUTPUT:
  - In text mode: displays a table with ID, Username, Email, Enabled, and isAdmin.
  - In JSON mode: returns a JSON array with all users or an error if they do not exist.

EXAMPLES:
  php .\src\scripts\user\list_users.php
  php .\src\scripts\user\list_users.php --json

HELP;
    exit(0);
}

// Carga las variables de entorno y el entity manager
Utils::loadEnv(dirname(__DIR__, 3));
$entityManager = DoctrineConnector::getEntityManager();

// Obtener todos los usuarios
$userRepository = $entityManager->getRepository(User::class);
$users = $userRepository->findAll();

// Salida en JSON
if (in_array('--json', $argv, true)) {
    if (!$users) {
        echo json_encode(
            ['error' => "There are no users registered in the database"],
            JSON_PRETTY_PRINT
        );
    } else {
        echo json_encode($users, JSON_PRETTY_PRINT);
    }

} else {
    // Salida en texto
    if (!$users) {
        echo "There are no users registered in the database" . PHP_EOL;
        exit(0);
    }

    echo PHP_EOL . sprintf(
            '  %2s: %20s %30s %7s %7s' . PHP_EOL,
            'Id', 'Username', 'Email', 'Enabled', 'Admin');

    foreach ($users as $user) {
        echo sprintf(
            '- %2d: %20s %30s %7s %7s',
            $user->getId(),
                $user->getUsername(),
                $user->getEmail(),
                ($user->isEnabled()) ? 'true' : 'false',
                ($user->isAdmin()) ? 'true' : 'false')
            . PHP_EOL;
    }
    // Se indica el número total de usuarios
    $total = count($users);
    echo PHP_EOL . "Total: $total users." . PHP_EOL . PHP_EOL;
}
