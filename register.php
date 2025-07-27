<?php
require_once 'config/database.php';
require_once 'model/tododata.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Favicon -->
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3468/3468371.png" type="image/x-icon" />

  <meta name="theme-color" content="#af8eceff" />
  <title>Register | PlanPal</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;500;700&display=swap" rel="stylesheet" />

  <style>
    body {
      margin: 0;
      font-family: 'Work Sans', sans-serif;
      background-color: #fff;
      display: flex;
      min-height: 100vh;
    }

    .left-panel {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
      background-color: #fefefe;
    }

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

    .form-box img {
      width: 150px;
      margin-bottom: 15px;
      animation: bounce 2.5s infinite ease-in-out;
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

    /* Wrap each label + input */
    .form-group {
      max-width: 320px;
      margin: 0 auto 15px;
      text-align: left;
    }

    .form-group label {
      display: block;
      font-size: 14px;
      color: #333;
      margin-bottom: 5px;
    }

    .form-group input {
      width: 100%;
      padding: 12px 15px;
      border-radius: 25px;
      border: 1px solid #ccc;
      background-color: #fafafa;
      font-size: 14px;
      box-sizing: border-box;
    }

    .form-group input::placeholder {
      color: #aaa;
    }

    button {
      width: 320px;
      padding: 12px;
      background-color: #af8eceff;
      color: white;
      border: 2px solid #f7c6c7;
      border-radius: 25px;
      font-weight: 600;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s ease;
      margin: 10px auto 0;
      display: block;
    }

    button:hover {
      background-color: #efa5fdff;
      box-shadow: 0 0 10px 2px #f3e7f5ff;
    }

    .login-link {
      font-size: 13px;
      margin-top: 12px;
      color: #666;
      text-align: center;
    }

    .login-link a {
      color: #b973da;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
    }

    .right-panel {
      flex: 1;
      background-color: #af8eceff;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      /* Shift content upward */
      align-items: center;
      padding: 60px 40px 20px;
      /* more top padding, less bottom */
      color: white;
      user-select: none;
    }

    .right-panel .app-name {
      font-family: 'Work Sans', sans-serif;
      font-weight: 700;
      font-size: 4rem;
      letter-spacing: 0.05em;
      margin-bottom: 25px;
      margin-top: 0;
      /* Remove default top margin */
      text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.3);
    }

    .right-panel img.todolist {
      width: 70%;
      max-width: 800px;
      margin-top: 0;
      /* Remove any extra margin on top */
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

    #register_msg {
      max-width: 320px;
      margin: 0 auto 10px;
      font-size: 14px;
      text-align: center;
    }

    .validate-has-error {
      color: red;
      font-size: 13px;
      margin-top: -10px;
      margin-bottom: 10px;
      display: block;
    }
  </style>
</head>

<body>
  <div class="left-panel">
    <div class="form-box">
      <img src="https://purpledaisydesign.com/wp-content/uploads/2021/05/N-2584-Cute-Baby-Elephant-colored.jpg" alt="Elephant Cartoon" />
      <h2>About You</h2>
      <p>Create your PlanPal account</p>

      <form id="register-form" method="POST">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" id="name" name="name" placeholder="Your name" />
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Your email" />
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Choose a username" />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Create a password" />
        </div>

        <div id="register_msg"></div>

        <button type="submit" id="register-submit">Register</button>

        <div class="login-link">
          Already registered? <a href="login.php">Login</a>
        </div>
      </form>
    </div>
  </div>

  <div class="right-panel">
    <h1 class="app-name">PlanPal</h1>
    <img src="images/girl-todolist.svg" alt="Todolist Girl" class="todolist" />
  </div>

  <!-- jQuery and Validation scripts -->
  <script src="js/jquery.min.js" type="text/javascript"></script>
  <script src="js/jquery.validate.js" type="text/javascript"></script>
  <script>
    jQuery(document).ready(function() {
      jQuery('#register-form').validate({
        errorElement: 'span',
        errorClass: 'validate-has-error',
        rules: {
          name: {
            required: true,
            minlength: 2
          },
          email: {
            required: true,
            email: true
          },
          username: {
            required: true,
            minlength: 2
          },
          password: {
            required: true,
            minlength: 4
          }
        },
        messages: {
          name: {
            required: "Required",
            minlength: "Minimum 2 characters"
          },
          email: {
            required: "Required",
            email: "Enter valid email"
          },
          username: {
            required: "Required",
            minlength: "Minimum 2 characters"
          },
          password: {
            required: "Required",
            minlength: "Minimum 4 characters"
          }
        },
        submitHandler: function(form) {
          var Frmval = jQuery("#register-form").serialize();
          jQuery("#register-submit").attr("disabled", true).html('Registering...');

          jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "ajax/tododata.php",
            data: "action=register&" + Frmval,
            success: function(data) {
              jQuery("#register-submit").removeAttr("disabled").html('Register');
              jQuery('#register_msg').html(data.message)
                .addClass('alert alert-' + (data.status === 'success' ? 'success' : 'danger'))
                .fadeIn().delay(3000).fadeOut();

              if (data.status === 'success') {
                jQuery("#register-form")[0].reset();
                setTimeout(function() {
                  window.location.href = "login.php";
                }, 4000);
              }
            }
          });

          return false;
        }
      });
    });
  </script>
</body>

</html>