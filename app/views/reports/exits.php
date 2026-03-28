<h2>Laporan Barang Keluar</h2>
<p><a href="index.php?url=inventory"> Kembali ke Inventory</a></p>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Tanggal</th>
            <th>SKU</th>
            <th>Nama Produk</th>
            <th>Jumlah Keluar</th>
            <th>Penerima</th>
            <th>Alasan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($exits as $e) : ?>
        <tr>
            <td><?= date('d/m/Y H:i', strtotime($e['exit_date'])); ?></td>
            <td><?= $e['sku']; ?></td>
            <td><?= $e['product_name']; ?></td>
            <td style="color: orange; font-weight: bold;">- <?= $e['quantity']; ?></td>
            <td><?= $e['receiver']; ?></td>
            <td><span style="padding: 3px 8px; background: #eee; border-radius: 4px;"><?= $e['reason']; ?></span></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>