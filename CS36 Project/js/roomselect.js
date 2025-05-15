let roomCount = 0;
                        let totalprice = 0;

                        function RoomSelect(element) {//gets attributes from each room type

                            //Disable all rooms after a selection
                            const allRooms = document.querySelectorAll('[data-room]');
                            allRooms.forEach(room => {
                                room.onclick = null;  //Disable clicking
                            });

                            element.setAttribute("data-selected", "true");
                            const imgSrc = element.getAttribute("data-img");
                            const roomDesc = element.getAttribute("data-room");   
                            const roomType = element.getAttribute("data-roomType");                       
                            let price = Number(element.getAttribute("data-price"));

                            roomCount++; //Increases room count on click
                            totalprice += price;//adds price

                            const container = document.getElementById("selected-rooms");

                            const roomDiv = document.createElement("div");//creates a new div for the selected rooms section
                            roomDiv.className = "row mb-3 align-items-center";
                            roomDiv.id = `room-${roomCount}`;//each selected room has an id room-{1,2,3...}
                            roomDiv.setAttribute("data-price", price); //sets price attribute for subtraction
                            roomDiv.innerHTML = `
                                <div class="col-md-3">
                                <img src="${imgSrc}" class="img-fluid">
                                </div>
                                <div class="col-md-3">
                                <p>${roomDesc}</p>
                                </div>
                                <div class="col-md-3">
                                <p>PHP ${price}/per night</p>
                                </div>
                                <div class="col-md-3">
                                <button class="btn btn-danger" onclick="removeRoom('room-${roomCount}')">Remove</button>
                                </div>
                                <input type="hidden" name="roomType" value="${roomType}">
                            `;

                            container.appendChild(roomDiv);
                            document.getElementById("cart").textContent = `Your Cart: ${roomCount} Item(s)`;//Updates cart items & price
                            document.getElementById("total-price").textContent = `Total: PHP${totalprice}`;      
                            
                            //Enable the "Confirm" button if a room is selected
                            if (roomCount > 0) {
                                document.getElementById("confirm").disabled = false;  // Enable the button
                            }
                            
                        }

                        function removeRoom(roomId) {// removes the selected room when Remove button is clicked
                            const roomDiv = document.getElementById(roomId);
                            let price = Number(roomDiv.getAttribute("data-price"));// Decrease room count and price depending on removed room type
                            roomCount--;

                            if (roomDiv) {
                                totalprice -= price;

                                document.getElementById("cart").textContent = `Your Cart: ${roomCount} Item(s)`;
                                document.getElementById("total-price").textContent = `Total: PHP${totalprice}`;

                                roomDiv.remove();
                                
                                //Re-enable all rooms by adding the onclick back
                                const allRooms = document.querySelectorAll('[data-room]');
                                allRooms.forEach(room => {
                                    room.onclick = () => RoomSelect(room); //Add the click event back
                                });

                                // If no rooms are selected, disable the "Confirm" button again
                                if (roomCount === 0) {
                                    document.getElementById("confirm").disabled = true;  // Disable the button
                                }
                            }
                        }

                        function handleConfirmBooking(event) {
                            // Prevent the default form submission
                            event.preventDefault();

                            // Show the modal
                            const bookingModal = new bootstrap.Modal(document.getElementById('bookingSuccessful'));
                            bookingModal.show();

                            // Optionally, you can submit the form after the modal is displayed
                            // Uncomment the following line if you want to submit the form after showing the modal
                            // document.querySelector('form').submit();

                            // Return false to prevent the default form submission
                            return false;
                        }

                        function redirectToHome() {
                            window.location.href = "HomePage.php"; // Redirects to HomePage.php
                        }