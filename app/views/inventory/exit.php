<h2>Transaksi Barang Keluar</h2>
<form action="index.php?url=inventory/exit_save" method="POST">
    <input type="hidden" name="product_id" value="<?= $_GET['id']; ?>">
    
    <p>Produk: <strong><?= $_GET['name']; ?></strong></p>
    <p>Stok Tersedia: <strong><?= $_GET['current_stock']; ?></strong></p>

    <div style="margin-bottom: 10px;">
        <label>Jumlah Keluar:</label><br>
        <input type="number" name="quantity" min="1" max="<?= $_GET['current_stock']; ?>" required>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Penerima/Pembeli:</label><br>
        <input type="text" name="receiver" placeholder="Nama pelanggan" required>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Alasan:</label><br>
        <select name="reason">
            <option value="Sales">Penjualan</option>
            <option value="Return">Retur ke Supplier</option>
            <option value="Internal">Pemakaian Internal</option>
        </select>
    </div>
    <button type="submit">Proses Barang Keluar</button>
</form>