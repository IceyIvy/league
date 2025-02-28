function validateForm() {
    const requiredFields = [
        document.getElementById("sport"),
        document.getElementById("team-name"),
        document.getElementById("division"),
        document.getElementById("first-name"),
        document.getElementById("middle-name"),
        document.getElementById("last-name"),
        document.getElementById("birth-date"),
        document.getElementById("age"),
        document.getElementById("sex"),
        document.getElementById("sitio"),
        document.getElementById("mobile-number"),
        document.getElementById("email-address"),
        document.getElementById("photo"),
        document.getElementById("nso"),
        document.getElementById("voter-cert")
    ];

    const allFilled = requiredFields.every(field => {
        if (field.tagName === "INPUT" && field.type === "file") {
            return field.files.length > 0;
        }
        return field.value.trim() !== "";
    });

    if (allFilled) {
        showPopup();
    } else {
        alert("Please fill out all fields before submitting.");
    }
}

function showPopup() {
    document.getElementById("popup").style.display = "flex";
}

function closePopup() {
    document.getElementById("popup").style.display = "none";
}

function calculateAge() {
    const birthDateInput = document.getElementById("birth-date").value;
    if (birthDateInput) {
        const [year, month, day] = birthDateInput.split('-');
        const birthDateObj = new Date(year, month - 1, day);
        const today = new Date();
        let age = today.getFullYear() - birthDateObj.getFullYear();
        const monthDiff = today.getMonth() - birthDateObj.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDateObj.getDate())) {
            age--;
        }
        document.getElementById("age").value = age;
    } else {
        document.getElementById("age").value = '';
    }
}

document.getElementById("sport").addEventListener("change", function() {
    const divisionSelect = document.getElementById("division");
    const sexSelect = document.getElementById("sex");

    divisionSelect.innerHTML = "";
    sexSelect.innerHTML = "";

    const sportValue = this.value;

    let divisionOptions = [];
    let sexOptions = [];

    if (sportValue === "Basketball") {
        divisionOptions = [
            { value: "", text: "Please Select", disabled: true },
            { value: "Mosquito", text: "Mosquito" },
            { value: "Midget", text: "Midget" },
            { value: "Aspirant", text: "Aspirant" },
            { value: "Junior", text: "Junior" },
            { value: "Equalizer", text: "Equalizer" }
        ];
        sexOptions = [
            { value: "", text: "Please Select", disabled: true },
            { value: "Male", text: "Male" }
        ];
    } else if (sportValue === "Volleyball") {
        divisionOptions = [
            { value: "", text: "Please Select", disabled: true },
            { value: "Women's", text: "Women's" },
            { value: "Men's", text: "Men's" }
        ];
        sexOptions = [
            { value: "", text: "Please Select", disabled: true },
            { value: "Male", text: "Male" },
            { value: "Female", text: "Female" }
        ];
    }

    function populateSelect(selectElement, options) {
        options.forEach(option => {
            const newOption = document.createElement("option");
            newOption.value = option.value;
            newOption.textContent = option.text;
            if (option.disabled) {
                newOption.disabled = true;
            }
            selectElement.appendChild(newOption);
        });

        selectElement.selectedIndex = 0;
    }

    populateSelect(divisionSelect, divisionOptions);
    populateSelect(sexSelect, sexOptions);
});

function updateDivision() {
    const sport = document.getElementById('sport').value;
    const division = document.getElementById('division');

    if (sport === 'Basketball') {
      division.value = "Men's";
      division.disabled = true;
    } else {
      division.disabled = false;
      division.value = '';
    }
  }