document.addEventListener("DOMContentLoaded", function () {

    // ===== ELEMENTOS =====
    const form = document.getElementById("formCliente");

    const codigo = document.getElementById("codigo");
    const nome = document.getElementById("nome");
    const cpf = document.getElementById("cpf");
    const data_nascimento = document.getElementById("data_nascimento");
    const profissao = document.getElementById("profissao");
    const logradouro = document.getElementById("logradouro");
    const numero = document.getElementById("numero");
    const bairro = document.getElementById("bairro");
    const cidade = document.getElementById("cidade");
    const cep = document.getElementById("cep");
    const cel = document.getElementById("cel");
    const email = document.getElementById("email");
    const uf = document.getElementById("uf");

    const btnSalvar = document.getElementById("btnSalvar");
    const btnEditar = document.getElementById("btnEditar");
    const btnExcluir = document.getElementById("btnExcluir");
    const btnLimpar = document.getElementById("btnLimpar");
    const btnNovo = document.getElementById("btnNovo");
    const btnPesquisar = document.getElementById("btnPesquisar");

    // ===== SNAPSHOT =====
    let snapshot = null;

    function takeSnapshot() {
        snapshot = {
            nome: nome.value,
            cpf: cpf.value,
            data_nascimento: data_nascimento.value,
            profissao: profissao.value,
            logradouro: logradouro.value,
            numero: numero.value,
            bairro: bairro.value,
            cidade: cidade.value,
            cep: cep.value,
            cel: cel.value,
            email: email.value,
            uf: uf.value
        };
    }

    function isFormDirty() {
        if (!snapshot) return false;

        return (
            nome.value !== snapshot.nome ||
            cpf.value !== snapshot.cpf ||
            data_nascimento.value !== snapshot.data_nascimento ||
            profissao.value !== snapshot.profissao ||
            logradouro.value !== snapshot.logradouro ||
            numero.value !== snapshot.numero ||
            bairro.value !== snapshot.bairro ||
            cidade.value !== snapshot.cidade ||
            cep.value !== snapshot.cep ||
            cel.value !== snapshot.cel ||
            email.value !== snapshot.email ||
            uf.value !== snapshot.uf
        );
    }

    // ===== BLOQUEAR/LIBERAR =====
    function bloquearCampos() {
        const campos = document.querySelectorAll("#formCliente input, #formCliente select");
        campos.forEach(c => {
            c.disabled = true;
            c.style.background = "#cfcfcf";
            c.style.cursor = "not-allowed";
        });

        codigo.disabled = true;
        codigo.style.background = "#cfcfcf";
        codigo.style.cursor = "not-allowed";
    }

    function liberarCampos(modo) {
        const campos = document.querySelectorAll("#formCliente input, #formCliente select");

        campos.forEach(c => {
            c.disabled = false;
            c.style.background = "#fff";
            c.style.cursor = "text";
        });

        // EDITAR → mantém código bloqueado
        if (modo === "editar") {
            codigo.disabled = true;
            codigo.style.background = "#cfcfcf";
            codigo.style.cursor = "not-allowed";
        }

        // LIMPAR → libera código
        if (modo === "limpar") {
            codigo.disabled = false;
            codigo.style.background = "#fff";
            codigo.style.cursor = "text";
            codigo.focus();
        }

        // bloqueia código (ID auto-increment)
        if (modo === "novo") {
            codigo.disabled = true;
            codigo.style.background = "#cfcfcf";
            codigo.style.cursor = "not-allowed";
            nome.focus();
        }
    }

    
    window.preencherFormulario = function (c) {
        codigo.value = c.id ?? "";
        nome.value = c.nome ?? "";
        cpf.value = c.cpf ?? "";

        document.querySelector('input[value="M"]').checked = c.sexo === "M";
        document.querySelector('input[value="F"]').checked = c.sexo === "F";

        if (c.data_nascimento) {
            const p = c.data_nascimento.split("-");
            data_nascimento.value = `${p[2]}/${p[1]}/${p[0]}`;
        }

        profissao.value = c.profissao ?? "";
        logradouro.value = c.logradouro ?? "";
        numero.value = c.numero ?? "";
        bairro.value = c.bairro ?? "";
        cidade.value = c.cidade ?? "";
        cep.value = c.cep ?? "";
        cel.value = c.celular ?? "";
        email.value = c.email ?? "";
        uf.value = c.uf ?? "";

        bloquearCampos();
        estadoEdicao();
        takeSnapshot();
    };

    // ===== forceClear =====
    function forceClear() {
        form.reset();
        liberarCampos("limpar");
        estadoInicial();
        takeSnapshot();
    }

    // ===== SALVAR =====
    function salvarCliente() {
        return new Promise((resolve, reject) => {

            let fd = new FormData();

            fd.append("id", codigo.value.trim());
            fd.append("nome", nome.value.trim());
            fd.append("cpf", cpf.value.trim());
            fd.append("sexo", document.querySelector('input[name="sexo"]:checked')?.value || "");
            fd.append("data_nascimento", data_nascimento.value.trim());
            fd.append("profissao", profissao.value.trim());
            fd.append("logradouro", logradouro.value.trim());
            fd.append("numero", numero.value.trim());
            fd.append("bairro", bairro.value.trim());
            fd.append("cidade", cidade.value.trim());
            fd.append("cep", cep.value.trim());
            fd.append("cel", cel.value.trim());
            fd.append("email", email.value.trim());
            fd.append("uf", uf.value);

            fetch("salvar.php", { method: "POST", body: fd })
                .then(r => r.json())
                .then(ret => {
                    alert(ret.mensagem);

                    if (ret.status === "sucesso") {
                        if (ret.id) codigo.value = ret.id;
                        bloquearCampos();
                        estadoEdicao();
                        takeSnapshot();
                    }

                    resolve(ret);
                })
                .catch(err => {
                    alert("Erro ao salvar.");
                    reject(err);
                });

        });
    }

    
    function editarCliente() {
        liberarCampos("editar");

        estadoInicial(); // libera botões

        // Rebloqueia código cliente
        codigo.disabled = true;
        codigo.style.background = "#cfcfcf";
        codigo.style.cursor = "not-allowed";
    }

    // ===== EXCLUIR =====
    function excluirCliente() {

        if (codigo.value.trim() === "") {
            alert("Nenhum cliente carregado para excluir.");
            return;
        }

        if (!confirm("Tem certeza?")) return;

        let fd = new FormData();
        fd.append("id", codigo.value.trim());

        fetch("excluir.php", { method: "POST", body: fd })
            .then(r => r.json())
            .then(ret => {
                alert(ret.mensagem);
                if (ret.status === "sucesso") forceClear();
            });

    }

    // ===== PESQUISAR =====
    function pesquisarCliente() {

        
        if (
            !codigo.value.trim() &&
            !nome.value.trim() &&
            !cpf.value.trim() &&
            !cel.value.trim() &&
            !email.value.trim()
        ) {
            alert("Digite algo para pesquisar.");
            return;
        }

        let fd = new FormData();
        fd.append("codigo", codigo.value.trim());
        fd.append("nome", nome.value.trim());
        fd.append("cpf", cpf.value.trim());
        fd.append("cel", cel.value.trim());
        fd.append("email", email.value.trim());

        fetch("pesquisar.php", { method: "POST", body: fd })
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
                }
            });
    }

    // ===== MODAL SALVAR / DESC / CANCEL =====
    function showUnsavedModal(action) {
        const modal = document.getElementById("popupUnsaved");
        modal.style.display = "block";

        const btnSave = document.getElementById("unsavedSalvar");
        const btnDiscard = document.getElementById("unsavedDescartar");
        const btnCancel = document.getElementById("unsavedCancelar");

        btnSave.onclick = () => {
            salvarCliente().then(() => {
                modal.style.display = "none";

                if (action === "novo") {
                    forceClear();
                    liberarCampos("novo");
                }

                if (action === "limpar") {
                    forceClear();
                }
            });
        };

        btnDiscard.onclick = () => {
            modal.style.display = "none";

            if (action === "novo") {
                forceClear();
                liberarCampos("novo");
            }

            if (action === "limpar") {
                forceClear();
            }
        };

        btnCancel.onclick = () => modal.style.display = "none";
    }

    
    window.isFormDirty = isFormDirty;
    window.showUnsavedModal = showUnsavedModal;
    window.forceClear = forceClear;

    btnSalvar.addEventListener("click", salvarCliente);
    btnEditar.addEventListener("click", editarCliente);
    btnExcluir.addEventListener("click", excluirCliente);
    btnLimpar.addEventListener("click", limparFormulario);
    btnNovo.addEventListener("click", function () {

        if (isFormDirty()) {
            showUnsavedModal("novo");
            return;
        }

        forceClear();
        liberarCampos("novo");
    });

    btnPesquisar.addEventListener("click", pesquisarCliente);

    // ===== ENTER para pesquisar =====
    form.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault();
            pesquisarCliente();
        }
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Enter" && document.activeElement.id === "codigo") {
            e.preventDefault();
            pesquisarCliente();
        }
    });

    
    forceClear();
});

// ================== LISTAR TODOS ==================

function listarTodos() {

    fetch("listar.php")
        .then(r => r.json())
        .then(ret => {

            if (!ret || ret.status !== "ok") {
                alert(ret.msg || "Erro ao carregar lista.");
                return;
            }

            if (!ret.clientes || ret.clientes.length === 0) {
                alert("Nenhum cliente cadastrado.");
                return;
            }

            const tbody = document.querySelector("#tabelaTodos tbody");
            tbody.innerHTML = "";

            ret.clientes.forEach(c => {
                let tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${c.id}</td>
                    <td>${c.nome}</td>
                    <td>${c.cpf}</td>
                `;

                tr.onclick = () => {
                    fecharListaTodos();

                    let fd = new FormData();
                    fd.append("codigo", c.id);

                    fetch("pesquisar.php", {
                        method: "POST",
                        body: fd
                    })
                    .then(r => r.json())
                    .then(ret2 => {
                        if (ret2.status === "ok") {
                            preencherFormulario(ret2.cliente);
                        }
                    });
                };

                tbody.appendChild(tr);
            });

            document.getElementById("popupListaTodos").style.display = "block";
        });
}





function fecharListaTodos() {
    document.getElementById("popupListaTodos").style.display = "none";
}

// botão
document.getElementById("btnListar").addEventListener("click", listarTodos);

