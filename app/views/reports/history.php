<h2>Laporan Riwayat Stock Opname</h2>
<p><a href="index.php?url=inventory"> Kembali ke Inventory</a></p>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Tanggal</th>
            <th>SKU</th>
            <th>Nama Produk</th>
            <th>Stok Sistem</th>
            <th>Stok Fisik</th>
            <th>Selisih</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($history as $h) : ?>
        <tr>
            <td><?= date('d/m/Y H:i', strtotime($h['opname_date'])); ?></td>
            <td><?= $h['sku']; ?></td>
            <td><?= $h['product_name']; ?></td>
            <td><?= $h['system_stock']; ?></td>
            <td><?= $h['physical_stock']; ?></td>
            <td style="color: <?= ($h['difference'] < 0) ? 'red' : 'green'; ?>; font-weight: bold;">
                <?= ($h['difference'] > 0) ? '+' . $h['difference'] : $h['difference']; ?>
            </td>
            <td><?= $h['note']; ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>