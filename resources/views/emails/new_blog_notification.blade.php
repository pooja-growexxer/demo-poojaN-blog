<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Blog Notification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #3498db;
        }

        h2 {
            color: #333333;
        }

        p {
            color: #555555;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>A new blog post has been created:</h1>

    <h2>{{ $blog->blog_title }}</h2>
    <p>{{ $blog->blog_description }}</p>
    </div>
</body>
</html>