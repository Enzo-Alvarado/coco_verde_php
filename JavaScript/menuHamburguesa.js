const navLinks = document.getElementById("nav-links");
const iconoAbrir = document.getElementById("iconoAbrir");
const iconoCerrar = document.getElementById("iconoCerrar");
const links = document.querySelectorAll(".links");

iconoAbrir.addEventListener("click", () => {
  navLinks.classList.add("mostrar");
  iconoAbrir.style.display = "none";
  iconoCerrar.classList.remove("cerrar");
});

iconoCerrar.addEventListener("click", () => {
  navLinks.classList.remove("mostrar");
  iconoCerrar.classList.add("cerrar");
  iconoAbrir.style.display = "block";
});

links.forEach((link) => {
  link.addEventListener("click", () => {
    navLinks.classList.remove("mostrar");
    iconoCerrar.classList.add("cerrar");
    iconoAbrir.style.display = "block";
  });
});
