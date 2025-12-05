<head>
    <meta charset="utf-8">
    <title>List of Results</title>
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
