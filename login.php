<?php
require_once 'config/database.php';
require_once 'model/tododata.php';
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Login | PlanPal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3468/3468371.png" type="image/x-icon" />

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;500;700&display=swap" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body,
        html {
            height: 100%;
            font-family: 'Work Sans', sans-serif;
            background-color: #fff;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* LEFT PANEL */
        .left-panel {
            flex: 1;
            background-color: #af8eceff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
            user-select: none;
        }

        .left-panel .app-name {
            font-family: 'Work Sans', sans-serif;
            font-weight: 700;
            font-size: 4rem;
            letter-spacing: 0.05em;
            margin-bottom: 25px;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.3);
            text-align: center;
            user-select: none;
        }

        .left-panel img.todolist {
            width: 70%;
            max-width: 800px;
            user-select: none;
        }

        /* RIGHT PANEL */
        .right-panel {
            flex: 1;
            background-color: #fefefe;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        /* FORM BOX */
        .form-box {
            background-color: #ffffff;
            padding: 30px 25px;
            border-radius: 20px;
            box-shadow: 0 0 15px rgba(175, 142, 206, 0.3);
            border: 2px solid #e6d6f7;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .form-box img.elephant {
            width: 150px;
            margin-bottom: 15px;
            animation: bounce 2.5s infinite ease-in-out;
            user-select: none;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .form-box h2 {
            font-weight: 700;
            font-size: 28px;
            color: #b973da;
            margin-bottom: 5px;
        }

        .form-box p {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
        }

        form {
            max-width: 320px;
            margin: 0 auto;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 25px;
            border: 1px solid #ccc;
            font-size: 1rem;
            box-sizing: border-box;
            outline-offset: 2px;
        }

        .form-group {
            position: relative;
        }

        .form-group .toggle-password {
            position: absolute;
            top: 12px;
            right: 20px;
            cursor: pointer;
            user-select: none;
            font-size: 18px;
            padding: 3px 6px;
            color: #888;
            transition: color 0.3s ease;
        }

        .form-group .toggle-password:hover {
            color: #b973da;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #af8eceff;
            color: white;
            border: 2px solid #f7c6c7;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        button:hover {
            background-color: #efa5fdff;
            box-shadow: 0 0 10px 2px #f3e7f5ff;
        }

        .signup-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.95rem;
            user-select: none;
        }

        .signup-link a {
            color: #b973da;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        #login_msg {
            max-width: 320px;
            margin: 0 auto 15px;
            font-size: 14px;
            text-align: center;
            min-height: 20px;
            user-select: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="left-panel">
            <h1 class="app-name">PlanPal</h1>
            <img src="images/girl-todolist.svg" alt="Todolist Girl" class="todolist" />
        </div>

        <div class="right-panel">
            <div class="form-box">
                <img src="images/Elephant.png" alt="Smiling Elephant" class="elephant" />
                <h2>Hello Lovely!</h2>
                <p>Welcome! Please login</p>

                <form id="login-form">
                    <input type="text" name="username" placeholder="Username or Email" autocomplete="off" required />
                    <div class="form-group">
                        <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required />
                        <span class="toggle-password" onclick="togglePassword()" title="Show/hide password">🙈</span>
                    </div>
                    <div id="login_msg"></div>
                    <button type="submit" id="login-submit">Login</button>
                    <div class="signup-link">
                        <span>Don't have an account? </span>
                        <a href="register.php">Sign up</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const toggle = document.querySelector('.toggle-password');

            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = '👁️';
            } else {
                input.type = 'password';
                toggle.textContent = '🙈';
            }
        }

        jQuery(document).ready(function() {
            jQuery('#login-form').validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 2
                    },
                    password: {
                        required: true,
                        minlength: 2
                    }
                },
                messages: {
                    username: {
                        required: "Enter username",
                        minlength: "Min 2 characters"
                    },
                    password: {
                        required: "Enter password",
                        minlength: "Min 2 characters"
                    }
                },
                submitHandler: function(form) {
                    const Frmval = jQuery(form).serialize();
                    jQuery("#login-submit").attr("disabled", true).html('Logging in...');

                    jQuery.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "ajax/tododata.php",
                        data: "action=login&" + Frmval,
                        success: function(response) {
                            jQuery("#login-submit").removeAttr("disabled").html('Login');
                            if (response.status === "success") {
                                jQuery('#login_msg').css("color", "green").html(response.message).fadeIn();
                                setTimeout(function() {
                                    window.location.href = "dashboard.php";
                                }, 2000);
                            } else {
                                jQuery('#login_msg').css("color", "red").html(response.message).fadeIn();
                            }
                        },
                        error: function() {
                            jQuery("#login-submit").removeAttr("disabled").html('Login');
                            jQuery('#login_msg').css("color", "red").html("Something went wrong.").fadeIn();
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>

</html>