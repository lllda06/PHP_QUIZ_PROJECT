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
