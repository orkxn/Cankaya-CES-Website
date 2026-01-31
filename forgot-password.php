<?php
include 'baglan.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    
    // HTML formundan gelen 'value' değeri
    $question_id = $_POST['security_question']; 
    
    
    $answer = mb_strtolower(trim($_POST['security_answer']), 'UTF-8');
    
    $new_password = $_POST['new_password'];

    // Şifre uzunluk kontrolü
    if (strlen($new_password) < 8) {
        echo "<script>alert('Yeni şifre en az 8 karakter olmalıdır!'); history.back();</script>";
        exit();
    }

    try {
        
        $stmt = $baglanti->prepare("SELECT id, security_question, security_answer FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            
            if ($user['security_question'] != $question_id) {
                echo "<script>alert('Seçtiğiniz güvenlik sorusu, kayıtlı olanla eşleşmiyor!'); history.back();</script>";
                exit();
            }

            
            if (password_verify($answer, $user['security_answer'])) {
                
                // Şifre güncelleme işlemi
                $hashed_new = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $baglanti->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->execute([$hashed_new, $user['id']]);

                echo "<script>alert('Şifreniz başarıyla güncellendi! Yeni şifrenizle giriş yapabilirsiniz.'); window.location.href='login.php';</script>";
                exit();
            } else {
                echo "<script>alert('Güvenlik cevabı yanlış!'); history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Böyle bir kullanıcı bulunamadı!'); history.back();</script>";
            exit();
        }
    } catch (PDOException $e) {
        die("Sistem Hatası: " . $e->getMessage());
    }
} else {
    // Form harici girişleri engelle
    header("Location: login.php");
    exit();
}
?>