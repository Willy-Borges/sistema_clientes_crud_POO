document.getElementById("data_nascimento").addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, "");
    if (value.length > 8) {
        value = value.substring(0, 8);
    }

    let formatted = "";

    if (value.length > 0) {
        formatted = value.substring(0, 2);
    }
    if (value.length > 2) {
        formatted += "/" + value.substring(2, 4);
    }
    if (value.length > 4) {
        formatted += "/" + value.substring(4, 8);
    }

    e.target.value = formatted;
});


// Máscara: CPF
document.getElementById("cpf").addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, "");

    if (value.length > 11) value = value.substring(0, 11);

    let formatted = "";

    if (value.length > 0) {
        formatted = value.substring(0, 3);
    }
    if (value.length > 3) {
        formatted += "." + value.substring(3, 6);
    }
    if (value.length > 6) {
        formatted += "." + value.substring(6, 9);
    }
    if (value.length > 9) {
        formatted += "-" + value.substring(9, 11);
    }

    e.target.value = formatted;
});


// Máscara: CEP
document.getElementById("cep").addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, "");

    if (value.length > 8) value = value.substring(0, 8);

    let formatted = "";

    if (value.length > 0) {
        formatted = value.substring(0, 5);
    }
    if (value.length > 5) {
        formatted += "-" + value.substring(5, 8);
    }

    e.target.value = formatted;
});


// Máscara: Celular
document.getElementById("cel").addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, "");

    if (value.length > 11) value = value.substring(0, 11);

    let formatted = "";

    if (value.length > 0) {
        formatted = "(" + value.substring(0, 2);
    }
    if (value.length > 2) {
        formatted += ") " + value.substring(2, 7);
    }
    if (value.length > 7) {
        formatted += "-" + value.substring(7, 11);
    }

    e.target.value = formatted;
});

