const slides = document.querySelectorAll('.slide');
const nextButton = document.querySelector('.next');
const prevButton = document.querySelector('.prev');
const dots = document.querySelectorAll('.dot'); // Add this line
let currentSlide = 0;
let slideInterval;

function showSlide(index) {
    slides.forEach((slide, i) => {
        if (i === index) {
            slide.classList.add('active');
            slide.classList.remove('inactive');
        } else {
            slide.classList.remove('active');
            slide.classList.add('inactive');
        }
    });
    // Update dots
    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
}

dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
        currentSlide = i;
        showSlide(currentSlide);
        resetInterval();
    });
});

function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
}

function prevSlide() {
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
}

nextButton.addEventListener('click', () => {
    nextSlide();
    resetInterval();
});

prevButton.addEventListener('click', () => {
    prevSlide();
    resetInterval();
});

// Auto-slide every 5 seconds
function startInterval() {
    slideInterval = setInterval(nextSlide, 5000);
}

function resetInterval() {
    clearInterval(slideInterval);
    startInterval();
}

// Initialize the slideshow
showSlide(currentSlide);
startInterval();