<h2>Transaksi COD Baru (Direct Sales)</h2>
<form action="index.php?url=sales/save" method="POST">
    <div style="margin-bottom: 15px;">
        <label>Nomor Invoice:</label><br>
        <input type="text" name="invoice_number" value="<?= $invoiceNumber; ?>" readonly style="background:#eee;">
    </div>
    <div style="margin-bottom: 15px;">
        <label>Nama Pelanggan COD:</label><br>
        <input type="text" name="customer_name" required placeholder="Contoh: Rina Ariyanti (seperti di screenshot)">
    </div>

    <h3>Daftar Barang</h3>
    <table border="1" cellpadding="8" cellspacing="0" id="itemsTable" style="width:100%; border-collapse:collapse; margin-bottom:15px;">
        <tr style="background:#eee;">
            <th>Produk</th>
            <th>Harga Satuan</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
        </table>

    <button type="button" onclick="addRow()" style="margin-bottom: 20px;">+ Tambah Barang</button>

    <div style="float: right; text-align: right; width: 300px; border: 1px solid #ccc; padding: 15px;">
        <strong>Grand Total:</strong><br>
        <span style="font-size: 24px; color: blue;" id="grandTotalDisplay">Rp 0</span>
        <input type="hidden" name="total_amount" id="total_amount_input">
    </div>

    <div style="clear: both; padding-top: 20px;">
        <button type="submit" style="background: green; color: white; padding: 10px 20px; font-weight: bold;">Simpan & Cetak Pesanan</button>
        <a href="index.php?url=dashboard">Batal</a>
    </div>
</form>

<script>
    const products = <?= json_encode($productsJSON); ?>; // Data produk dari PHP ke JS
    let rowCount = 0;

    function addRow() {
        const table = document.getElementById("itemsTable");
        const row = table.insertRow();
        row.id = `row_${rowCount}`;

        // Kolom 1: Pilih Produk
        let cell1 = row.insertCell(0);
        let select = `<select name="items[${rowCount}][product_id]" onchange="updateRowPrice(this, ${rowCount})" required style="width:100%;">`;
        select += '<option value="">-- Pilih Produk --</option>';
        products.forEach(p => {
            select += `<option value="${p.id}" data-price="${p.price}" data-stock="${p.stock}">${p.sku} - ${p.product_name} (Stok: ${p.stock})</option>`;
        });
        select += '</select>';
        cell1.innerHTML = select;

        // Kolom 2: Harga
        let cell2 = row.insertCell(1);
        cell2.innerHTML = `<input type="number" name="items[${rowCount}][price]" id="price_${rowCount}" readonly style="background:#eee; text-align:right;">`;

        // Kolom 3: Jumlah
        let cell3 = row.insertCell(2);
        cell3.innerHTML = `<input type="number" name="items[${rowCount}][quantity]" id="qty_${rowCount}" min="1" required oninput="calculateRowSubtotal(${rowCount})">`;

        // Kolom 4: Subtotal
        let cell4 = row.insertCell(3);
        cell4.innerHTML = `<input type="number" id="subtotal_${rowCount}" readonly style="background:#eee; text-align:right;" class="subtotal_input">`;

        // Kolom 5: Aksi (Hapus Baris)
        let cell5 = row.insertCell(4);
        cell5.innerHTML = `<button type="button" onclick="removeRow(${rowCount})" style="color:red;">Hapus</button>`;

        rowCount++;
    }

    function updateRowPrice(selectObj, index) {
        const selectedOption = selectObj.options[selectObj.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        const stock = selectedOption.getAttribute('data-stock');
        document.getElementById(`price_${index}`).value = price;
        document.getElementById(`qty_${index}`).max = stock; // Validasi stok maksimal
        calculateRowSubtotal(index);
    }

    function calculateRowSubtotal(index) {
        const price = document.getElementById(`price_${index}`).value || 0;
        const qty = document.getElementById(`qty_${index}`).value || 0;
        const subtotal = price * qty;
        document.getElementById(`subtotal_${index}`).value = subtotal;
        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.subtotal_input').forEach(input => {
            grandTotal += parseFloat(input.value) || 0;
        });
        document.getElementById('grandTotalDisplay').innerText = "Rp " + grandTotal.toLocaleString('id-ID');
        document.getElementById('total_amount_input').value = grandTotal;
    }

    function removeRow(index) {
        document.getElementById(`row_${index}`).remove();
        calculateGrandTotal();
    }

    // Tambah satu baris default saat halaman dimuat
    addRow();
</script>