<!DOCTYPE html>
<html>
<head><title>Upload Photo</title></head>
<body>
    <h1 align="center">Upload New Photo</h1>

    <form method="POST" action="{{ route('photos.store') }}" enctype="multipart/form-data">
        @csrf
        <table border="1" cellpadding="10" cellspacing="0" align="center">
            <tr>
                <td>
                    <p>
                        <label><b>Choose Photo:</b></label><br>
                        <input type="file" name="image" required>
                    </p>

                    <p>
                        <label><b>Caption:</b></label><br>
                        <input type="text" name="caption" maxlength="255" placeholder="Write a caption (optional)">
                    </p>

                    <p align="center">
                        <button type="submit">📤 Upload</button>
                    </p>

                    <p align="center">
                        <a href="{{ route('photos.index') }}">⬅ Back to Photos</a>
                    </p>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>
