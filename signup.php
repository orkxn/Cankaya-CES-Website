<?php include 'baglan.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>CES Çankaya | Kayıt Ol</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Orbitron:wght@500;700&display=swap" rel="stylesheet">

  <style>
    
    :root {
      --neon-blue: #00f3ff;
      --bg-color: #050505;
      --text-white: #ffffff;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background-color: var(--bg-color);
      color: var(--text-white);
      font-family: 'Inter', sans-serif;
      height: 100vh;   
      overflow: hidden;
      background-image: 
          linear-gradient(rgba(0, 243, 255, 0.03) 1px, transparent 1px),
          linear-gradient(90deg, rgba(0, 243, 255, 0.03) 1px, transparent 1px);
      background-size: 40px 40px;
      display: flex;
      flex-direction: column;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 40px;
    }

    .logo { height: 90px; filter: drop-shadow(0 0 5px rgba(0, 243, 255, 0.3)); }

    main {
      flex-grow: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    
    .signup-card {
      background: rgba(10, 10, 15, 0.7);
      backdrop-filter: blur(15px);
      border: 1px solid rgba(0, 243, 255, 0.4);
      padding: 40px;
      border-radius: 16px;
      width: 100%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 0 40px rgba(0, 0, 0, 0.8);
      transform: translateY(-80px);
    }

    .signup-card h1 {
      font-family: 'Orbitron', sans-serif;
      color: var(--neon-blue);
      margin-bottom: 25px;
      font-size: 1.8rem;
    }

    .input-group { margin-bottom: 20px; text-align: left; }

    .input-label {
      display: block;
      font-size: 0.85rem;
      margin-bottom: 8px;
      color: var(--neon-blue);
      font-family: 'Orbitron', sans-serif;
    }

    .input-field {
      width: 100%;
      padding: 12px;
      background: rgba(0, 0, 0, 0.5);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 6px;
      color: white;
      outline: none;
      transition: all 0.3s;
    }

    .input-field:focus {
      border-color: var(--neon-blue);
      box-shadow: 0 0 10px rgba(0, 243, 255, 0.2);
    }

    /* Select menüsü için stil */
    select.input-field {
      background-color: #000;
      color: #fff;
    }

    .signup-btn {
      width: 100%;
      padding: 14px;
      background: var(--neon-blue);
      color: #000;
      border: none;
      font-family: 'Orbitron', sans-serif;
      font-weight: 700;
      cursor: pointer;
      border-radius: 6px;
      transition: 0.3s;
      text-transform: uppercase;
    }

    .signup-btn:hover {
      box-shadow: 0 0 25px var(--neon-blue);
      transform: translateY(-2px);
    }

    .footer-link {
        margin-top: 20px;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.5);
    }

    .footer-link a { color: var(--neon-blue); text-decoration: none; }
  </style>
</head>
<body>

  <header>
    <a href="index.html"><img src="assets/ceslogotrans.png" class="logo"></a>
  </header>

  <main>
    <div class="signup-card">
        <h1>YENİ KAYIT</h1>
        <form action="kayit.php" method="POST">
            <div class="input-group">
                <label class="input-label">Kullanıcı Adı</label>
                <input type="text" name="username" class="input-field" required>
            </div>
            
            <div class="input-group">
                <label class="input-label">Şifre</label>
                <input type="password" name="password" class="input-field" required>
            </div>
            
            <div class="input-group">
                <label class="input-label">Güvenlik Sorusu</label>
                <select name="security_question" class="input-field" required>
                    <option value="" disabled selected>Bir soru seçin</option>
                    <option value="1">İlk evcil hayvanınızın adı?</option>
                    <option value="2">Annenizin kızlık soyadı?</option>
                    <option value="3">En sevdiğiniz öğretmen?</option>
                </select>
            </div>
            
            <div class="input-group">
                <label class="input-label">Cevabınız</label>
                <input type="text" name="security_answer" class="input-field" required>
            </div>
			<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="signup-btn">Hesap Oluştur</button>
        </form>
        <div class="footer-link">
            Zaten üye misin? <a href="login.php">Giriş Yap</a>
        </div>
    </div>
  </main>

</body>
</html>