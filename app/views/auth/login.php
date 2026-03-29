<div style="width: 300px; margin: 100px auto; padding: 20px; border: 1px solid #ccc; border-radius: 10px; text-align: center;">
    <h2>WMS Login</h2>
    <?php if(isset($_GET['error'])): ?>
        <p style="color: red;">Username atau Password salah!</p>
    <?php endif; ?>
    
    <form action="index.php?url=auth/process" method="POST">
        <input type="text" name="username" placeholder="Username" required style="width: 100%; margin-bottom: 10px; padding: 8px;"><br>
        <input type="password" name="password" placeholder="Password" required style="width: 100%; margin-bottom: 10px; padding: 8px;"><br>
        <button type="submit" style="width: 100%; padding: 10px; background: #222; color: #fff; border: none; cursor: pointer;">Login</button>
    </form>
</div>