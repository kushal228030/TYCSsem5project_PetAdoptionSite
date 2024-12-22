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
  
