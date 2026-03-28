<style>
    body { font-family: sans-serif; font-size: 12px; }
    .header { text-align: center; margin-bottom: 20px; }
    .invoice-info { margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    table, th, td { border: 1px solid #ddd; }
    th, td { padding: 8px; text-align: left; }
    .total { text-align: right; font-weight: bold; font-size: 14px; }
</style>

<div class="header">
    <h1>INVOICE PENJUALAN</h1>
    <p>WMS - Research & Engineering Supply</p>
</div>

<div class="invoice-info">
    <p>No. Invoice: <strong><?= $data['header']['invoice_number']; ?></strong></p>
    <p>Tanggal: <?= date('d/m/Y H:i', strtotime($data['header']['order_date'])); ?></p>
    <p>Pelanggan: <?= $data['header']['customer_name']; ?></p>
</div>

<table>
    <thead>
        <tr style="background: #f2f2f2;">
            <th>SKU</th>
            <th>Nama Barang</th>
            <th>Harga Satuan</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['details'] as $item): ?>
        <tr>
            <td><?= $item['sku']; ?></td>
            <td><?= $item['product_name']; ?></td>
            <td>Rp <?= number_format($item['price_at_purchase'], 0, ',', '.'); ?></td>
            <td><?= $item['quantity']; ?> <?= $item['unit']; ?></td>
            <td>Rp <?= number_format($item['price_at_purchase'] * $item['quantity'], 0, ',', '.'); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<p class="total">Total Bayar: Rp <?= number_format($data['header']['total_amount'], 0, ',', '.'); ?></p>