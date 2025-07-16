const slides = document.getElementById('slides');
const botoes = document.querySelectorAll('.botoes button');
const tituloPrincipal = document.getElementById('titulo-principal');
const textoPrincipal = document.getElementById('texto-principal');

const legendasTopo = [{
titulo: '👥 Quem Somos',
texto: 'Somos uma equipe apaixonada por transformar vidas por meio da <strong>educação financeira</strong>. Acreditamos que o conhecimento empodera e pode tirar pessoas do ciclo da dívida, oferecendo novas possibilidades para o futuro.'
},
{
titulo: '💼 O que Fazemos',
texto: 'Oferecemos oficinas, conteúdos educativos e consultorias para capacitar pessoas em situação de vulnerabilidade.'
},
{
titulo: '🌱 Nossas Intenções',
texto: 'Construir uma rede de apoio que promova estabilidade e independência financeira para famílias e comunidades.'
}
];
let slideAtual = 0;

function animarElemento(el) {
el.classList.remove('animar');
void el.offsetWidth; // Reinicia a animação
el.classList.add('animar');
}

function mudarSlide(index) {
slideAtual = index;
slides.style.transform = `translateX(-${ index * (100 / legendasTopo.length) }%)`;
botoes.forEach(btn => btn.classList.remove('ativo'));
botoes[index].classList.add('ativo');

tituloPrincipal.textContent = legendasTopo[index].titulo;
textoPrincipal.innerHTML = legendasTopo[index].texto;

animarElemento(tituloPrincipal);
animarElemento(textoPrincipal);
}

setInterval(() => {
slideAtual = (slideAtual + 1) % legendasTopo.length;
mudarSlide(slideAtual);
}, 10000);