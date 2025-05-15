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


// MENU SLIDER (shows 3 at a time)
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.menu-card-img-slider .menu-slide');
    const wrapper = document.querySelector('.menu-wrapper-holder');
    const prevBtn = document.querySelector('.menu-prev');
    const nextBtn = document.querySelector('.menu-next');
    const slidesToShow = 3;
    let currentIndex = 0;

    function updateSlider() {
        const slideWidth = slides[0].offsetWidth + 20; // 20px for margin
        wrapper.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
    }

    nextBtn.addEventListener('click', () => {
        if (currentIndex + slidesToShow >= slides.length) {
            // Loop to the start
            currentIndex = 0;
        } else {
            currentIndex += slidesToShow;
        }
        updateSlider();
    });

    prevBtn.addEventListener('click', () => {
        if (currentIndex === 0) {
            // Loop to the last full set
            currentIndex = slides.length - slidesToShow;
        } else {
            currentIndex -= slidesToShow;
        }
        updateSlider();
    });

    window.addEventListener('resize', updateSlider);

    updateSlider();
});