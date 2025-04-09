document.addEventListener('DOMContentLoaded', function () {

    const startDate = new Date('2025-05-10T20:00:00'); // Date de dÃ©part - 08 aoÃ»t

    const currentDate = new Date(); // Date actuelle


    let timeRemaining = startDate - currentDate; // Calcul du temps restant en millisecondes

    function updateCountdown() {
        // Si le temps restant est supÃ©rieur Ã  zÃ©ro
        if (timeRemaining >= 0) {
            // Calculer le nombre de jours, heures, minutes et secondes
            const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            // Mettre Ã  jour les Ã©lÃ©ments HTML avec les nouvelles valeurs
            document.querySelector('.jour').textContent = days;
            document.querySelector('.heure').textContent = hours;
            document.querySelector('.minute').textContent = minutes;
            document.querySelector('.seconde').textContent = seconds;


            timeRemaining -= 1000; // Mettre Ã  jour le temps restant pour le prochain tick

            // RÃ©pÃ©ter la mise Ã  jour toutes les secondes
            setTimeout(updateCountdown, 1000);
        } else {
            // Si le compte Ã  rebours est terminÃ©
            document.querySelector('.decompte-block__time').textContent = 'Le temps est Ã©coulÃ©!';
        }
    }

    // DÃ©marrer la mise Ã  jour du compte Ã  rebours
    updateCountdown();
});