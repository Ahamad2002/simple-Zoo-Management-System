// Image Slider/Carousel for the Hero Section
let currentSlide = 0;
const slides = document.querySelectorAll('.hero .slide');
const totalSlides = slides.length;

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === index) {
            slide.classList.add('active');
        }
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    showSlide(currentSlide);
}

// Initialize the first slide
showSlide(currentSlide);

// Automatically change slides every 5 seconds
setInterval(nextSlide, 5000);

// Carousel controls
document.querySelector('.carousel-controls .next').addEventListener('click', nextSlide);
document.querySelector('.carousel-controls .prev').addEventListener('click', prevSlide);
