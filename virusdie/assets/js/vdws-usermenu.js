const userLink = document.getElementById("vdUserMenuLink");
const userMenu = document.getElementById("vdUserMenu");

if (userLink) {
  userLink.addEventListener("click", () => {
    userMenu.classList.add("--show");
  });
  
  document.addEventListener("click", function (event) {
    var isClickInsideMenu = userLink.contains(event.target);
    var isClickInsideLink = userMenu.contains(event.target);
    if (!isClickInsideMenu && !isClickInsideLink) {
      userMenu.classList.remove("--show");
    }
  });
}


