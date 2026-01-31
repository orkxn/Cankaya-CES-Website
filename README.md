# CES Çankaya Web Platformu
Çankaya CES(Computer Engineering Society) olarak topluluğumuzun kendini daha da iyi duyurabilmesi için bu websiteyi oluşturma kararı aldık. Website içerisinde hakkında, iletişim, haberler, ve giriş sayfası bulunmaktadır. Okulumzun öğrencileri siteye kayıt olduktan sonra yöneticiler tarafından onaylanarak kullanıcı paneline erişim sağlarlar. Bu panelde öğrenciler ders, kişisel gelişim, teknoloji ve daha bir çok alanda yardımcı olabilecek kaynakları birbirleriyle paylaşıp erişim sağlayabilirler. Ayrıca ana sayfamızda aşağıda okula yeni gelen arkadaşlarımız için okulun bir kuş bakışı çizimi de bulunmaktadır.

## Güvenlik Standartları
Bu proje geliştirilirken OWASP Top 10 zafiyetlerine karşı aktif koruma yöntemleri uygulanmıştır:
- SQL Injection Koruması: Veritabanı etkileşimlerinde PDO Prepared Statements kullanılarak sorgu güvenliği sağlanmıştır.

- CSRF (Cross-Site Request Forgery): "Synchronizer Token Pattern" ile oturum bazlı benzersiz anahtarlar kullanılarak sahte isteklerin önüne geçilmiştir.

- XSS Prevention: Kullanıcıdan gelen tüm girdiler htmlspecialchars() filtresinden geçirilerek güvenli hale getirilmiştir.

- Güvenli Kimlik Doğrulama: Şifreler ve güvenlik cevapları password_hash ile güçlü algoritmalarla maskelenerek saklanmaktadır.

- Session Security: session_regenerate_id kullanımıyla oturum sabitleme saldırıları engellenmiştir.

## Kullanılan Teknolojiler
- Frontend: HTML5, CSS3 (Custom Grid System), Google Fonts (Exo 2, Inter).

- Backend: PHP 8.x.

- Database: MySQL (PDO Interface).

## İletişim & Geri Bildirim

Proje hakkında sorularınız veya güvenlik üzerine tartışmak için mail ya da Discord üzerinden ulaşabilirsiniz:
```bash
E-mail: ork.74@hotmail.com
Discord: orkun33
```

