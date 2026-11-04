// Validacao do formulario de produtos no navegador.
// Roda antes de enviar para o PHP, para avisar o usuario mais rapido.

var form = document.getElementById("formProduto");

if (form) {
  var ehEdicao = form.getAttribute("data-edicao") === "1";

  form.addEventListener("submit", function (evento) {
    limparErros();
    var valido = true;

    var nome = document.getElementById("nome");
    var marca = document.getElementById("marca");
    var senha = document.getElementById("senha");
    var valor = document.getElementById("valor");

    if (nome.value.trim() === "") {
      mostrarErro(nome, "Digite o nome do produto.");
      valido = false;
    }

    if (marca.value.trim() === "") {
      mostrarErro(marca, "Digite a marca do produto.");
      valido = false;
    }

    // na edicao a senha pode ficar vazia
    if (!ehEdicao && senha.value.length < 6) {
      mostrarErro(senha, "A senha precisa ter pelo menos 6 caracteres.");
      valido = false;
    }
    if (ehEdicao && senha.value !== "" && senha.value.length < 6) {
      mostrarErro(senha, "A senha precisa ter pelo menos 6 caracteres.");
      valido = false;
    }

    var numero = parseFloat(valor.value.replace(",", "."));
    if (isNaN(numero) || numero < 0) {
      mostrarErro(valor, "Digite um valor numerico valido.");
      valido = false;
    }

    // se tiver algum erro, nao envia o formulario
    if (!valido) {
      evento.preventDefault();
    }
  });
}

function mostrarErro(campo, mensagem) {
  campo.classList.add("is-invalid");
  var aviso = document.createElement("div");
  aviso.className = "erro-campo";
  aviso.textContent = mensagem;
  campo.parentNode.appendChild(aviso);
}

function limparErros() {
  var avisos = document.querySelectorAll(".erro-campo");
  avisos.forEach(function (a) {
    a.remove();
  });
  var campos = document.querySelectorAll(".is-invalid");
  campos.forEach(function (c) {
    c.classList.remove("is-invalid");
  });
}
