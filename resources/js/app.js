import './bootstrap';

document.addEventListener("DOMContentLoaded", function () {
    let priceInput = document.getElementById("price");

    priceInput.addEventListener("input", function (e) {
        let value = this.value.replace(/\D/g, "");
        if (value) {
            this.value = new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(value);
        }
    });
});
