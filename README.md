# Large Video Upload System with Laravel & Cloudinary

A robust Laravel application demonstrating efficient handling of large video file uploads (300MB+) using chunked uploading, background job processing, and cloud storage integration.

## 🚀 Key Features

- **Chunked File Uploads**: Utilizes `Resumable.js` to break large files into smaller chunks for reliable uploads, even on unstable connections.
- **Asynchronous Processing**: Background jobs (Laravel Queues) handle file merging and Cloudinary API transfers to ensure a zero-wait UI experience.
- **Cloudinary Integration**: Secure storage and optimized delivery for video assets.
- **Real-time Progress Tracking**: Intuitive UI with live progress bars and status updates.
- **Automated Notifications**: Email notifications dispatched to administrators upon successful upload and processing.
- **Error Resilience**: Automatic cleanup of temporary files and detailed error reporting in the dashboard.

## 🛠️ Technical Stack

- **Backend**: Laravel 11.x
- **Frontend**: Blade, JavaScript (Resumable.js)
- **Database**: MySQL (for file metadata and queue management)
- **Cloud Storage**: Cloudinary (via `cloudinary-laravel` SDK v3)
- **Mailing**: SMTP / Gmail

---

## 💻 Installation & Setup

Follow these steps to get the project running on your local machine.

### 1. Prerequisites
Ensure you have the following installed:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL Server

### 2. Clone the Repository
```bash
git clone https://github.com/your-username/cloudinary-laravel-video-upload.git
cd cloudinary-laravel-video-upload
```

### 3. Install Dependencies
```bash
composer install
npm install
npm run build
```

### 4. Environment Configuration
Copy the example environment file and update your credentials.
```bash
cp .env.example .env
```

**Required `.env` Variables:**
```env
# Database
DB_DATABASE=your_db_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Queue Settings
QUEUE_CONNECTION=database

# Cloudinary Credentials
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret

# Mail Settings
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_app_password
MAIL_FROM_ADDRESS="admin@example.com"
```

### 5. Finalize Installation
Generate the application key and migrate the database.
```bash
php artisan key:generate
php artisan migrate
php artisan storage:link
```

---

## 🏃 Running the Application

To handle the large file uploads and background processing, you need to run three separate terminal processes:

1. **Start the Web Server:**
   ```bash
   php artisan serve
   ```

2. **Start the Queue Worker (Crucial for Cloudinary Upload):**
   ```bash
   php artisan queue:work
   ```

3. **Vite Development Server:**
   ```bash
   npm run dev
   ```

Now visit `http://localhost:8000` in your browser.

---

## 📂 Project Structure Highlights

- **`app/Http/Controllers/VideoUploadController.php`**: Handles the initial chunked reception from the frontend.
- **`app/Jobs/ProcessVideoUpload.php`**: The background worker that merges chunks and performs the chunked upload to Cloudinary.
- **`config/filesystems.php`**: Configured with the `cloudinary` driver for seamless API integration.
- **`resources/views/upload.blade.php`**: The main interface using Resumable.js for chunk management.

## 🧪 Testing with Large Files
The system is pre-configured to handle files exceeding **100MB** by using Cloudinary's chunked upload API (set to 6MB chunks). Even on the Cloudinary free plan, this allows you to bypass standard upload size limits.

---

## 📄 License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
