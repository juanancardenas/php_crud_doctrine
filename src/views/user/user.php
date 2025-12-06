<?php
/**
 * views/user/user.php
 *
 * Muestra un único usuario
 *
 * @var \MiW\Results\Entity\User $user
 */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>User #<?= htmlspecialchars($user->getId()) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>

<h1>User details</h1>
<br>
<div class="top">
    <a href="/users">⬅️ Back to users</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <td><?= htmlspecialchars((string)$user->getId()) ?></td>
    </tr>
    <tr>
        <th>Username</th>
        <td><?= htmlspecialchars($user->getUsername()) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($user->getEmail()) ?></td>
    </tr>
    <tr>
        <th>Enabled</th>
        <td><?= $user->isEnabled() ? 'Yes' : 'No' ?></td>
    </tr>
    <tr>
        <th>Admin</th>
        <td><?= $user->isAdmin() ? 'Yes' : 'No' ?></td>
    </tr>
    <tr>
        <th>Password (hash)</th>
        <td><?= htmlspecialchars('*************') ?></td>
    </tr>
    <tr>
        <th>Raw JSON</th>
        <td><pre class="json"><?= htmlspecialchars(json_encode($user, JSON_PRETTY_PRINT)) ?></pre></td>
    </tr>
</table>
</body>
</html>
