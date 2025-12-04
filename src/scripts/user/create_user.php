<?php
/**
 * Realiza la creación de un nuevo usuario
 * src/scripts/user/create_user.php
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
  php .\src\scripts\user\create_user.php username email password isEnabled isAdmin [--json]

DESCRIPTION:
  Create a new user in the database using Doctrine

PARAMETERS:
  username      User name (string)
  email         Email address (string)
  password      Plain text password (string)
  isEnabled     Indicates if user is active or not (true|false|1|0)
  isAdmin       Indicates if user is an administrative user or not (true|false|1|0)

OPTIONS:
  --json        Display the output in JSON format
  --help        Display this message and exit

EXAMPLES:
  php .\src\scripts\user\create_user.php alice alice@example.com secret true false
  php .\src\scripts\user\create_user.php bob bob@test.com hello 1 0 --json

HELP;
    exit(0);
}

// Carga las variables de entorno y el entity manager
Utils::loadEnv(dirname(__DIR__, 3));
$entityManager = DoctrineConnector::getEntityManager();

// Definición de variables
$username = ""; $email = ""; $password = ""; $isEnabled = false; $isAdmin = false; // Valores del usuario a insertar
$formatoJson = false;

// Control formato json o texto
if (in_array('--json',$argv,true)) {
    $formatoJson = true;
}

// Control de argumentos (7 para json / 6 para texto)
$argsValidos = ($argc === ($formatoJson ? 7 : 6));
if (!$argsValidos) {
    echo "Enter the parameters correctly, use --help for support" . PHP_EOL;
    exit(0);
}

// Leer y validar los parámetros de entrada
$paramSchema = [
    1 => ['name' => 'username', 'type' => 'string'],
    2 => ['name' => 'email',    'type' => 'email'],
    3 => ['name' => 'password', 'type' => 'string'],
    4 => ['name' => 'isEnabled','type' => 'bool'],
    5 => ['name' => 'isAdmin',  'type' => 'bool'],
];

try {
    foreach ($argv as $key => $value) {
        if (!isset($paramSchema[$key])) continue;

        $type = $paramSchema[$key]['type'];
        $name = $paramSchema[$key]['name'];

        switch ($type) {
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

// Persistir el nuevo usuario
$user = new User();
$user->setUsername($username);
$user->setEmail($email);
$user->setPassword($password);
$user->setIsEnabled($isEnabled);
$user->setIsAdmin($isAdmin);

try {
    $entityManager->persist($user);
    $entityManager->flush();
    if ($formatoJson) {
        echo json_encode(
            ["OK" => "Created User with ID #{$user->getId()}"], JSON_PRETTY_PRINT );
    } else {
        echo 'Created User with ID #' . $user->getId() . PHP_EOL;
    }
} catch (Throwable $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
