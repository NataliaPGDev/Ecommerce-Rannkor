
document.querySelectorAll('.productos--carrusel').forEach(carrusel => {
    const slidesContainer = carrusel.querySelector('.carrusel__slides');
    const slides = carrusel.querySelectorAll('.slide');
    const prevBtn = carrusel.querySelector('.carrusel__boton--prev');
    const nextBtn = carrusel.querySelector('.carrusel__boton--next');

    let index = 0;
    let startX = 0;
    let isDragging = false;

    // Mostrar slide
    function showSlide(i) {
        if(i < 0) i = slides.length - 1;
        if(i >= slides.length) i = 0;
        index = i;
        slidesContainer.style.transform = `translateX(${-index * 100}%)`;
    }

    // Botones
    prevBtn.addEventListener('click', () => showSlide(index - 1));
    nextBtn.addEventListener('click', () => showSlide(index + 1));

    // Touch swipe
    slidesContainer.addEventListener('touchstart', e => {
        startX = e.touches[0].clientX;
        isDragging = true;
    });

    slidesContainer.addEventListener('touchmove', e => {
        if(!isDragging) return;
    });

    slidesContainer.addEventListener('touchend', e => {
        isDragging = false;
        const endX = e.changedTouches[0].clientX;
        const diff = endX - startX;
        if(diff > 50) showSlide(index - 1); // swipe derecha
        if(diff < -50) showSlide(index + 1); // swipe izquierda
    });
});
