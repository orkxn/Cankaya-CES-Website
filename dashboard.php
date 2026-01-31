<?php
include 'baglan.php';
session_start();

// 1. ADIM: Saat Ayarları (İstanbul)
date_default_timezone_set('Europe/Istanbul');

// MySQL bağlantısının da saati doğru alması için
try {
    $baglanti->exec("SET time_zone = '+03:00'");
} catch (PDOException $e) {
    // Hata verirse sessizce devam et
}

// Güvenlik: Giriş yapmamışsa login sayfasına at
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Çıkış Yapma
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$active_user = $_SESSION['username'];
$message = "";


if (isset($_GET['del'])) {
    $del_id = (int)$_GET['del']; // ID'yi integer'a çevir (SQL Injection önlemi)

    
    if (!isset($_GET['token']) || $_GET['token'] !== $_SESSION['csrf_token']) {
        die("Güvenlik Hatası: Geçersiz Token! (Silme İşlemi)");
    }
    
    // Sadece kendi entry'sini silebilir (Güvenlik Kontrolü)
    $stmt = $baglanti->prepare("DELETE FROM entries WHERE id = ? AND username = ?");
    $stmt->execute([$del_id, $active_user]);
    
    header("Location: dashboard.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['share_entry'])) {
    
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Güvenlik Hatası: Geçersiz CSRF Token! (Paylaşım İşlemi)");
    }

    $desc = trim($_POST['description']);
    $link = trim($_POST['link']);

    if (!empty($desc) && !empty($link)) {
        if (!preg_match("~^(?:f|ht)tps?://~i", $link)) {
            $link = "http://" . $link;
        }

        try {
            $stmt = $baglanti->prepare("INSERT INTO entries (username, description, link) VALUES (?, ?, ?)");
            $stmt->execute([$active_user, $desc, $link]);
            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            $message = "Hata: " . $e->getMessage();
        }
    }
}


$search_query = "";
$sql = "SELECT * FROM entries ORDER BY created_at DESC";
$params = [];

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search_query = trim($_GET['q']);
    $sql = "SELECT * FROM entries WHERE description LIKE ? OR username LIKE ? ORDER BY created_at DESC";
    $params = ["%$search_query%", "%$search_query%"];
}

$stmt = $baglanti->prepare($sql);
$stmt->execute($params);
$entries = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CES Çankaya | Dashboard</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        :root { --neon-blue: #00f3ff; --bg-color: #050505; --text-white: #ffffff; --neon-red: #ff3333; }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-white);
            font-family: 'Inter', sans-serif;
            background-image: linear-gradient(rgba(0, 243, 255, 0.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 243, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            min-height: 100vh;
        }

        
        header {
            background: rgba(10, 10, 15, 0.7);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0, 243, 255, 0.6); 
            box-shadow: 0 4px 15px rgba(0, 243, 255, 0.15); 
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo { height: 50px; filter: drop-shadow(0 0 8px rgba(0, 243, 255, 0.5)); }
        
        .user-info { 
            font-family: 'Exo 2', sans-serif; 
            color: var(--neon-blue); 
            display: flex; 
            align-items: center; 
            gap: 25px; 
            font-size: 1.4rem; 
            font-weight: 700; 
            text-shadow: 0 0 15px rgba(0, 243, 255, 0.6); 
        }

        .logout-btn { 
            color: rgba(255, 255, 255, 0.9); 
            text-decoration: none; 
            border: 2px solid rgba(255, 51, 51, 0.7); 
            padding: 10px 24px; 
            font-size: 1rem;    
            border-radius: 8px; 
            transition: 0.3s; 
            font-family: 'Exo 2', sans-serif; 
            font-weight: 600;
            letter-spacing: 1px;
        }
        .logout-btn:hover { 
            background: rgba(255, 51, 51, 0.2); 
            color: #ff3333; 
            border-color: #ff3333; 
            box-shadow: 0 0 15px rgba(255, 51, 51, 0.5); 
            transform: scale(1.05); 
        }

        
        .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }

        
        .share-card {
            background: rgba(10, 10, 15, 0.6);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 243, 255, 0.5); 
            box-shadow: 0 0 20px rgba(0, 243, 255, 0.15); 
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 40px;
        }

        h2 { 
            font-family: 'Exo 2', sans-serif; 
            color: var(--neon-blue); 
            margin-bottom: 20px; 
            font-size: 1.3rem; 
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 0 8px rgba(0, 243, 255, 0.3);
        }

        .input-row { display: flex; gap: 15px; margin-bottom: 15px; }
        
        .dash-input {
            width: 100%;
            padding: 14px;
            background: rgba(0, 0, 0, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.25); 
            color: #fff;
            border-radius: 8px;
            outline: none;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        .dash-input:focus { 
            border-color: var(--neon-blue); 
            box-shadow: 0 0 15px rgba(0, 243, 255, 0.25);
            background: rgba(0, 0, 0, 0.8);
        }

        .share-btn {
            background: var(--neon-blue);
            color: #000;
            border: none;
            padding: 0 30px;
            font-weight: 700;
            font-family: 'Exo 2', sans-serif;
            cursor: pointer;
            border-radius: 8px;
            transition: 0.3s;
            white-space: nowrap;
            font-size: 1rem;
        }
        .share-btn:hover { 
            box-shadow: 0 0 25px var(--neon-blue); 
            transform: translateY(-2px); 
            background-color: #fff;
        }

        
        .search-bar { margin-bottom: 30px; display: flex; gap: 10px; }
        
        .search-btn { 
            background: rgba(255, 255, 255, 0.1); 
            color: #fff; 
            border: 1px solid rgba(255, 255, 255, 0.2); 
            cursor: pointer; 
            padding: 0 25px; 
            border-radius: 8px; 
            transition: 0.3s;
            font-family: 'Exo 2', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .search-btn:hover { background: rgba(255, 255, 255, 0.2); border-color: var(--neon-blue); color: var(--neon-blue); }

        
        .entry-card {
            background: rgba(20, 20, 25, 0.7);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 12px;
            transition: 0.3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5); 
        }
        
        .entry-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px; 
            background: var(--neon-blue);
            opacity: 1; 
            box-shadow: 0 0 20px var(--neon-blue); 
        }

        .entry-card:hover { 
            transform: translateY(-3px); 
            background: rgba(30, 30, 35, 0.9); 
            box-shadow: 0 10px 40px rgba(0, 243, 255, 0.1); 
            border-color: rgba(0, 243, 255, 0.3);
        }

        .entry-header {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .username { 
            color: var(--neon-blue); 
            font-weight: 700; 
            font-family: 'Exo 2', sans-serif; 
            letter-spacing: 0.5px;
            text-shadow: 0 0 5px rgba(0, 243, 255, 0.4);
        }
        
        .entry-body { 
            margin-bottom: 15px; 
            line-height: 1.6; 
            color: rgba(255, 255, 255, 0.95); 
            font-weight: 300;
        }
        
        .entry-link a {
            color: var(--neon-blue);
            text-decoration: none;
            border-bottom: 1px solid transparent;
            font-size: 0.9rem;
            transition: 0.3s;
            background: rgba(0, 243, 255, 0.15); 
            padding: 5px 10px;
            border-radius: 4px;
        }
        .entry-link a:hover { 
            background: rgba(0, 243, 255, 0.3);
            box-shadow: 0 0 15px rgba(0, 243, 255, 0.4); 
            color: #fff;
        }

        
        .delete-btn {
            color: rgba(255, 51, 51, 0.7);
            border: 1px solid rgba(255, 51, 51, 0.4);
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            text-decoration: none;
            transition: 0.3s;
            margin-left: 10px;
            font-family: 'Exo 2', sans-serif;
            font-weight: 600;
        }
        .delete-btn:hover {
            background: rgba(255, 51, 51, 0.2);
            color: #ff3333;
            border-color: #ff3333;
            box-shadow: 0 0 8px rgba(255, 51, 51, 0.5);
        }

        @media (max-width: 600px) {
            .input-row { flex-direction: column; }
            .share-btn { width: 100%; padding: 15px; }
            header { padding: 15px 20px; }
        }
    </style>
</head>
<body>

    <header>
        <img src="assets/ceslogotrans.png" alt="Logo" class="logo">
        <div class="user-info">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="admin.php" style="color: #00f3ff; border: 1px solid #00f3ff; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; margin-right: 15px; transition:0.3s;">
                    ⚙️ YÖNETİM
                </a>
            <?php endif; ?>

            <span><?php echo htmlspecialchars($active_user); ?></span> 
            <a href="?logout=true" class="logout-btn">ÇIKIŞ</a>
        </div>
    </header>

    <div class="container">
        
        <div class="share-card">
            <h2>KAYNAK PAYLAŞ</h2>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="input-row">
                    <input type="text" name="description" class="dash-input" placeholder="Konu / Açıklama (Örn: Web Pentesting için Harika Kaynak)" required>
                </div>
                <div class="input-row">
                    <input type="text" name="link" class="dash-input" placeholder="Link (https://...)" required>
                    <button type="submit" name="share_entry" class="share-btn">GÖNDER</button>
                </div>
            </form>
        </div>

        <form method="GET" class="search-bar">
            <input type="text" name="q" class="dash-input" placeholder="Entry'lerde ara..." value="<?php echo htmlspecialchars($search_query); ?>">
            <button type="submit" class="search-btn">ARA</button>
            <?php if(!empty($search_query)): ?>
                <a href="dashboard.php" style="padding: 12px; color: rgba(255, 255, 255, 0.5); text-decoration: none; display: flex; align-items: center;">✕</a>
            <?php endif; ?>
        </form>

        <div class="feed">
            <?php if (count($entries) > 0): ?>
                <?php foreach ($entries as $entry): ?>
                    <div class="entry-card">
                        <div class="entry-header">
                            <div>
                                <span class="username">@<?php echo htmlspecialchars($entry['username']); ?></span>
                                <?php if ($entry['username'] === $active_user): ?>
                                    <a href="?del=<?php echo $entry['id']; ?>&token=<?php echo $_SESSION['csrf_token']; ?>" class="delete-btn" onclick="return confirm('Emin misin?');">SİL</a>
                                <?php endif; ?>
                            </div>
                            <span style="font-size: 0.75rem;"><?php echo date("d.m.Y H:i", strtotime($entry['created_at'])); ?></span>
                        </div>
                        <div class="entry-body">
                            <?php echo htmlspecialchars($entry['description']); ?>
                        </div>
                        <div class="entry-link">
                            <a href="<?php echo htmlspecialchars($entry['link']); ?>" target="_blank">
                                🔗 <?php echo htmlspecialchars(substr($entry['link'], 0, 60)) . (strlen($entry['link']) > 60 ? '...' : ''); ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; color: rgba(255, 255, 255, 0.4); margin-top: 50px;">
                    <p>Henüz hiç paylaşım yapılmamış veya aradığınız kriterde sonuç yok.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>