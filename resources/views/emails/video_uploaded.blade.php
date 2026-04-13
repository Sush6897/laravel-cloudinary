<!DOCTYPE html>
<html>
<head>
    <title>Video Uploaded</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #4f46e5;">New Video Uploaded Successfully</h2>
        <p>A new large video file has been uploaded, processed, and stored in Cloudinary.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Original Name:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $video->original_filename }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Size:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ number_format($video->size / 1048576, 2) }} MB</td>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><strong>Cloudinary URL:</strong></td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;"><a href="{{ $video->cloudinary_url }}" target="_blank">View Video</a></td>
            </tr>
        </table>

        <p style="margin-top: 30px;">
            <a href="{{ $video->cloudinary_url }}" style="background-color: #4f46e5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">Watch Video Now</a>
        </p>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 40px 0 20px;">
        <p style="font-size: 12px; color: #777;">This is an automated notification from your Laravel Application.</p>
    </div>
</body>
</html>
