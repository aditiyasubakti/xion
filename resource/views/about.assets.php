<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>heelo</title>
</head>
<body>
<h1><?= $title ?></h1>

<h3>Daftar User</h3>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Email</th>
    </tr>

    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['nama'] ?></td>
            <td><?= $u['email'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>