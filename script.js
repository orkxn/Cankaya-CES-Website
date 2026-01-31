document.addEventListener("DOMContentLoaded", () => 
{
  const menuBtn = document.getElementById("menu-btn");
  const navBox = document.getElementById("nav-box");

  if (!menuBtn || !navBox) 
  {
    console.error("tuş ya da navigasyon kutusu yok");
    return;
  }

  // navigasyon kutusunu 'açık' olarak nitelendir
  menuBtn.addEventListener("click", (event) => 
  {
    event.stopPropagation();
    navBox.classList.toggle("open");
  });

  // Menü dışına tıklanırsa menüyü kapat
  document.addEventListener("click", (event) => 
  {
    const clickedInsideNav = navBox.contains(event.target);
    const clickedMenuBtn = menuBtn.contains(event.target);

    // Tıklamanın yerine bak eğer dışarıdaysa menüyü kapat
    if (navBox.classList.contains("open") && !clickedInsideNav && !clickedMenuBtn) 
    {
      navBox.classList.remove("open");
    }
  });
});