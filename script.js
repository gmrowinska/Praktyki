// Hamburger menu
document.addEventListener('DOMContentLoaded', () => {
  const menuToggle = document.querySelector('.menu-toggle');
  const menu = document.querySelector('.menu');
  menuToggle.addEventListener('click', () => {
    menu.classList.toggle('active');
  });
});

// Funkcje modali
function showLoginForm() {
  document.getElementById('loginModal').style.display = 'flex';
}
function showRegisterForm() {
  document.getElementById('registerModal').style.display = 'flex';
}
function closeModal(modalId) {
  document.getElementById(modalId).style.display = 'none';
}

// Toggle stopki
function toggleFooter() {
  const footerContent = document.getElementById('footer-content');
  footerContent.style.display = footerContent.style.display === 'block' ? 'none' : 'block';
}

// Logika generatora wyzwań
window.onload = () => {
  const drawButton = document.getElementById('losuj');
  const finishButton = document.getElementById('koniec');
  const result = document.getElementById('wynik');

  let done = JSON.parse(localStorage.getItem("done")) || [];
  if (finishButton) finishButton.style.display = 'none';

  clearHistoryAtMidnight();

  window.wyzwania = [
    "Zrób origami",
    "Zrób 10 pompek",
    "Ucz się przez 20 minut",
    "Zrób deser",
    "Spotkaj się z znajomym/znajomymi",
    "Spędź godzinę bez telefonu",
    "Wypij szklankę wody",
    "Naucz się 5 słówek w innym języku",
    "Zrób porządek w pokoju",
    "Spróbuj nowego sportu",
    "Narysuj coś",
    "Idź na siłownię",
    "Napisz list do siebie",
    "Idź na spacer",
    "Zrób listę swoich celów na najbliższy rok",
    "Spędź 10 minut na rozwiązywaniu krzyżówki",
    "Zrób plan oszczędnościowy",
    "Idź na pilates",
    "Zorganizuj swoje rzeczy w szafie",
    "Zrób coś, czego się boisz"
  ];

  window.currentChallengeIndex = null;

  // Obsługa logowania
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(this);
      fetch('login.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          closeModal('loginModal');
          window.location.reload();
        } else {
          alert(data.message);
        }
      })
      .catch(error => console.error('Błąd:', error));
    });
  }

  // Obsługa rejestracji
  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', function(event) {
      event.preventDefault();
      const password = document.getElementById('registerPassword').value;
      const confirmPassword = document.getElementById('confirmPassword').value;
      if (password !== confirmPassword) {
        alert("Hasła muszą być identyczne!");
        return;
      }
      const formData = new FormData(this);
      fetch('register.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        alert(data.message);
        if (data.success) {
          closeModal('registerModal');
        }
      })
      .catch(error => console.error('Błąd:', error));
    });
  }

  function clearHistoryAtMidnight() {
    const lastClearedDate = localStorage.getItem("lastClearedDate");
    const currentDate = new Date().toLocaleDateString();
    if (lastClearedDate !== currentDate) {
      localStorage.removeItem("done");
      localStorage.setItem("lastClearedDate", currentDate);
      done = [];
    }
  }

  window.losuj = function() {
    result.style.color = "black";
    result.style.textDecoration = "none";

    if (done.length >= window.wyzwania.length) {
      result.innerHTML = "<h4 style='color:black;'>Koniec wyzwań na dziś!</h4>";
      if (finishButton) finishButton.style.display = 'none';
      window.currentChallengeIndex = null;
      return;
    }

    let candidate;
    do {
      candidate = Math.floor(Math.random() * window.wyzwania.length);
    } while (done.includes(candidate));

    window.currentChallengeIndex = candidate;
    result.innerHTML = window.wyzwania[candidate];

    if (drawButton) drawButton.textContent = "Wylosuj ponownie";
    if (finishButton) finishButton.style.display = 'block';
  }

  window.mark_finish = function() {
    if (window.currentChallengeIndex === null) return;
    result.style.color = "green";
    result.style.textDecoration = "underline";
    alert("Gratulacje! Wyzwanie wykonane 🎉");
    if (!done.includes(window.currentChallengeIndex)) {
      done.push(window.currentChallengeIndex);
      localStorage.setItem("done", JSON.stringify(done));
    }
    if (finishButton) finishButton.style.display = 'none';
    window.currentChallengeIndex = null;
    updateHistory();
  }

  function updateHistory() {
    const historyContainer = document.getElementById('history-container');
    if (!historyContainer) return;
    historyContainer.innerHTML = '';
    if (done.length === 0) {
      historyContainer.innerHTML = '<li>Nie wykonano żadnych wyzwań.</li>';
      return;
    }
    done.forEach((index) => {
      const li = document.createElement('li');
      li.textContent = window.wyzwania[index];
      historyContainer.appendChild(li);
    });
  }

  updateHistory();
}

// Zamknięcie modali po kliknięciu poza nimi
window.onclick = function(event) {
  const loginModal = document.getElementById('loginModal');
  const registerModal = document.getElementById('registerModal');
  if (event.target === loginModal) {
    loginModal.style.display = 'none';
  }
  if (event.target === registerModal) {
    registerModal.style.display = 'none';
  }
}
