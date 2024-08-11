<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>$Title</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            .container {
                background-color: #fff;
                border-radius: 10px;
                padding: 20px;
                max-width: 400px;
                width: 100%;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                position: relative;
            }

            .back-button-container {
                position: absolute;
                top: 20px;
                left: 20px;
                z-index: 1;
            }

            .back-button {
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                padding: 10px 15px;
                cursor: pointer;
                font-size: 16px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                margin-bottom: 20px;
            }

            .back-button:hover {
                background-color: #0056b3;
            }

            div > h1, div > p {
                text-align: center;
            }

            button {
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 5px;
                padding: 10px 15px;
                cursor: pointer;
                width: 100%;
                font-size: 16px;
            }

            button:hover {
                background-color: #0056b3;
            }

            hr {
                margin: 20px;
            }
        </style>
    </head>
    <body>

        <div class="back-button-container">
            <a href="/" class="back-button">Back to homepage</a>
        </div>
        <div class="container">
            <div>
                <h1>$SiteConfig.Title</h1>

                <hr>

                <p style="color: red;">$Error</p>

                <a href="/admin">
                    <button>Try again</button>
                </a>
            </div>
        </div>
    </body>
</html>
