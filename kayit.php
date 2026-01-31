<?php
include 'baglan.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    //CSRF 
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        // Hata durumunda boş ekran yerine hata mesajı bastırıp durduralım
        die("Güvenlik Hatası: CSRF Token uyuşmazlığı. Lütfen sayfayı yenileyip tekrar deneyin.");
    }

    // Formdan gelen verileri al ve boşlukları temizle
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $s_question = $_POST['security_question'];
    
    // Güvenlik cevabını hashle
    $s_answer = password_hash(mb_strtolower(trim($_POST['security_answer']), 'UTF-8'), PASSWORD_DEFAULT);

    
    if (empty($s_question)) {
        echo "<script>alert('Lütfen bir güvenlik sorusu seçin!'); history.back();</script>";
        exit();
    }

    if (strlen($username) < 3) {
        echo "<script>alert('Kullanıcı adı en az 3 karakter olmalıdır!'); history.back();</script>";
        exit();
    }

    if (strlen($password) < 8) {
        echo "<script>alert('Şifre güvenliğiniz için en az 8 karakter olmalıdır!'); history.back();</script>";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        
        $check = $baglanti->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$username]);

        if ($check->rowCount() > 0) {
            echo "<script>alert('Bu kullanıcı adı zaten alınmış!'); history.back();</script>";
            exit(); 
        } else {
            
            $sql = "INSERT INTO users (username, password, security_question, security_answer, role, is_approved) VALUES (?, ?, ?, ?, 'user', 0)";
            $stmt = $baglanti->prepare($sql);
            
            if ($stmt->execute([$username, $hashed_password, $s_question, $s_answer])) {
                
                echo "<script>
                        alert('Kaydınız oluştu. Onay için admin\'e Whatsapp\'tan ya da ork.74@hotmail.com adlı mail üzerinden ulaşın.'); 
                        window.location.href='login.php';
                      </script>";
            } 
            else {
                echo "<script>alert('Kayıt sırasında bir teknik hata oluştu.'); history.back();</script>";
            }
        }
    } catch (PDOException $e) { 
        // Veritabanı hatasını ekrana bas
        die("Veritabanı Hatası: " . $e->getMessage()); 
    }
} else {
    header("Location: index.html");
    exit();
}
?>