<h2>Tambah Produk Baru</h2>
<form action="index.php?url=inventory/save" method="POST">
    <div style="margin-bottom: 10px;">
        <label>SKU Produk:</label><br>
        <input type="text" name="sku" required placeholder="Contoh: ELC-001">
    </div>
    <div style="margin-bottom: 10px;">
        <label>Nama Produk:</label><br>
        <input type="text" name="product_name" required>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Kategori:</label><br>
        <select name="category">
            <option value="Elektronik">Elektronik</option>
            <option value="Furniture">Furniture</option>
            <option value="ATK">ATK</option>
        </select>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Harga Satuan:</label><br>
        <input type="number" name="price" step="0.01" required>
    </div>
    <div style="margin-bottom: 10px;">
        <label>Satuan:</label><br>
        <input type="text" name="unit" value="unit">
    </div>
    <button type="submit">Simpan Produk</button>
    <a href="index.php?url=inventory">Batal</a>
</form>