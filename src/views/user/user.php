<?php
/**
 * views/user/show.php
 *
 * Muestra un único User
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
    <style>
        body { font-family: Arial, Helvetica, sans-serif; margin: 20px; }
        a { text-decoration: none; color: #0366d6; }
        table { border-collapse: collapse; width: 720px; max-width: 100%; }
        th, td { padding: 8px 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f6f8fa; font-weight: 600; }
        .actions a { margin-right: 8px; }
        .top { margin-bottom: 16px; }
    </style>
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
        <td><pre style="margin:0; white-space:pre-wrap; font-family:monospace; font-size:13px;">
<?= htmlspecialchars(json_encode($user, JSON_PRETTY_PRINT)) ?></pre></td>
    </tr>
</table>
<!--
<p class="actions" style="margin-top:12px;">
    <a href="/users/<?= htmlspecialchars($user->getId()) ?>/edit">Edit</a> |
    <a href="/users/<?= htmlspecialchars($user->getId()) ?>/delete">Delete</a>
</p>
-->
</body>
</html>
