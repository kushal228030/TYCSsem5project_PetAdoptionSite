function updateBreedOptions() {
  const petSpecies = document.getElementById("pet_species").value;
  const breedSelect = document.getElementById("breed");

  // Clear current breed options
  breedSelect.innerHTML = '';

  // Define breed options for each species
  const breeds = {
      Dog: ['Labrador', 'Pitbull', 'Bulldog', 'Others'],
      Cat: ['Persian', 'Bengal Cat', 'British Short Hair', 'Others'],
      Others: ['Goldfish', 'Pet Rat', 'Pet Bird', 'Others']
  };

  // Populate breed options based on selected species
  if (breeds[petSpecies]) {
    breeds[petSpecies].forEach(function(breed) {
        const option = document.createElement("option");
        option.value = breed;
        option.text = breed;
        breedSelect.add(option);
        if (breed==='Others'){
          option.value='Others';
        }
    });
  } else {
    // If no species is selected, reset breeds list
    const option = document.createElement("option");
    option.value = '';
    option.text = 'Select Breed';
    breedSelect.add(option);
  }
}


// Initialize breed options on page load
window.onload = function() {
  updateBreedOptions();
};

document.querySelector('form').addEventListener('submit', function(e) {
  var password = document.getElementById('pass').value;
  var confirmPassword = document.getElementById('conPass').value;

  if (password !== confirmPassword) {
      e.preventDefault();
      alert('Passwords do not match.');
  }
});

function showLastVaccinationDate() {
  const vaccinationSelect = document.getElementById('vaccination');
  const lastVaccinationDateDiv = document.getElementById('lastVaccinationDateDiv');

  if (vaccinationSelect.value === 'completed') {
      lastVaccinationDateDiv.style.display = 'block';
  } else {
      lastVaccinationDateDiv.style.display = 'none';
  }
}

function adoption_validateForm() {
  var pass = document.getElementById("pass").value;
  var conPass = document.getElementById("conPass").value;
  var householdMembers = document.getElementById("household_members").value;
  var petAge = document.getElementById("pet_age").value;
  var valid = true;
  var errorMessage = '';

  // Check if passwords match
  if (pass !== conPass) {
    errorMessage += 'Passwords do not match!\n';
    valid = false;
  }

  // Check if household members is suitable (minimum 1 person)
  if (householdMembers < 1) {
    errorMessage += 'The number of household members should be at least 1.\n';
    valid = false;
  }

  // Check if pet age is numeric
  if (petAge !== '' && isNaN(petAge)) {
    errorMessage += 'Please enter a valid number for the preferred pet age.\n';
    valid = false;
  }

  // If not valid, show an alert with errors
  if (!valid) {
    alert(errorMessage);
  }

  return valid;
}

function toggleDropdown(event) {
  event.preventDefault();
  const dropdown = document.getElementById('dropdown-content');
  dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropdown img')) {
    const dropdowns = document.getElementsByClassName("dropdown-content");
    for (let i = 0; i < dropdowns.length; i++) {
      const openDropdown = dropdowns[i];
      if (openDropdown.style.display === 'block') {
        openDropdown.style.display = 'none';
      }
    }
  }
}