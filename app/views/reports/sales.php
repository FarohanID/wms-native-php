<h2>Laporan Transaksi COD (Direct Sales)</h2>
<p><a href="index.php?url=dashboard">Kembali ke Dashboard</a></p>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #f2f2f2;">
            <th>Tanggal</th>
            <th>No. Invoice</th>
            <th>Nama Pelanggan</th>
            <th>Total Pembayaran</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $o) : ?>
        <tr>
            <td><?= date('d/m/Y H:i', strtotime($o['order_date'])); ?></td>
            <td><strong><?= $o['invoice_number']; ?></strong></td>
            <td><?= $o['customer_name']; ?></td>
            <td>Rp <?= number_format($o['total_amount'], 0, ',', '.'); ?></td>
            <td>
                <a href="index.php?url=reports/sales_detail&id=<?= $o['id']; ?>&inv=<?= $o['invoice_number']; ?>">Lihat Detail</a>
            </td>
            <td>
                <a href="index.php?url=sales/print_pdf&id=<?= $o['id']; ?>" target="_blank">Cetak PDF</a>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>