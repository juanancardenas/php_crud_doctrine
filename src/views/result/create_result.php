<?php
/**
 * views/result/create_result.php
 *
 * Formulario de creación de un resultado
 *
 * @var \MiW\Results\Entity\User[] $users
 */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Create Result</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
<h1>Create Result</h1>
<div class="top">
    <a href="/results">⬅️ Back to results</a>
</div>
<form method="POST" action="/results/create">
    <table>
        <tr>
            <th>Result</th>
            <td><input type="number" name="result" required autofocus></td>
        </tr>
        <tr>
            <th>User</th>
            <td>
                <select name="user" required>
                    <option value="">-- Select User --</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= htmlspecialchars($user->getId()) ?>">
                            <?= htmlspecialchars($user->getUsername() . ' (' . $user->getEmail() . ')') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Time</th>
            <td><input type="datetime-local" name="time" required></td>
        </tr>
        <tr>
            <td colspan="2" class="submit-row">
                <button type="submit">Create Result</button>
            </td>
        </tr>
    </table>
</form>
</body>
</html>
