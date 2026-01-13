<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Carrossel de Produtos</title>
<style>

.carrossel-container {
    background-color: white;
    width: 600px; 
    height: 260px;
    border-radius: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    overflow: hidden;
    margin: 50px auto;
}


.carrossel {
    display: flex;
    transition: transform 0.5s ease-in-out;
    
}


.slide {
    flex: 0 0 600px;   
    height: 260px;
    display: flex;
    justify-content: center; 
    align-items: center;     
    box-sizing: border-box;
    padding: 10px;
}

.slide a {
    display: block;
    width: 100%;
    height: 100%;
    text-align: center;
}

.slide img {
    max-width: 100%;
    max-height: 200px;
    width: auto;
    height: auto;
    object-fit: contain;
    display: inline-block;
    margin: 0 auto;
}

.indicadores {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
}

.indicadores .dot {
    width: 12px;
    height: 12px;
    background-color: #000;
    border-radius: 50%;
    opacity: 0.3;
    cursor: pointer;
    transition: 0.3s;
}

.indicadores .dot.ativo {
    opacity: 1;
}
</style>
</head>
<body>

<div class="carrossel-container">

    <div class="carrossel" id="carrossel">

        <div class="slide">
            <a href="http://localhost/academia/ver_produto.php?idProduto=2">
                <img src="img/Creatina300.webp" alt="Creatina">
            </a>
        </div>

        <div class="slide">
            <a href="http://localhost/academia/ver_produto.php?idProduto=3">
                <img src="img/Pre-treino350.jpg" alt="Pre-treino">
            </a>
        </div>

        <div class="slide">
            <a href="http://localhost/academia/ver_produto.php?idProduto=1">
                <img src="img/Whey_Protein_Nutri900.png" alt="Whey Protein">
            </a>
        </div>

    </div>

    <div class="indicadores" id="indicadores"></div>
</div>

<script>
const carrossel = document.getElementById("carrossel");
const slides = carrossel.querySelectorAll(".slide");
const total = slides.length;
let indice = 0;

const indicadores = document.getElementById("indicadores");

// cria dots
for (let i = 0; i < 2; i++) {
    const dot = document.createElement("div");
    dot.classList.add("dot");
    if (i === 0) dot.classList.add("ativo");
    dot.addEventListener("click", () => irParaImagem(i));
    indicadores.appendChild(dot);
}

function irParaImagem(i) {
    indice = i;
    atualizarCarrossel();
}

function atualizarCarrossel() {
    carrossel.style.transform = `translateX(${-indice * 600}px)`;
    document.querySelectorAll(".dot").forEach((d, i) => {
        d.classList.toggle("ativo", i === indice);
    });
}

/* (opcional) rotação automática se quiser:
setInterval(() => {
  indice = (indice + 1) % total;
  atualizarCarrossel();
}, 4000);
*/
</script>

</body>
</html>
