<?php
/**
 * views/user/list.php
 *
 * Ver todos los usuarios
 *
 * @var \MiW\Results\Entity\User[] $users
 */
?>
<head>
    <meta charset="utf-8">
    <title>List of Users</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <h1>List of Users</h1>
    <div class="top">
        <a href="/">⬅️ Back to home</a>
    </div>
    <br><a href="/users/create">➕ Create User</a>
    <br><br>
    <table>
        <tr>
            <th>ID</th><th>Username</th><th>Email</th><th>Enabled</th><th>Admin</th><th>Actions</th>
        </tr>
        <?php
        foreach($users as $user): ?>
            <tr>
                <td><?= $user->getId() ?></td>
                <td><?= $user->getUsername() ?></td>
                <td><?= $user->getEmail() ?></td>
                <td><?= $user->isEnabled() ? 'Yes' : 'No' ?></td>
                <td><?= $user->isAdmin() ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="/users/<?= $user->getId() ?>">View</a> |
                    <a href="/users/<?= $user->getId() ?>/edit">Edit</a> |
                    <a href="/users/<?= $user->getId() ?>/delete">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
