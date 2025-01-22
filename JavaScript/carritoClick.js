const carritoIcono = document.getElementById("carritoIcono");
const carrito = document.querySelector(".carrito");
const x = document.getElementById("x");

carritoIcono.addEventListener("click", () => {
  carrito.style.display = "block";
});

x.addEventListener("click", () => {
  carrito.style.display = "none";
});
