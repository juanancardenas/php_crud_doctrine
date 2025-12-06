<?php
/**
 * views/user/update.php
 *
 * Formulario de edición de usuario
 *
 * @var \MiW\Results\Entity\User $user
 */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Edit User #<?= htmlspecialchars($user->getId()) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<h1>Edit User</h1>
<div class="top">
    <a href="/users">⬅️ Back to users</a>
</div>
    <form method="POST" action="/users/<?= htmlspecialchars($user->getId()) ?>/edit">
        <table>
            <tr>
                <th>ID</th>
                <td><?= htmlspecialchars($user->getId()) ?></td>
            </tr>
            <tr>
                <th>Username</th>
                <td><input type="text" name="username" value="<?= htmlspecialchars($user->getUsername()) ?>" required></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="email" name="email" value="<?= htmlspecialchars($user->getEmail()) ?>" required></td>
            </tr>
            <tr>
                <th>Password</th>
                <td>
                    <input type="password" name="password" placeholder="Leave empty to keep current password">
                </td>
            </tr>
            <tr>
                <th>Enabled</th>
                <td><input type="checkbox" name="enabled" <?= $user->isEnabled() ? 'checked' : '' ?>></td>
            </tr>
            <tr>
                <th>Admin</th>
                <td><input type="checkbox" name="admin" <?= $user->isAdmin() ? 'checked' : '' ?>></td>
            </tr>
            <tr>
                <td colspan="2" class="submit-row">
                    <button type="submit">Save Changes</button>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
