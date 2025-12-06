<?php
/**
 * views/user/create_user.php
 *
 * Formulario de creación de usuario
 *
 * @var \MiW\Results\Entity\User[] $users
 */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Create User</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <h1>Create User</h1>
    <div class="top">
        <a href="/users">⬅️ Back to users</a>
    </div>
    <form method="POST" action="/users/create">
        <table>
            <tr>
                <th>Username</th>
                <td><input type="text" name="username" required autofocus></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="email" name="email" required></td>
            </tr>
            <tr>
                <th>Password</th>
                <td><input type="password" name="password" required></td>
            </tr>
            <tr>
                <th>Enabled</th>
                <td><input type="checkbox" name="enabled"></td>
            </tr>
            <tr>
                <th>Admin</th>
                <td><input type="checkbox" name="admin"></td>
            </tr>
            <tr>
                <td colspan="2" class="submit-row">
                    <button type="submit">Create User</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
