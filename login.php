<?php include 'baglan.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CES Çankaya | Giriş Yap</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Orbitron:wght@500;700&display=swap" rel="stylesheet">

  <style>
    
    :root {
      --neon-blue: #00f3ff;
      --bg-color: #050505;
      --text-white: #ffffff;
      --glass-bg: rgba(0, 0, 0, 0.8);
      --card-bg: rgba(255, 255, 255, 0.03);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background-color: var(--bg-color);
      color: var(--text-white);
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
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
      position: relative;
      width: 100%;
    }

    .logo {
      height: 90px;
      width: auto;
      filter: drop-shadow(0 0 5px rgba(0, 243, 255, 0.3));
      cursor: pointer;
      transition: transform 0.3s ease;
    }
    .logo:hover { transform: scale(1.05); }

    .menu-btn {
      background: transparent;
      border: 1px solid var(--neon-blue);
      color: var(--neon-blue);
      font-size: 1.5rem;
      padding: 5px 15px;
      border-radius: 4px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 0 5px rgba(0, 243, 255, 0.2);
    }
    .menu-btn:hover {
      background: var(--neon-blue);
      color: #000;
      box-shadow: 0 0 15px var(--neon-blue);
    }

    
    .nav-box {
      display: none;
      position: absolute;
      top: 100px;
      right: 40px;
      background-color: var(--glass-bg);
      backdrop-filter: blur(10px);
      border: 1px solid var(--neon-blue);
      border-radius: 8px;
      padding: 20px;
      flex-direction: column;
      gap: 15px;
      min-width: 200px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
      z-index: 100;
    }
    .nav-box.active { display: flex; }
    .nav-box a {
      color: white;
      text-decoration: none;
      font-family: 'Orbitron', sans-serif;
      font-size: 0.9rem;
      padding: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      transition: color 0.3s;
    }
    .nav-box a:last-child { border-bottom: none; }
    .nav-box a:hover { color: var(--neon-blue); text-shadow: 0 0 8px var(--neon-blue); }

    
    main {
      flex-grow: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    
    .login-card {
      background: rgba(10, 10, 10, 0.6);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(0, 243, 255, 0.3);
      padding: 40px;
      border-radius: 12px;
      width: 100%;
      max-width: 400px;
      text-align: center;
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.7);
      position: relative;
      overflow: hidden;
      
      
      transform: translateY(-60px);
    }
    
    .login-card::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(0,243,255,0.05), transparent 60%);
        z-index: -1;
    }

    .login-card h1 {
      font-family: 'Orbitron', sans-serif;
      color: var(--neon-blue);
      margin-bottom: 30px;
      font-size: 2rem;
      letter-spacing: 2px;
      text-shadow: 0 0 10px rgba(0, 243, 255, 0.5);
    }

    
    .input-group {
      margin-bottom: 20px;
      text-align: left;
    }

    .input-label {
      display: block;
      font-size: 0.9rem;
      margin-bottom: 8px;
      color: rgba(255, 255, 255, 0.8);
      font-family: 'Orbitron', sans-serif;
    }

    .input-field {
      width: 100%;
      padding: 12px 15px;
      background: rgba(0, 0, 0, 0.4);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 5px;
      color: white;
      font-family: 'Inter', sans-serif;
      font-size: 1rem;
      outline: none;
      transition: all 0.3s ease;
    }

    .input-field:focus {
      border-color: var(--neon-blue);
      box-shadow: 0 0 15px rgba(0, 243, 255, 0.2);
      background: rgba(0, 243, 255, 0.05);
    }

    .login-btn {
      width: 100%;
      padding: 14px;
      margin-top: 10px;
      background: transparent;
      color: var(--neon-blue);
      border: 1px solid var(--neon-blue);
      font-family: 'Orbitron', sans-serif;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      border-radius: 5px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .login-btn:hover {
      background: var(--neon-blue);
      color: #000;
      box-shadow: 0 0 20px var(--neon-blue);
      transform: translateY(-2px);
    }

    .extra-links {
      margin-top: 20px;
      font-size: 0.85rem;
      color: rgba(255, 255, 255, 0.6);
      display: flex;
      justify-content: space-between;
    }

    .extra-links a {
      color: white;
      text-decoration: none;
      transition: color 0.3s;
    }

    .extra-links a:hover {
      color: var(--neon-blue);
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      header { padding: 15px 20px; }
      .logo { height: 60px; }
      .nav-box { right: 20px; top: 90px; }
      .login-card { padding: 30px 20px; transform: translateY(-30px); }
    }
  </style>
</head>
<body>

  <header>
    <a href="index.html">
        <img src="assets/ceslogotrans.png" alt="CES Logo" class="logo">
    </a>
    <button id="menu-btn" class="menu-btn">☰</button>
    
    <nav id="nav-box" class="nav-box">
      <a href="index.html">ANA SAYFA</a>
      <a href="about.html">HAKKIMIZDA</a>
      <a href="page1.html">HABERLER</a>
      <a href="contact.html">İLETİŞİM</a>
    </nav>
  </header>

  <main>
    <div class="login-card">
        <h1>GİRİŞ YAP</h1>
        
        <form action="islem.php" method="POST">
            <div class="input-group">
                <label class="input-label">Kullanıcı Adı</label>
                <input type="text" name="username" class="input-field" placeholder="Kullanıcı adınızı girin" required>
            </div>

            <div class="input-group">
                <label class="input-label">Şifre</label>
                <input type="password" name="password" class="input-field" placeholder="••••••••" required>
            </div>
			<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button type="submit" class="login-btn">GİRİŞ YAP</button>
        </form>

        <div class="extra-links">
            <a href="forgot-password.html">Şifremi Unuttum</a>
            <a href="/signup.php">Kayıt Ol</a>
        </div>
    </div>
  </main>

  <script>
    const menuBtn = document.getElementById('menu-btn');
    const navBox = document.getElementById('nav-box');

    menuBtn.addEventListener('click', () => {
      navBox.classList.toggle('active');
      if (navBox.classList.contains('active')) {
        menuBtn.innerHTML = "✕";
      } else {
        menuBtn.innerHTML = "☰";
      }
    });

    document.addEventListener('click', (event) => {
      if (!navBox.contains(event.target) && !menuBtn.contains(event.target)) {
        navBox.classList.remove('active');
        menuBtn.innerHTML = "☰";
      }
    });
  </script>
</body>
</html>