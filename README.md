# DevCollab — AI-Powered Project Management Platform

> A modern full-stack project management and team collaboration platform inspired by Jira and Trello — built with Laravel, MySQL, and AI integration.

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

---

## Overview

DevCollab is a collaborative project management platform designed for development teams and organizations.  
The application enables teams to manage projects, assign tasks, collaborate in real time, track productivity, and automate workflows using AI.

This project was developed as a professional portfolio application to demonstrate advanced full-stack Laravel development, OAuth authentication, AI integration, and scalable application architecture.

---

## Features

### Authentication & Security
- Email/password authentication
- GitHub OAuth login
- Google OAuth login
- Secure role-based access control
- Global Admin and Member permissions
- Protected admin middleware

### Project & Task Management
- Create and manage projects
- Invite and assign team members
- Kanban board with drag-and-drop functionality
- Task priorities (Low / Medium / High)
- Due date tracking and overdue alerts
- Personal "My Tasks" dashboard
- Task comments and collaboration

### File Attachments
- Upload PDFs, images, and documents
- 10MB upload limit
- Secure file storage using Laravel filesystem

### AI Integration (Groq API — LLaMA 3.3 70B)
- Automatically generate project tasks using AI
- Smart task distribution based on team skills
- AI-generated assignment reasoning
- OpenAI-compatible API integration

### Analytics & Reporting
- Global dashboard statistics
- Project completion tracking
- Animated progress indicators
- Team performance analytics
- Priority distribution charts with Chart.js

### Calendar System
- FullCalendar.js integration
- Task scheduling by due date
- Drag-and-drop calendar updates
- Status and priority filtering

### Team Collaboration
- Real-time project chat
- AJAX polling updates every 3 seconds
- Member invitation system with secure tokens
- Skills and job title management

### User Experience
- Dark mode / Light mode
- English / French localization
- Fully responsive design
- Modern dashboard UI

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 12, PHP 8.2 |
| Database | MySQL 8.0 |
| Frontend | Blade, Vanilla JavaScript, CSS |
| AI | Groq API (LLaMA 3.3 70B) |
| Authentication | Laravel Breeze + Socialite |
| Charts | Chart.js |
| Calendar | FullCalendar.js |
| Email | Laravel Mail + Mailtrap |
| Storage | Laravel Filesystem |

---

## Architecture

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── AiController
│   │   ├── AnalyticsController
│   │   ├── CalendarController
│   │   ├── ChatController
│   │   ├── CommentController
│   │   ├── ProjectController
│   │   ├── SettingsController
│   │   ├── TaskController
│   │   └── TeamController
│   └── Middleware/
│       ├── EnsureIsAdmin
│       └── SetLocale
├── Models/
├── database/
├── resources/views/
└── routes/
```

---

## Screenshots

### Login Page
<img width="100%" src="https://raw.githubusercontent.com/Marwa-El-faiz/DevCollab/main/Capture%20d'%C3%A9cran%202026-05-07%20111326.png" />

### Dashboard
<img width="100%" src="https://raw.githubusercontent.com/Marwa-El-faiz/DevCollab/main/Capture%20d'%C3%A9cran%202026-05-07%20111424.png" />

### Kanban Board
<img width="100%" src="https://raw.githubusercontent.com/Marwa-El-faiz/DevCollab/main/Capture%20d'%C3%A9cran%202026-05-07%20111553.png" />

### Calendar View
<img width="100%" src="https://raw.githubusercontent.com/Marwa-El-faiz/DevCollab/main/Capture%20d'%C3%A9cran%202026-05-07%20111614.png" />

### Team Management
<img width="100%" src="https://raw.githubusercontent.com/Marwa-El-faiz/DevCollab/main/Capture%20d'%C3%A9cran%202026-05-07%20111633.png" />


---

## Installation

### Prerequisites

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8+

### Setup

```bash
git clone https://github.com/Marwa-El-faiz/DevCollab.git

cd DevCollab

composer install

npm install && npm run build

cp .env.example .env

php artisan key:generate
```

---

## Environment Configuration

```env
DB_DATABASE=devcollab
DB_USERNAME=root
DB_PASSWORD=

GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=

GROQ_API_KEY=
GROQ_MODEL=llama-3.3-70b-versatile
```

---

## Database Setup

```bash
php artisan migrate

php artisan db:seed

php artisan serve
```

---

## Demo Admin Account

```text
Email: karim.benali@devcollab.io
Password: Admin@2024
```

---

## Key Design Decisions

### Vanilla JavaScript Approach
The application intentionally avoids frontend frameworks to demonstrate strong DOM manipulation and AJAX fundamentals using pure JavaScript.

### Practical AI Integration
The AI module is fully functional and integrated into real workflows, including intelligent task generation and assignment logic.

### Layered Authorization
Permissions are enforced at middleware, route, controller, and UI levels to ensure security and role isolation.

### Dynamic Theme System
Dark mode is implemented using CSS variables and class switching without requiring frontend re-rendering.

---

## Future Improvements

- Real-time WebSocket chat
- Notifications system
- Docker deployment
- CI/CD pipeline
- Activity logs
- Mobile application
- Advanced AI sprint planning

---

## License

This project is licensed under the MIT License.

---

## Author

Built by Marwa El Faiz as a full-stack portfolio project focused on Laravel development, AI integration, and collaborative productivity systems.
