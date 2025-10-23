<<<<<<< HEAD

CZĘŚĆ UŻYTKOWNIKA

baza pytan testowych
1) baza dannych
2) szata graficzna - jak najbardziej uproszczona, ale czytelna i elastyczna
3) funkcjonalność
 	- wybór iłości pytań (10,20,30,40)
	- pytania wyświetlają się wszystkie na raz na liście (mamy 4 odpowiedzi - jedno poprawne)
	- w pytaniach może znalezc sie element graficzny
	- wynik - po ciśnieniu przycisku sprawdz
	- przy wyborze 40 pytań - mamy licznik ktory liczy godzinę
	- zakonczenie czasu - zakonczenie testu
	- po wyborze odpowiedzi odpowiedzi - zmienia sie kolor pytania
	- po sprawdzieniu mamy naszą odpowiedz i poprawną odpowiedz
	- randomowe losowanie pytań

-----------------------------------------------------------

CZĘŚĆ ADMINISTRACYJNE

na stronie głownej - przycisk do logowania na zaplecze z autoryzacja
na zapleczu - t6worzy liste pytan, ktore dodaja sie do bd, musi byc funkcja dodania obrazka do pytania
mozemy edytowac, dodawac i usuwac dane

=======
# PHP_QUIZ_PROJECT
🧠 Test Question Database  A simple PHP-based web application for creating, managing, and taking online tests. The project consists of two parts: a user section (test interface) and an administrative section (question management panel).

🎯 Features
🧍‍♀️ User Section

- Choose the number of questions: 10, 20, 30, or 40

- Randomized question selection from the database

- All questions are displayed on one page

- Each question has four answer options with only one correct

- Each question can include an image

- When a user selects an answer, the question changes color (visual feedback)

- After clicking the “Check” button, the user sees:

      - their chosen answers

      - the correct answers

- When choosing 40 questions, a 1-hour timer starts

      - When time runs out, the test automatically ends

- A final score is shown after completing the test

⚙️ Administrative Section (Admin Panel)

- Access via login with authentication

- Functions:

      - Add new questions (with image upload)

      - Edit existing questions

      - Delete questions

- All data is stored in a MySQL database

🧩 Technologies Used

- PHP

- MySQL

- HTML / CSS – minimal, responsive, and readable design

- JavaScript – timer and interactive logic

🗄️ Project Structure
/ (root)
├── index.php              # Main test page
├── admin/                 # Admin panel
│   ├── login.php
│   ├── dashboard.php
│   ├── add_question.php
│   ├── edit_question.php
│   └── delete_question.php
├── db/                    # Database connection
│   └── connect.php
├── assets/                # Images, styles, JS scripts
└── README.md
>>>>>>> 0bf39ce9d79b70c14f6333f923d2c0f55f68328b
