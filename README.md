
# Ohayoo

Ohayoo is a social networking website developed in PHP, similar to Facebook or Instagram, allowing users to chat, share posts, customize their profiles, and more.

## 📁 Project Structure

```graph
.  
├── api/                # API endpoints  
├── asset/              # Static files (CSS, JS, images)  
├── classes/            # PHP classes  
├── core/               # Configuration files, database  
├── inc/                # PHP includes (header, footer, etc.)  
├── layouts/            # HTML/PHP layouts  
├── p/, u/              # Custom directories for files  
├── upload/             # User-uploaded files: chat, profile, posts, cover...  
├── vendor/             # Composer dependencies  
├── .htaccess           # Apache configuration  
├── *.php               # Main pages: chat, login, home, etc.  
├── ohayoo.sql          # Database export  
```

## 🚀 Features

- Secure authentication (registration, login, hashed password)
- User profile (photo, cover, bio, personal information)
- Posting
- Comments on posts
- Like system (on posts and comments)
- Personalized news feed
- Real-time chat
- File uploads
- Notifications
- Responsive design

## 🔧 Installation

1. Clone this repository :

```bash
git clone https://github.com/badie16/ohayoo.git
```

2. Install PHP dependencies :

```bash
composer install
```

3. Create a MySQL database and import ohayoo.sql.

4. Configure the database connection in core/db.php (or equivalent).

5. Run the project locally:

```bash
php -S localhost:8000
```

6. Open your browser at http://localhost:8000


## 🧹 Remove the upload/ Folder from GitHub

Add this to .gitignore :

```gitignore
upload/
```



## ✅ To-Do


- [ ] Real-time notifications
- [ ] Advanced security (hashing, CSRF, upload validation)
- [ ] Mobile optimization & responsive improvements

## 📜 Licence

Personal project — free for learning or inspiration.

---

Developed with ❤️ by Badie Bahida.


