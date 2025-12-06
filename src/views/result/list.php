<head>
    <meta charset="utf-8">
    <title>List of Results</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>

<h1>List of Results</h1>

<div class="top">
    <a href="/">⬅️ Back to home</a>
</div>
<br>
<a href="/results/create">➕ Create Result</a>
<br><br>
<table>
    <tr>
        <th>ID</th>
        <th>Result</th>
        <th>User</th>
        <th>Time</th>
        <th>Actions</th>
    </tr>
    <?php
    /** @var \MiW\Results\Entity\Result[] $results */
    foreach($results as $result): ?>
        <tr>
            <td><?= $result->getId() ?></td>
            <td><?= $result->getResult() ?></td>
            <td><?= $result->getUser()->getUsername() ?? $result->getUser()->getEmail() ?></td>
            <td><?= $result->getTime()->format('Y-m-d H:i:s') ?></td>
            <td>
                <a href="/results/<?= $result->getId() ?>">View</a> |
                <a href="/results/<?= $result->getId() ?>/edit">Edit</a> |
                <a href="/results/<?= $result->getId() ?>/delete">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
