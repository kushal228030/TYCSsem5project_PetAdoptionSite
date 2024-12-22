document.addEventListener('DOMContentLoaded', () => {
    const weaponForm = document.getElementById('weapon-form');
    const weaponList = document.getElementById('weapon-list');

    weaponForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const name = document.getElementById('name').value;
        const type = document.getElementById('type').value;
        const damage = document.getElementById('damage').value;

        if (name && type && damage) {
            const listItem = document.createElement('li');
            listItem.textContent = `Name: ${name}, Type: ${type}, Damage: ${damage}`;
            weaponList.appendChild(listItem);

            weaponForm.reset();
        }
    });
});
