const timeButtons = document.querySelectorAll('.time-button');
const bookingDate = document.getElementById('bookingDate');
const totalCost = document.getElementById('totalCost');
const bookNowButton = document.getElementById('bookNowButton');
const popup = document.getElementById('popup');
const closePopup = document.getElementById('closePopup');
let selectedTimes = [];

timeButtons.forEach(button => {
    button.addEventListener('click', () => {
        const time = button.getAttribute('data-time');
        
        if (selectedTimes.includes(time)) {
            selectedTimes = selectedTimes.filter(t => t !== time);
            button.classList.remove('selected');
        } else {
            selectedTimes.push(time);
            button.classList.add('selected');
        }

        updateBookingDetails();
    });
});

document.getElementById('date').addEventListener('change', updateBookingDetails);

function updateBookingDetails() {
    const dateInput = document.getElementById('date').value;
    if (!dateInput) {
        bookingDate.innerText = 'Select a date and time';
        totalCost.innerText = '₱0.00';
        return;
    }

    const date = new Date(dateInput);
    const options = { month: 'long', day: 'numeric', year: 'numeric' };
    const formattedDate = date.toLocaleDateString('en-US', options);
    
    const selectedTimeText = selectedTimes.join(', ');
    const hours = selectedTimes.length;
    const cost = hours * 200;

    bookingDate.innerText = `Sports Complex\n${formattedDate} at ${selectedTimeText}\n${hours} hr${hours > 1 ? 's' : ''}`;
    totalCost.innerText = `₱${cost.toFixed(2)}`;
}

bookNowButton.addEventListener('click', () => {
    const date = document.getElementById('date').value;
    const name = document.getElementById('name').value;
    const mobile = document.getElementById('mobile').value;
    const email = document.getElementById('email').value;
    const reference = document.getElementById('reference').value;

    if (!date || selectedTimes.length === 0 || !name || !mobile || !email || !reference) {
        alert('Please fill out all the fields before booking.');
    } else {
        popup.style.display = 'flex';
    }
});

closePopup.addEventListener('click', () => {
    popup.style.display = 'none';
});
