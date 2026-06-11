document.addEventListener("DOMContentLoaded", function () {
  const note = document.getElementById("resolution-note");

  if (!note) return;

  note.addEventListener("input", function () {
    const hasText = this.value.trim() !== "";
    document.getElementById("btn-release").disabled = !hasText;
    document.getElementById("btn-refund").disabled = !hasText;
  });
});
