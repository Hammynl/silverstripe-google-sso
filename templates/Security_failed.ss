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

            form {
                margin-bottom: 20px;
            }

            fieldset {
                border: none;
            }

            label {
                display: inline-block;
                font-weight: bold;
                margin-bottom: 5px;
                color: #333;
            }

            /* Styling the input fields */
            input[type="text"],
            input[type="password"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }

            /* Styling the checkbox */
            .checkbox {
                margin-bottom: 15px;
            }

            .checkbox label {
                font-weight: normal;
                color: #666;
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

            #ForgotPassword {
                text-align: center;
                margin-top: 15px;
            }

            #ForgotPassword a {
                color: #007bff;
                text-decoration: none;
            }

            #ForgotPassword a:hover {
                text-decoration: underline;
            }

            .login-with-google-btn {
                transition: background-color .3s, box-shadow .3s;
                padding: 12px 16px 12px 42px;
                border: none;
                border-radius: 3px;
                box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
                color: #757575;
                font-size: 14px;
                font-weight: 500;
                font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen,Ubuntu,Cantarell,"Fira Sans","Droid Sans","Helvetica Neue",sans-serif;
                background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
                background-color: white;
                background-repeat: no-repeat;
                background-position: 12px 11px;
            }

            .login-with-google-btn:hover {
                box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 2px 4px rgba(0, 0, 0, .25);
                cursor: pointer;
            }

            .login-with-google-btn:active {
                background-color: #eeeeee;
            }

            .login-with-google-btn:focus {
                outline: none;
                box-shadow:
                    0 -1px 0 rgba(0, 0, 0, .04),
                    0 2px 4px rgba(0, 0, 0, .25),
                    0 0 0 3px #c8dafc;
            }

            .login-with-google-btn:disabled {
                filter: grayscale(100%);
                background-color: #ebebeb;
                box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
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
