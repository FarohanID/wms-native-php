<h2>Ubah Harga Produk</h2>
<p>Produk: <strong><?= $_GET['name']; ?></strong> (SKU: <?= $_GET['sku']; ?>)</p>

<form action="index.php?url=inventory/update_price_save" method="POST">
    <input type="hidden" name="product_id" value="<?= $_GET['id']; ?>">
    
    <div style="margin-bottom: 15px;">
        <label>Harga Saat Ini:</label><br>
        <input type="text" value="Rp <?= number_format($_GET['current_price'], 0, ',', '.'); ?>" readonly style="background: #eee;">
    </div>

    <div style="margin-bottom: 15px;">
        <label>Harga Baru (Angka saja):</label><br>
        <input type="number" name="new_price" step="0.01" required placeholder="Contoh: 1500000">
    </div>

    <button type="submit" style="background: blue; color: white; padding: 10px 20px; border: none; cursor: pointer;">
        Simpan Perubahan Harga
    </button>
    <a href="index.php?url=inventory">Batal</a>
</form>