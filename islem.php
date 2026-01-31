<?php
include 'baglan.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // CSRF PROTECTION VAR :)
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Güvenlik Hatası: Geçersiz CSRF Token!");
    }

    $username = trim($_POST['username'] ?? '');
    $sifre = $_POST['password'] ?? '';

    if (empty($username) || empty($sifre)) {
        echo "<script>alert('Lütfen tüm alanları doldurun!'); window.location.href='login.php';</script>";
        exit();
    }

    try {
        $sorgu = $baglanti->prepare("SELECT id, username, password, role, is_approved FROM users WHERE username = ?");
        $sorgu->execute([$username]);
        $user = $sorgu->fetch();

        if ($user && password_verify($sifre, $user['password'])) {
            
            if ($user['username'] !== $username) {
                echo "<script>alert('Hatalı kullanıcı adı veya şifre!'); window.location.href='login.php';</script>";
                exit();
            }

            // Yönetici onay kontrolü
            if ($user['is_approved'] == 0) {
                echo "<script>alert('Hesabınız henüz yönetici tarafından onaylanmamış! Onay için admin\'e Whatsapp\'tan ya da ork.74@hotmail.com adlı mail üzerinden ulaşın.'); window.location.href='login.php';</script>";
                exit();
            }

            // GÜVENLİ OTURUM BAŞLATMA
            session_regenerate_id(true); 
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; 
            
            header("Location: dashboard.php");
            exit();

        } else {
            echo "<script>alert('Hatalı kullanıcı adı veya şifre!'); window.location.href='login.php';</script>";
            exit();
        }

    } catch (PDOException $e) {
        echo "<script>alert('Hatalı kullanıcı adı veya şifre!'); window.location.href='login.php';</script>";
        exit();
    }
} else {
    header("Location: index.html");
    exit();
}
?>