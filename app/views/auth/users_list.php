<h2>Manajemen Akun Pengguna</h2>
<a href="index.php?url=users/add">+ Daftarkan User Baru</a><br><br>

<table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <tr style="background:#eee;">
        <th>Username</th>
        <th>Nama Lengkap</th>
        <th>Role / Hak Akses</th>
    </tr>
    <?php foreach ($allUsers as $u): ?>
    <tr>
        <td><?= $u['username']; ?></td>
        <td><?= $u['full_name']; ?></td>
        <td><strong><?= strtoupper($u['role']); ?></strong></td>
    </tr>
    <?php endforeach; ?>
</table>