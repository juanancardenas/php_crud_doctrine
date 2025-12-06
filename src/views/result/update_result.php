<?php
/**
 * views/result/update_result.php
 *
 * Formulario de edición de un resultado
 *
 * @var \MiW\Results\Entity\Result $result
 * @var \MiW\Results\Entity\User[] $users
 */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Edit Result #<?= htmlspecialchars($result->getId()) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <h1>Edit Result</h1>
    <div class="top">
        <a href="/results">⬅️ Back to results</a>
    </div>
    <form method="POST" action="/results/<?= htmlspecialchars($result->getId()) ?>/edit">
        <table>
            <tr>
                <th>ID</th>
                <td><?= htmlspecialchars($result->getId()) ?></td>
            </tr>
            <tr>
                <th>Result</th>
                <td><input type="number" name="result" value="<?= htmlspecialchars($result->getResult()) ?>" required autofocus></td>
            </tr>
            <tr>
                <th>User</th>
                <td>
                    <select name="user" required>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= htmlspecialchars($user->getId()) ?>"
                                <?= $user->getId() === $result->getUser()->getId() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($user->getUsername() . ' (' . $user->getEmail() . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Time</th>
                <td>
                    <input type="datetime-local" name="time"
                           value="<?= $result->getTime()->format('Y-m-d\TH:i') ?>" required>
                </td>
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
