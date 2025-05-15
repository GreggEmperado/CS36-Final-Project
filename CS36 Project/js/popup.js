// Select all "Read More" buttons and the popup box
const readMoreButtons = document.querySelectorAll('.readMore');
const popup = document.querySelector('.popup-box');
const popupBody = document.querySelector('.popup-body');
const closeButton = document.querySelector('.close-btn');
const closeX = document.querySelector('.close-x');
const popupTitle = document.querySelector('.popup-title');

// Add click event listeners to each "Read More" button
readMoreButtons.forEach((button) => {
    button.addEventListener('click', (event) => {
        // Find content for the popup box
        const room = button.closest('.room');
        const roomHeader = room.querySelector('.room-header').innerHTML;
        const roomContent = room.querySelector('.read-more-content').innerHTML;

        // Put the content in the popup box
        popupTitle.innerHTML = roomHeader;
        popupBody.innerHTML = roomContent;

        // Show the popup box
        popup.classList.add('open');
        event.stopPropagation(); // Prevent event bubbling
    });
});

// Add click event listener to the "Close" button
if (closeButton) {
    closeButton.addEventListener('click', () => {
        popup.classList.remove('open');
    });
}

// Add click event listener to the "X" button
if (closeX) {
    closeX.addEventListener('click', () => {
        popup.classList.remove('open');
    });
}