let pets = null;
let breedName = null;

// Function to get query parameter value by name
function getQueryParam(name) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(name);
}

// Fetch data from JSON
fetch("data.json")
  .then(response => response.json())
  .then(data => {
    const breed = getQueryParam('breed');
    if (breed) {
      // Check if the breed exists in the data
      if (data.pets_by_breed[breed]) {
        pets = data.pets_by_breed[breed].pets || data.pets_by_breed[breed]; // Adjusting for "pets" key in breed data
        breedName = breed;
        displayPets(pets, breedName);
      } else {
        console.error("Breed not found in data");
        document.querySelector(".pet_grid").innerHTML = '<p>No pets available for this breed.</p>';
      }
    } else {
      console.error("No breed specified in URL");
      document.querySelector(".pet_grid").innerHTML = '<p>No breed specified.</p>';
    }
  })
  .catch(error => console.error("Error fetching data:", error));

// Function to display pets
function displayPets(pets, breedName) {
  let petGrid = document.querySelector(".pet_grid");
  let breedHeader = document.querySelector(".breed_header");
  
  petGrid.innerHTML = ''; // Clear the grid before populating

  if (breedHeader) {
    breedHeader.innerHTML = `<h2>${breedName}</h2>`; // Set the breed name in <h2>
  }

  if (pets && pets.length) {
    pets.forEach(pet => {
      let petDiv = document.createElement("div");
      petDiv.classList.add("pet");

      let petImgDiv = document.createElement("div");
      petImgDiv.classList.add("pet_img");

      let petImg = document.createElement("img");
      petImg.src = pet.image;
      petImgDiv.appendChild(petImg);

      let detailsDiv = document.createElement("div");
      detailsDiv.classList.add("details");

      detailsDiv.innerHTML = `
        <p class="p_name">Name: ${pet.name}</p>
        <p class="p_breed">Breed: ${breedName}</p>
        <p class="p_age">Age: ${pet.age} years</p>
        <p class="p_gender">Gender: ${pet.gender}</p>
        <p class="p_allergies">Allergies: ${pet.allergies}</p>
        <p class="p_location">Location: ${pet.location}</p>
        <p class="p_vaccination">Vaccination: ${pet.vaccination}</p>
        <p class="p_vet">Vet: ${pet.vet_details.name}</p>
        <p class="p_vet_contact">Vet Contact:${pet.vet_details.contact}</p>
        <p class="p_donator_contact">Donor Contact: <a href="tel:${pet.user_contact}">${pet.user_contact}</a></p>
         <p class="p_donator_contact">Donor email: <a href="mailto:${pet.user_email}">${pet.user_email}</a></p>
        <button 
      class="adopt_btn" 
      data-pet-name="${pet.name}" 
      data-pet-breed="${breedName}"
      data-pet-age="${pet.age}" 
      data-pet-gender="${pet.gender}" 
      data-pet-allergies="${pet.allergies}"
      data-pet-location="${pet.location}"
      data-pet-vaccination="${pet.vaccination}"
      data-pet-vet-name="${pet.vet_details.name}"
      data-pet-vet-contact="${pet.vet_details.contact}"
      data-donor-mob="${pet.user_contact}" 
      data-donor-mail="${pet.user_email}"
      data-pet-img="${pet.image}"
    >Donor Contact</button>
  `;

      petDiv.appendChild(petImgDiv);
      petDiv.appendChild(detailsDiv);

      petGrid.appendChild(petDiv);
      return petDiv;
    });

    // Add event listeners to adopt buttons
    document.querySelectorAll('.adopt_btn').forEach(button => {
      button.addEventListener('click', handleAdoptClick);
    });
  } else {
    petGrid.innerHTML = '<p>No pets available for this breed.</p>';
  }
}

// Function to handle adopt button clicks
function handleAdoptClick(event) {
  // const petName_con = event.target.getAttribute('data-pet-name');
  // const pet_donor_mob = event.target.getAttribute('data-donor-mob');
  // const pet_donor_email = event.target.getAttribute('data-donor-email');
  const button = event.target;
  const selectedPet = {
    name: button.getAttribute('data-pet-name'),
    breed: button.getAttribute('data-pet-breed'),
    age: button.getAttribute('data-pet-age'),
    gender: button.getAttribute('data-pet-gender'),
    allergies: button.getAttribute('data-pet-allergies'),
    location: button.getAttribute('data-pet-location'),
    vaccination: button.getAttribute('data-pet-vaccination'),
    vetName: button.getAttribute('data-pet-vet-name'),
    vetContact: button.getAttribute('data-pet-vet-contact'),
    donorMob: button.getAttribute('data-donor-mob').trim(),
    donorEmail: button.getAttribute('data-donor-mail').trim(),
    petsImg:button.getAttribute('data-pet-img'),
  };

  
        sessionStorage.setItem('selectedPet', JSON.stringify(selectedPet));
        window.location.href = 'pet_details.html';
        
}



