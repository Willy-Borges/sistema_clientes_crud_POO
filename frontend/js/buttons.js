function limparFormulario() {

    // Se há alterações não salvas → modal
    if (typeof isFormDirty === "function" && isFormDirty()) {
        if (typeof showUnsavedModal === "function") {
            showUnsavedModal("limpar");
            return;
        }
    }

    forceClear();
}

function estadoInicial() {
    const btnSalvar = document.getElementById("btnSalvar");
    const btnEditar = document.getElementById("btnEditar");
    const btnExcluir = document.getElementById("btnExcluir");
    const codigo = document.getElementById("codigo");

    btnSalvar.disabled = false;
    btnEditar.disabled = true;
    btnExcluir.disabled = true;

    // Código liberado para pesquisa
    codigo.disabled = false;
    codigo.style.background = "#fff";
    codigo.style.cursor = "text";
}

function estadoEdicao() {
    const btnSalvar = document.getElementById("btnSalvar");
    const btnEditar = document.getElementById("btnEditar");
    const btnExcluir = document.getElementById("btnExcluir");
    const codigo = document.getElementById("codigo");

    btnSalvar.disabled = true;
    btnEditar.disabled = false;
    btnExcluir.disabled = false;

    // Código travado na edição
    codigo.disabled = true;
    codigo.style.background = "#cfcfcf";
    codigo.style.cursor = "not-allowed";
}


