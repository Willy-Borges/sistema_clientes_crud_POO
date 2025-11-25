document.getElementById("btnPesquisar").onclick = function () {

    let fd = new FormData();
    fd.append("codigo", document.getElementById("codigo").value.trim());
    fd.append("nome",   document.getElementById("nome").value.trim());
    fd.append("cpf",    document.getElementById("cpf").value.trim());
    fd.append("cel",    document.getElementById("cel").value.trim());
    fd.append("email",  document.getElementById("email").value.trim());

    fetch("pesquisar.php", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(ret => {

        if (ret.status === "erro") {
            mostrarPopupNenhum();
            return;
        }

        if (ret.status === "lista") {
            mostrarPopup(ret.clientes);
            return;
        }

        if (ret.status === "ok") {
            preencherFormulario(ret.cliente);
            estadoEdicao();
            document.getElementById("codigo").setAttribute("readonly", true);
        }
    });
};


// -------- POPUP LISTA ---------

function mostrarPopup(lista) {
    const tabela = document.getElementById("tabelaResultados");
    tabela.innerHTML = "";

    lista.forEach(c => {
        let linha = document.createElement("tr");

        linha.innerHTML = `
            <td>${c.id}</td>
            <td>${c.nome}</td>
            <td>${c.cpf}</td>
        `;

        linha.onclick = () => selecionarCliente(c.id);

        tabela.appendChild(linha);
    });

    document.getElementById("popupResultados").style.display = "block";
}

function fecharPopup() {
    document.getElementById("popupResultados").style.display = "none";
}

function selecionarCliente(id) {

    let fd = new FormData();
    fd.append("codigo", id);

    fetch("pesquisar.php", {
        method: "POST",
        body: fd
    })
    .then(r => r.json())
    .then(ret => {
        if (ret.status === "ok") {
            preencherFormulario(ret.cliente);
            estadoEdicao();
            document.getElementById("codigo").setAttribute("readonly", true);
            fecharPopup();
        }
    });
}



// -------- POPUP: NENHUM RESULTADO ---------

function mostrarPopupNenhum() {
    document.getElementById("popupNenhum").style.display = "block";
}

function fecharPopupNenhum() {
    document.getElementById("popupNenhum").style.display = "none";
}
