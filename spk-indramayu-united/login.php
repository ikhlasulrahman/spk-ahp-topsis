<?php include 'functions.php'; // Sertakan functions.php untuk mengakses session dan fungsi ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow" />
    <title>SPK AHP-TOPSIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .login-container:hover {
            transform: scale(1.05);
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="login-container">
                    <h1 class="text-center text-primary">Sistem Pendukung Keputusan Indramayu United</h1>
					<h4 class="text-center text-primary">Silakan Masuk</h4>
                    <?php 
                    // --- TAMBAHKAN BLOK INI ---
                    if (isset($_SESSION['message'])) {
                        print_msg($_SESSION['message']['text'], $_SESSION['message']['type']); //
                        unset($_SESSION['message']);
                    }
                    // -------------------------
                    
                    if($_POST) include 'aksi.php';
                    ?>
                    <form action="?act=login" method="post">
                        <div class="mb-3">
                            <label for="user" class="form-label">Username</label>
                            <input type="text" class="form-control" id="user" name="user" placeholder="Masukkan username" autofocus autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <label for="pass" class="form-label">Password</label>
                            <input type="password" class="form-control" id="pass" name="pass" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </form>
					<br>
					<br>
					
                </div>
            </div>
        </div>
    </div>
</body>
</html>