const botaoComprar = document.getElementById('botaoComprar');
const mensagemAdmin = document.getElementById('mensagemAdmin');

const usuarioEhAdmin = false;

function aplicarRegraCompra() {
  if (usuarioEhAdmin) {
    botaoComprar.style.display = 'none';
    mensagemAdmin.style.display = 'block';
  }
}

if (botaoComprar) {
  botaoComprar.addEventListener('click', function () {
    alert('Redirecionar para a tela de compra / checkout.');
  });
}

aplicarRegraCompra();