<?php
include 'baglan.php';
session_start();


if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Bu alana erişim yetkiniz yok!'); window.location.href='dashboard.php';</script>";
    exit();
}


if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $current_admin_id = $_SESSION['user_id'];
    
    if ($_GET['action'] == 'approve') {
        $stmt = $baglanti->prepare("UPDATE users SET is_approved = 1 WHERE id = ?");
        $stmt->execute([$id]);
    } 
    elseif ($_GET['action'] == 'delete') {
        // Güvenlik: Admin kendi hesabını bu panelden silemesin
        if ($id === $current_admin_id) {
            echo "<script>alert('Kendi yönetici hesabınızı buradan silemezsiniz!'); window.location.href='admin.php';</script>";
            exit();
        }
        $stmt = $baglanti->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
    header("Location: admin.php");
    exit();
}


$stmt_pending = $baglanti->prepare("SELECT * FROM users WHERE is_approved = 0 ORDER BY id DESC");
$stmt_pending->execute();
$pending_users = $stmt_pending->fetchAll();

// 2. Onaylı Üyeler (is_approved = 1)
$stmt_approved = $baglanti->prepare("SELECT * FROM users WHERE is_approved = 1 ORDER BY id DESC");
$stmt_approved->execute();
$approved_users = $stmt_approved->fetchAll();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CES Çankaya | Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --neon-blue: #00f3ff; --neon-red: #ff3333; --neon-green: #33ff33; --bg-color: #050505; --text-white: #ffffff; }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-white);
            font-family: 'Inter', sans-serif;
            background-image: linear-gradient(rgba(0, 243, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(0, 243, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            padding: 40px;
        }

        .container { max-width: 1000px; margin: 0 auto; }

        h1, h2 { font-family: 'Exo 2'; color: var(--neon-blue); text-shadow: 0 0 15px rgba(0,243,255,0.4); }
        h1 { text-align: center; margin-bottom: 40px; }
        h2 { margin-bottom: 20px; font-size: 1.5rem; border-left: 4px solid var(--neon-blue); padding-left: 15px; }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            color: #fff;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 6px;
            transition: 0.3s;
        }
        .back-btn:hover { border-color: var(--neon-blue); color: var(--neon-blue); box-shadow: 0 0 10px var(--neon-blue); }

        .section { margin-bottom: 60px; }

        
        .user-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(10, 10, 15, 0.8);
            border: 1px solid rgba(0, 243, 255, 0.2);
            border-radius: 12px;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        th, td { padding: 15px; text-align: left; border-bottom: 1px solid rgba(255,255,255,0.05); }
        th { background: rgba(0, 243, 255, 0.1); color: var(--neon-blue); font-family: 'Exo 2'; font-weight: 600; }
        tr:hover { background: rgba(255,255,255,0.03); }

        .role-badge { font-size: 0.75rem; padding: 2px 6px; border-radius: 4px; border: 1px solid; }
        .role-admin { border-color: var(--neon-blue); color: var(--neon-blue); }
        .role-user { border-color: #888; color: #888; }

        
        .btn { padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.85rem; margin-right: 5px; display: inline-block; transition: 0.3s; font-family: 'Exo 2'; }
        
        .btn-approve { border: 1px solid var(--neon-green); color: var(--neon-green); }
        .btn-approve:hover { background: var(--neon-green); color: #000; box-shadow: 0 0 15px var(--neon-green); }

        .btn-delete { border: 1px solid var(--neon-red); color: var(--neon-red); }
        .btn-delete:hover { background: var(--neon-red); color: #000; box-shadow: 0 0 15px var(--neon-red); }

        .empty-msg { text-align: center; padding: 30px; color: #555; font-style: italic; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" class="back-btn">← Dashboard'a Dön</a>
    <h1>YÖNETİCİ KONTROL MERKEZİ</h1>

    <div class="section">
        <h2>Onay Bekleyen Başvurular</h2>
        <div style="overflow-x:auto;">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kullanıcı Adı</th>
                        <th>Rol</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($pending_users) > 0): ?>
                        <?php foreach ($pending_users as $user): ?>
                            <tr>
                                <td>#<?php echo $user['id']; ?></td>
                                <td style="color:#fff; font-weight:600;"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><span class="role-badge role-user">user</span></td>
                                <td>
                                    <a href="?action=approve&id=<?php echo $user['id']; ?>" class="btn btn-approve">ONAYLA</a>
                                    <a href="?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-delete" onclick="return confirm('Bu kaydı tamamen silmek istediğine emin misin?');">REDDET</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="empty-msg">Yeni başvuru bulunmuyor.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <h2>Onaylanmış Üyeler</h2>
        <div style="overflow-x:auto;">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kullanıcı Adı</th>
                        <th>Rol</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($approved_users) > 0): ?>
                        <?php foreach ($approved_users as $user): ?>
                            <tr>
                                <td>#<?php echo $user['id']; ?></td>
                                <td style="color:#fff; font-weight:600;"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td>
                                    <span class="role-badge <?php echo ($user['role'] === 'admin') ? 'role-admin' : 'role-user'; ?>">
                                        <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                                        <a href="?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-delete" onclick="return confirm('Bu kullanıcıyı SİLMEK istediğine emin misin? Bu işlem geri alınamaz!');">HESABI SİL</a>
                                    <?php else: ?>
                                        <span style="font-size:0.8rem; color:#555;">(Aktif Oturum)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="empty-msg">Onaylı üye kaydı bulunamadı.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>