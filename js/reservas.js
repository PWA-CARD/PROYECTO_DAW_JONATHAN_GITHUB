document.addEventListener("DOMContentLoaded", () => {
  const txt = document.getElementById("filtroTexto");
  const estado = document.getElementById("filtroEstado");
  const limpiar = document.getElementById("limpiarFiltros");

  const items = Array.from(document.querySelectorAll("li.list-group-item[data-nombre]"));

  function aplicarFiltros() {
    const q = (txt?.value || "").toLowerCase().trim();
    const e = (estado?.value || "").trim();

    items.forEach(li => {
      const nombre = (li.dataset.nombre || "").toLowerCase();
      const entrenador = (li.dataset.entrenador || "").toLowerCase();
      const est = (li.dataset.estado || "");

      const okTexto = !q || nombre.includes(q) || entrenador.includes(q);
      const okEstado = !e || est === e;

      li.style.display = (okTexto && okEstado) ? "" : "none";
    });
  }

  txt?.addEventListener("input", aplicarFiltros);
  estado?.addEventListener("change", aplicarFiltros);

  limpiar?.addEventListener("click", () => {
    if (txt) txt.value = "";
    if (estado) estado.value = "";
    aplicarFiltros();
  });
});
