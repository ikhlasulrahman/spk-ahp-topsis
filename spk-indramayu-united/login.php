<?php include 'functions.php'; // Sertakan functions.php untuk mengakses session dan fungsi ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="robots" content="noindex, nofollow" />
    <title>Login - SPK Indramayu United</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f2f5;
        }
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 15px;
        }
        .login-container {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .login-container:hover {
            transform: translateY(-5px);
        }
        .logo-container {
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .logo-container img {
            width: 100px;
            height: auto;
        }
        .login-title {
            color: #333;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            color: #666;
            margin-bottom: 2rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(165, 222, 2, 0.25); /* Warna utama: #A5DE02 */
            border-color: #A5DE02;
        }
        .input-group-text {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
        }
        .btn-primary {
            background-color: #A5DE02;
            border-color: #A5DE02;
            color: #333;
            font-weight: 500;
            padding: 12px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #89b902;
            border-color: #89b902;
            color: #000;
            transform: translateY(-2px);
        }
        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="logo-container">
                <img src="logo.png" alt="Logo Indramayu United">
            </div>

            <h2 class="text-center login-title">Indramayu United</h2>
            <p class="text-center login-subtitle">Sistem Pendukung Keputusan Pemain</p>
            
            <?php 
            if (isset($_SESSION['message'])) {
                print_msg($_SESSION['message']['text'], $_SESSION['message']['type']);
                unset($_SESSION['message']);
            }
            if ($_POST) include 'aksi.php';
            ?>
            <form action="?act=login" method="post">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" name="user" placeholder="Username" autofocus autocomplete="off" required>
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" name="pass" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Masuk</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>