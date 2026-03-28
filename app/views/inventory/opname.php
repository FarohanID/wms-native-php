<h2>Stock Opname</h2>
<form action="index.php?url=inventory/opname_save" method="POST">
    <input type="hidden" name="product_id" value="<?= $_GET['id']; ?>">
    <input type="hidden" name="system_stock" value="<?= $_GET['current_stock']; ?>">

    <p>Nama Produk: <strong><?= $_GET['name']; ?></strong></p>
    <p>Stok di Sistem: <strong><?= $_GET['current_stock']; ?></strong></p>

    <div style="margin-bottom: 10px;">
        <label>Stok Fisik di Gudang:</label><br>
        <input type="number" name="physical_stock" required>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Keterangan:</label><br>
        <textarea name="note" placeholder="Contoh: Barang hilang atau rusak"></textarea>
    </div>
    <button type="submit">Konfirmasi Opname</button>
</form>