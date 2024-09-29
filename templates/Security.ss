<!DOCTYPE html>
<html lang="$ContentLocale">
    <head>
        <% if $SiteConfig.Title %>
            <title>$SiteConfig.Title: <%t SilverStripe\LoginForms.LOGIN "Log in" %></title>
            $Metatags(false).RAW
        <% else %>
            $Metatags.RAW
        <% end_if %>
        <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
        <meta name="color-scheme" content="light <% if $darkModeIsEnabled() %>dark<% else %>only<% end_if %>"/>
        <% require css("silverstripe/admin: client/dist/styles/bundle.css") %>
        <% require css("silverstripe/login-forms: client/dist/styles/bundle.css") %>
        <% if $darkModeIsEnabled() %>
            <% require css("silverstripe/login-forms: client/dist/styles/darkmode.css") %>
        <% end_if %>
        <% require javascript("silverstripe/login-forms: client/dist/js/bundle.js") %>

        <style>
            .google-button-div {
                display: flex;
                justify-content: center;
                margin-top: 20px;
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
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
                background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIj48cGF0aCBkPSJNMTcuNiA5LjJsLS4xLTEuOEg5djMuNGg0LjhDMTMuNiAxMiAxMyAxMyAxMiAxMy42djIuMmgzYTguOCA4LjggMCAwIDAgMi42LTYuNnoiIGZpbGw9IiM0Mjg1RjQiIGZpbGwtcnVsZT0ibm9uemVybyIvPjxwYXRoIGQ9Ik05IDE4YzIuNCAwIDQuNS0uOCA2LTIuMmwtMy0yLjJhNS40IDUuNCAwIDAgMS04LTIuOUgxVjEzYTkgOSAwIDAgMCA4IDV6IiBmaWxsPSIjMzRBODUzIiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNNCAxMC43YTUuNCA1LjQgMCAwIDEgMC0zLjRWNUgxYTkgOSAwIDAgMCAwIDhsMy0yLjN6IiBmaWxsPSIjRkJCQzA1IiBmaWxsLXJ1bGU9Im5vbnplcm8iLz48cGF0aCBkPSJNOSAzLjZjMS4zIDAgMi41LjQgMy40IDEuM0wxNSAyLjNBOSA5IDAgMCAwIDEgNWwzIDIuNGE1LjQgNS40IDAgMCAxIDUtMy43eiIgZmlsbD0iI0VBNDMzNSIgZmlsbC1ydWxlPSJub256ZXJvIi8+PHBhdGggZD0iTTAgMGgxOHYxOEgweiIvPjwvZz48L3N2Zz4=);
                background-color: white;
                background-repeat: no-repeat;
                background-position: 12px 50%;
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
                box-shadow: 0 -1px 0 rgba(0, 0, 0, .04),
                0 2px 4px rgba(0, 0, 0, .25),
                0 0 0 3px #c8dafc;
            }

            .login-with-google-btn:disabled {
                filter: grayscale(100%);
                background-color: #ebebeb;
                box-shadow: 0 -1px 0 rgba(0, 0, 0, .04), 0 1px 1px rgba(0, 0, 0, .25);
            }

            #hidden-google-login {
                display: none;
            }

            hr {
                color: white;
                background-color: white;
            }

            #ForgotPassword {
                display: none;
            }


        </style>
    </head>
    <body
        <% if $darkModeIsEnabled() %>class="dark-mode-enabled"<% end_if %>>
        <% include AppHeader %>

        <main class="login-form">
            <div class="login-form__header">
                <% if $Title %>
                    <h2 class="login-form__title">$Title</h2>
                <% end_if %>
            </div>

            <% if $Message %>
                <p class="login-form__message
                                <% if $MessageType && not $AlertType %>login-form__message--$MessageType<% end_if %>
                    <% if $AlertType %>login-form__message--$AlertType<% end_if %>"
                >
                    $Message
                </p>
            <% end_if %>

            <% if $Content && $Content != $Message %>
                <div class="login-form__content">$Content</div>
            <% end_if %>

            $Form
            <div id="hidden-google-login">
                <hr>
                <div class="google-button-div">
                    <a href="/google-login/login">
                        <button type="button" class="login-with-google-btn">
                            Sign in with Google
                        </button>
                    </a>
                </div>
            </div>

        </main>

        <footer class="silverstripe-brand">
            <% include SilverStripeLogo %>
        </footer>

        <script>
            document.addEventListener("keydown", (event) => {
                if (event.ctrlKey && (event.key === "g" || event.key === "G")) {
                    let button = document.getElementById('hidden-google-login')
                    if (button.style.display === "none") {
                        button.style.display = "block";
                    } else {
                        button.style.display = "none";
                    }
                }
            });
        </script>
    </body>
</html>
