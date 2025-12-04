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

use MiW\Results\Entity\User;
use MiW\Results\Utility\DoctrineConnector;
use MiW\Results\Utility\Utils;

// Mostrar ayuda
if (in_array('--help', $argv, true)) {
    echo <<<HELP
USE:
  php .\src\scripts\user\update_user.php [ID] [--json]

DESCRIPTION:
  Updates the data of a user whose ID is passed as a parameter.
  Use Doctrine to query the database and update the data of this user.

PARAMETERS:
  id  Numeric identifier of the user to be updated. Mandatory. If not provided, the script will display an error.
  username      User name (string)
  email         Email address (string)
  password      Plain text password (string)
  isEnabled     Indicates if user is active or not (true|false|1|0)
  isAdmin       Indicates if user is an administrative user or not (true|false|1|0)

OPTIONS:
  --json        Display the output in JSON format
  --help        Display this message and exit

EXAMPLES:
  php .\src\scripts\user\update_user.php 5 bob bob@test.com hello 1 0
  php .\src\scripts\user\update_user.php 5 bob bob@test.com hello 1 0 --json

HELP;
    exit(0);
}

// Carga las variables de entorno y el entity manager
Utils::loadEnv(dirname(__DIR__, 3));
$entityManager = DoctrineConnector::getEntityManager();

// Definición de variables
$id = 0; $username = ""; $email = ""; $password = ""; $isEnabled = false; $isAdmin = false; // Valores del usuario a actualizar
$formatoJson = false;

// Control formato json o texto
if (in_array('--json',$argv,true)) {
    $formatoJson = true;
}

// Control de argumentos (8 para json / 7 para texto)
$argsValidos = ($argc === ($formatoJson ? 8 : 7));
if (!$argsValidos) {
    echo "Enter the parameters correctly, use --help for support" . PHP_EOL;
    exit(0);
}

// Leer y validar los parámetros de entrada
$paramSchema = [
    1 => ['name' => 'id', 'type' => 'int'],
    2 => ['name' => 'username', 'type' => 'string'],
    3 => ['name' => 'email',    'type' => 'email'],
    4 => ['name' => 'password', 'type' => 'string'],
    5 => ['name' => 'isEnabled','type' => 'bool'],
    6 => ['name' => 'isAdmin',  'type' => 'bool'],
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

            case 'string':
                $$name = (string)$value;
                break;

            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    throw new InvalidArgumentException("The email address '$value' is not valid");
                }
                $$name = $value;
                break;

            case 'bool':
                if (!in_array(strtolower($value), ['true', 'false', '1', '0'], true)) {
                    throw new InvalidArgumentException("The parameter '$name' must be a boolean (true/false/1/0)");
                }
                $$name = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;

            default:
                throw new UnexpectedValueException("Type '$type' no supported");
        }
    }
} catch (Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// Obtener usuario
$userRepository = $entityManager->getRepository(User::class);
$user = $userRepository->find($id);

if (!$user) {
    if ($formatoJson) {
        echo json_encode(
            ["error" => "User with ID #{$id} not found"], JSON_PRETTY_PRINT );
    } else {
        echo "User with ID #$id not found" . PHP_EOL;
    }
} else {
    try {
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setIsEnabled($isEnabled);
        $user->setIsAdmin($isAdmin);
        $entityManager->persist($user);
        $entityManager->flush();
        if ($formatoJson) {
            echo json_encode(
                ["OK" => "Updated User with ID #{$user->getId()}"], JSON_PRETTY_PRINT );
        } else {
            echo 'Updated User with ID #' . $user->getId() . PHP_EOL;
        }
    } catch(Throwable $e) {
        echo "Error: " . $e->getMessage() . PHP_EOL;
    }
}
