<h2>Riwayat Perubahan Harga: <?= $product['product_name']; ?></h2>
<p><a href="index.php?url=inventory"> Kembali ke Inventory</a></p>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Tanggal Perubahan</th>
            <th>Harga Lama</th>
            <th>Harga Baru</th>
            <th>Selisih</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($history as $h) : 
            $diff = $h['new_price'] - $h['old_price'];
        ?>
        <tr>
            <td><?= date('d/m/Y H:i', strtotime($h['change_date'])); ?></td>
            <td>Rp <?= number_format($h['old_price'], 0, ',', '.'); ?></td>
            <td>Rp <?= number_format($h['new_price'], 0, ',', '.'); ?></td>
            <td style="color: <?= ($diff > 0) ? 'green' : 'red'; ?>;">
                <?= ($diff > 0) ? '+' : ''; ?><?= number_format($diff, 0, ',', '.'); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>