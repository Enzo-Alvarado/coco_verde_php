const btnRestar = document.getElementById("btnRestar");
const btnSumar = document.getElementById("btnSumar");
const cantidad = document.getElementById("cantidad");

// usamos parseInt para convertir el valor del input a número
let contador = parseInt(cantidad.value);

btnRestar.addEventListener("click", () => {
  if (contador > parseInt(cantidad.min)) {
    // Verifica el mínimo permitido
    contador--;
    cantidad.value = contador; // Actualiza el valor del input
  }
});

btnSumar.addEventListener("click", () => {
  if (contador < parseInt(cantidad.max)) contador++;
  cantidad.value = contador; // Actualiza el valor del input
});
