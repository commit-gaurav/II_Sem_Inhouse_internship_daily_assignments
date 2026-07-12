// Show a live preview of the selected profile photo before the form is submitted
const photoInput = document.getElementById('photo');
const photoPreview = document.getElementById('photoPreview');

photoInput.addEventListener('change', function () {
    const file = this.files[0];

    if (!file) {
        photoPreview.src = '';
        photoPreview.classList.add('d-none');
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        photoPreview.src = e.target.result;
        photoPreview.classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});
