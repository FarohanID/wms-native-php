<h2>Daftarkan User Baru</h2>
<form action="index.php?url=users/save" method="POST">
    <table cellpadding="8">
        <tr>
            <td>Username</td>
            <td>: <input type="text" name="username" required></td>
        </tr>
        <tr>
            <td>Password</td>
            <td>: <input type="password" name="password" required></td>
        </tr>
        <tr>
            <td>Nama Lengkap</td>
            <td>: <input type="text" name="full_name" required></td>
        </tr>
        <tr>
            <td>Role</td>
            <td>: 
                <select name="role">
                    <option value="staff">Staff (Input Transaksi)</option>
                    <option value="manager">Manager (Lihat Laporan)</option>
                    <option value="admin">Admin (Full Akses)</option>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><button type="submit">Simpan User</button> <a href="index.php?url=users">Batal</a></td>
        </tr>
    </table>
</form>