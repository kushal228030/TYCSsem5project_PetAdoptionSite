document.addEventListener('DOMContentLoaded', function() {
  // Retrieve the pet details from sessionStorage
  const selectedPet = JSON.parse(sessionStorage.getItem('selectedPet'));

  if (selectedPet) {
      // Display the pet details on the page
      document.getElementById('pet-name').textContent = selectedPet.name;
      document.getElementById('pet-breed').textContent = selectedPet.breed;
      document.getElementById('pet-age').textContent = selectedPet.age;
      document.getElementById('pet-gender').textContent = selectedPet.gender;
      document.getElementById('pet-allergies').textContent = selectedPet.allergies;
      document.getElementById('pet-location').textContent = selectedPet.location;
      document.getElementById('pet-vaccination').textContent = selectedPet.vaccination;
      document.getElementById('pet-vet').textContent = selectedPet.vetName;
      document.getElementById('pet-vet-contact').textContent = selectedPet.vetContact;
      document.getElementById('pet-donor-contact').textContent = selectedPet.donorMob;
      document.getElementById('pet-donor-email').textContent = selectedPet.donorEmail;
      
      // Set the image src and alt if available
      const petPhotoImg = document.getElementById('pet-photo').querySelector('img');
      if (selectedPet.petsImg) {
          petPhotoImg.src = selectedPet.petsImg;
          petPhotoImg.alt = "Image of "+selectedPet.name; // Set the alt attribute correctly
      } else {
          petPhotoImg.alt = "No image available"; // Set a default alt text if no image is available
      }
  } else {
      // Handle the case where no pet details are found
      document.querySelector('.container').innerHTML = '<p>Sorry, no pet details found.</p>';
  }
});