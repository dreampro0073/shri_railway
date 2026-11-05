document.addEventListener('DOMContentLoaded', function () {
    const manual = document.querySelector('body.sitting-manual');

    if (!manual) {
        return;
    }

    const printButton = manual.querySelector('#printManualBtn');

    if (printButton) {
        printButton.addEventListener('click', function () {
            window.print();
        });
    }
});
