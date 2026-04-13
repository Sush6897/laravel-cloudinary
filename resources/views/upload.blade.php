<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Video Upload | SimuPhish</title>
    <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --bg: #0f172a;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            color: var(--text-main);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .container {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
            background: var(--card-bg);
            border-radius: 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            text-align: center;
            position: relative;
            z-index: 10;
        }

        h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        .upload-area {
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 1rem;
            padding: 3rem 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .upload-area:hover, .upload-area.dragover {
            border-color: var(--primary);
            background: rgba(79, 70, 229, 0.05);
        }

        .upload-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .btn-browse {
            background: var(--primary);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            display: inline-block;
            transition: background 0.2s;
        }

        .btn-browse:hover {
            background: var(--primary-hover);
        }

        .progress-container {
            display: none;
            margin-top: 2rem;
            text-align: left;
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .progress-bar {
            width: 100%;
            height: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 1rem;
            overflow: hidden;
        }

        .progress-fill {
            width: 0%;
            height: 100%;
            background: linear-gradient(to right, #4f46e5, #818cf8);
            transition: width 0.3s ease;
        }

        #status-message {
            margin-top: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-success { color: #10b981; }
        .status-error { color: #ef4444; }
        .status-info { color: #6366f1; }

        /* Abstract Background shapes */
        .shape {
            position: absolute;
            z-index: 1;
            filter: blur(80px);
            opacity: 0.4;
            border-radius: 50%;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            background: #4f46e5;
            top: -100px;
            left: -100px;
        }

        .shape-2 {
            width: 400px;
            height: 400px;
            background: #7c3aed;
            bottom: -150px;
            right: -100px;
        }

        #browse-button { cursor: pointer; }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>

    <div class="container">
        <h1>Video Upload</h1>
        <p>Reliable, chunked upload for large video files.</p>

        <div id="upload-area" class="upload-area">
            <div class="upload-icon">↑</div>
            <div id="browse-button" class="btn-browse">Choose Video File</div>
            <div style="margin-top: 1rem; font-size: 0.875rem;">or drag and drop here</div>
        </div>

        <div id="progress-container" class="progress-container">
            <div class="progress-info">
                <span id="file-name">filename.mp4</span>
                <span id="progress-percent">0%</span>
            </div>
            <div class="progress-bar">
                <div id="progress-fill" class="progress-fill"></div>
            </div>
            <div id="status-message">Ready to upload...</div>
        </div>
    </div>

    <script>
        const resumable = new Resumable({
            target: '/upload-chunk',
            query: { _token: '{{ csrf_token() }}' },
            fileType: ['mp4', 'avi', 'mov', 'mkv'],
            chunkSize: 2 * 1024 * 1024, // 2MB chunks
            simultaneousUploads: 3,
            testChunks: true,
            throttleProgressCallbacks: 1
        });

        const browseButton = document.getElementById('browse-button');
        const uploadArea = document.getElementById('upload-area');
        const progressContainer = document.getElementById('progress-container');
        const progressFill = document.getElementById('progress-fill');
        const progressPercent = document.getElementById('progress-percent');
        const fileNameLabel = document.getElementById('file-name');
        const statusMessage = document.getElementById('status-message');

        resumable.assignBrowse(browseButton);
        resumable.assignDrop(uploadArea);

        resumable.on('fileAdded', function(file) {
            progressContainer.style.display = 'block';
            fileNameLabel.textContent = file.fileName;
            statusMessage.textContent = 'Starting upload...';
            statusMessage.className = 'status-info';
            resumable.upload();
        });

        resumable.on('fileProgress', function(file) {
            let percent = Math.floor(file.progress() * 100);
            progressFill.style.width = percent + '%';
            progressPercent.textContent = percent + '%';
            statusMessage.textContent = 'Uploading chunked data...';
        });

        resumable.on('fileSuccess', function(file, message) {
            const response = JSON.parse(message);
            statusMessage.textContent = 'Upload complete! Processing video in background...';
            statusMessage.className = 'status-success';
            progressFill.style.background = '#10b981';
        });

        resumable.on('fileError', function(file, message) {
            statusMessage.textContent = 'Upload failed. Retrying...';
            statusMessage.className = 'status-error';
        });

        // UI Interactions
        uploadArea.ondragover = () => uploadArea.classList.add('dragover');
        uploadArea.ondragleave = () => uploadArea.classList.remove('dragover');
        uploadArea.ondrop = () => uploadArea.classList.remove('dragover');

    </script>
</body>
</html>
