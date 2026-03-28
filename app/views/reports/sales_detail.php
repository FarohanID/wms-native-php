<h2>Detail Invoice: <?= $_GET['inv']; ?></h2>
<p><a href="index.php?url=reports/sales"> kembali ke Laporan</a></p>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #eee;">
            <th>SKU</th>
            <th>Nama Produk</th>
            <th>Harga Satuan</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($details as $d) : ?>
        <tr>
            <td><?= $d['sku']; ?></td>
            <td><?= $d['product_name']; ?></td>
            <td>Rp <?= number_format($d['price_at_purchase'], 0, ',', '.'); ?></td>
            <td><?= $d['quantity']; ?></td>
            <td>Rp <?= number_format($d['price_at_purchase'] * $d['quantity'], 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>