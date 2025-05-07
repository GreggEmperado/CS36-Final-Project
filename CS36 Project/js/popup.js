// Select all "Read More" buttons and the popup box
const readMoreButtons = document.querySelectorAll('.readMore');
const popup = document.querySelector('.popup-box');
const popupHeader = document.querySelector('.popup-header');
const popupBody = document.querySelector('.popup-body');
const closeButton = document.querySelector('.close-btn');

// Add click event listeners to each "Read More" button
readMoreButtons.forEach((button) => {
    button.addEventListener('click', (event) => {
        //finds content for the popup box
        const room = button.closest('.room');
        const roomContent = room.querySelector('.read-more-content').innerHTML;
        const roomTitle = room.querySelector('.room-header').innerHTML;

        //put the content in the popup box
        popupHeader.innerHTML = roomTitle;
        popupBody.innerHTML = roomContent;
        

        //show the popup box
        popup.classList.add('open');
        event.stopPropagation(); // Prevent event bubbling
    });
});

// Add click event listener to the "Close" button
closeButton.addEventListener('click', () => {
    popup.classList.remove('open'); // Remove the "open" class to hide the popup
});