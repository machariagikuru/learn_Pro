<p align="center">
  <img src="public/Photos/long _logo.jpg" alt="Learn Pro Logo" width="180"/>
</p>

# 📚 Learn Pro

A modern, full-featured platform for managing online courses, users, instructors, and learning analytics. Built with Laravel, Blade, Tailwind CSS, and MySQL, this system streamlines course delivery, user management, and educational analytics for academies and e-learning providers.

---

## 🚀 Features

- 👤 **User Management**: Registration, login, roles (Admin, Instructor, User), profile management
- 🛡️ **Admin Panel**: User & course approval, role management, analytics dashboard, notifications
- 🎓 **Instructor Panel**: Course creation, chapter & lesson management, task/quest assignment, student progress tracking
- 📚 **Course & Lesson Management**: Upload courses with images/videos, organize by categories, approval workflow
- 📝 **Tasks & Insight Quests**: Assignments, file submissions, grading, feedback, multi-file upload
- 📊 **Analytics & Statistics**: Enrollment stats, student progress, course performance charts
- 🔔 **Notifications**: Email & in-app notifications for submissions, grading, approvals
- 🔍 **Search & Filtering**: Find courses by title, category, or description
- 🗂️ **File Management**: Upload, store, and download multiple file types
- 🧑‍💻 **Role Requests**: Instructors can request access, admins approve
- 🏆 **Certificates**: Auto-generate course completion certificates (PDF)
- 🌐 **Modern UI**: Responsive, clean interface using Blade, Tailwind CSS, and Alpine.js

---

## 🛠️ Tech Stack

- **Backend**: [Laravel 11](https://laravel.com/)
- **Frontend**: Blade, [Tailwind CSS](https://tailwindcss.com/), [Alpine.js](https://alpinejs.dev/)
- **Build Tools**: [Vite](https://vitejs.dev/), [Laravel Vite Plugin](https://laravel-vite.dev/)
- **Database**: MySQL
- **PDF Generation**: barryvdh/laravel-dompdf
- **Notifications**: Laravel Notifications, Flasher (SweetAlert, Toastr)
- **OAuth**: Laravel Socialite
- **Testing**: PHPUnit, Pest

---

## 📂 Project Folder Structure

```
├── app/                # Application logic (Models, Controllers, etc.)
├── bootstrap/          # Laravel bootstrap files
├── config/             # Configuration files
├── database/           # Migrations, seeders, factories
├── public/             # Public assets (images, CSS, entry point)
│   └── Photos/         # Screenshots & images for documentation
├── resources/
│   └── views/          # Blade templates (admin, instructor, Home, etc.)
├── routes/             # Web & API routes
├── storage/            # Logs, compiled files, uploads
├── tests/              # Test cases
├── artisan             # Artisan CLI
├── composer.json       # PHP dependencies
├── package.json        # JS dependencies
└── ...
```

---

## 🖥️ How to Run Locally

1. **Clone the repository**
   ```bash
   git clone <your-repo-url>
   cd <project-folder>
   ```
2. **Install PHP dependencies**
   ```bash
   composer install
   ```
3. **Install JS dependencies**
   ```bash
   npm install
   ```
4. **Copy & configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your DB and mail settings
   php artisan key:generate
   ```
5. **Run migrations**
   ```bash
   php artisan migrate
   # (Optional) php artisan db:seed
   ```
6. **Run the development server**
   ```bash
   npm run dev &
   php artisan serve
   ```
7. **Access the app**
   - Visit [http://localhost:8000](http://localhost:8000)

---

## 🖼️ Screenshots & Categories

Here are some screenshots from different sections of the application.  
*More screenshots and a live demo are coming soon!*

### 🏠 Landing Page
A welcoming homepage where users can explore the platform and its features.
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Landing_Page\1.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Landing_Page\2.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Landing_Page\3.png" width="600"/>

### 🔐 Login & Registration
Secure login and registration forms for new and returning users.
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Login_Registration\1.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Login_Registration\2.png" width="600"/>

### 🎓 Student Dashboard
Students can view their enrolled courses, progress, and access learning materials.
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\1.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\2.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\3.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\4.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\5.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\6.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\7.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\8.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\9.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\10.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\11.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Student\12.png" width="600"/>

### 👨‍🏫 Instructor Panel
Instructors manage their courses, upload content, and track student performance.
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Instructor\1.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Instructor\2.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Instructor\3.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Instructor\4.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Instructor\5.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Instructor\6.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Instructor\7.png" width="600"/>
<!-- Instructor panel screenshot coming soon -->

### 🛡️ Admin Panel
Admins oversee the platform, approve courses, and manage users.
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Admin\1.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Admin\2.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Admin\3.png" width="600"/>
<img src="D:\Projects\Github_Upload\learn_pro\public\Photos\Admin\4.png" width="600"/>
<!-- Admin panel screenshot coming soon -->

**Live demo: Coming soon!**

---

## 📌 Notes

- `.env` file is ignored by git for security.
- Use `php artisan test` or `phpunit` to run tests.
- For PDF generation, ensure required PHP extensions are installed.
- File uploads are stored in `public/courses` and `public/Photos`.
- Admin approval is required for new courses and instructor requests.

---

## 📫 Contact

- **GitHub**: [your-github-username](https://github.com/your-github-username)
- **Email**: your.email@example.com

---

## 💬 Contributing & Support

We welcome contributions, suggestions, and bug reports! Feel free to fork the repo, open issues, or submit pull requests. If you need help, please reach out via GitHub or email.

**Happy learning and building! 🚀**
