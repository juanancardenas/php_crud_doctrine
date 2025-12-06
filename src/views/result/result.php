<?php
/**
 * views/result/show.php
 *
 * Muestra un único Result (estilo coherente con "Result list")
 *
 * @var \MiW\Results\Entity\Result $result
 */
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Result #<?= htmlspecialchars($result->getId()) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>

<h1>Result details</h1>

<div class="top">
    <a href="/results">⬅️ Back to results</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <td><?= htmlspecialchars((string)$result->getId()) ?></td>
    </tr>
    <tr>
        <th>Result</th>
        <td><?= htmlspecialchars((string)$result->getResult()) ?></td>
    </tr>
    <tr>
        <th>User</th>
        <td>
            <?php
            $user = $result->getUser();
            if ($user !== null) {
                $display = $user->getUsername() ?? $user->getEmail() ?? '—';
                echo htmlspecialchars($display);
            } else {
                echo '—';
            }
            ?>
        </td>
    </tr>
    <tr>
        <th>Time</th>
        <td>
            <?php
            $time = $result->getTime();
            echo $time instanceof \DateTime ? htmlspecialchars($time->format('Y-m-d H:i:s')) : '—';
            ?>
        </td>
    </tr>
    <tr>
        <th>Raw JSON</th>
        <td><pre style="margin:0; white-space:pre-wrap; font-family:monospace; font-size:13px;"><?= htmlspecialchars(json_encode($result, JSON_PRETTY_PRINT)) ?></pre></td>
    </tr>
</table>
<!--
<p class="actions" style="margin-top:12px;">
    <a href="/results/<?= htmlspecialchars($result->getId()) ?>/edit">Edit</a> |
    <a href="/results/<?= htmlspecialchars($result->getId()) ?>/delete">Delete</a>
</p>
-->
</body>
</html>
