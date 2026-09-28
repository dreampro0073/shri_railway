document.addEventListener('DOMContentLoaded', function () {
    const printButton = document.getElementById('printManualBtn');

    if (printButton) {
        printButton.addEventListener('click', function () {
            window.print();
        });
    }
});
