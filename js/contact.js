const form = document.querySelector(".form");

form.addEventListener("submit", function(event) {
    event.preventDefault();

    const name = document.getElementById("name").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const inquiry = document.getElementById("info-advise").value.trim();

    if (name === "") {
        alert("Vui lòng nhập họ và tên");
        return;
    }

    const phoneRegex = /^(03|08|09)\d{8}$/;

    if (!phoneRegex.test(phone)) {
        alert("Số điện thoại phải có 10 số và bắt đầu bằng 0");
        return;
    }

    if (inquiry === "") {
        alert("Vui lòng nhập thông tin cần tư vấn");
        return;
    }

    form.submit();
});

form.addEventListener("keydown", function(event) {
    if (event.key !== "Enter" || event.shiftKey) {
        return;
    }

    event.preventDefault();
    form.requestSubmit();
});

if (window.location.search !== "") {
    window.history.replaceState({}, document.title, window.location.pathname);
}

