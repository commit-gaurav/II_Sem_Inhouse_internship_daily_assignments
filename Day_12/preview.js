// Shared client-side image preview for register.php and edit_student.php.
// Looks for a file input #photoInput and an <img id="photoPreview"> on the
// page; does nothing if either is missing, so it's safe to load on every page.
document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("photoInput");
    const preview = document.getElementById("photoPreview");

    if (!input || !preview) return;

    input.addEventListener("change", function () {
        const file = input.files[0];

        if (!file) {
            preview.classList.add("d-none");
            preview.src = "";
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove("d-none");
        };
        reader.readAsDataURL(file);
    });
});
