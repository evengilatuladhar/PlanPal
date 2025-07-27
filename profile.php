<?php


require_once 'login_verify.php';
require_once 'config/database.php';
require_once 'model/User.php';

$db = new Database();
$conn = $db->connect();
$user = new User($conn);
$currentUser = $user->getById($_SESSION['user_id']); // fetch current data
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#062e3f">
  <meta name="Description" content="A dynamic and aesthetic To-Do List WebApp.">

  <!-- Google Font: Quick Sand -->
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Caveat&family=Work+Sans:wght@300&display=swap" rel="stylesheet">

  <!-- font awesome (https://fontawesome.com) for basic icons; source: https://cdnjs.com/libraries/font-awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css"
    integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />

   <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3468/3468371.png" type="image/x-icon" />
  <link rel="stylesheet" href="CSS/main.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <title>Profile | PlanPal</title>


</head>
<style>
  html,
  body {
    height: 100%;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
  }

  form#profile-form {
    display: block;
  }

  /* Stylish centered form card */
  .profile-card {
    background-color: rgba(233, 194, 235, 0.08);
    /* soft light gray */
    border: 1px solid rgba(237, 206, 245, 0.2);
    /* soft white border */
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    /* subtle shadow */
    backdrop-filter: blur(5px);
    /* nice frosted glass effect */
    -webkit-backdrop-filter: blur(5px);
    color: #fff;
  }

  .form-label {
    color: black;
  }

  .formtitle {
    color: black;
  }

  body {
    font-family: 'Work Sans', sans-serif;
    background-color: #f8f2fc;
    color: #333;
  }

  .navbar {
    background: linear-gradient(90deg, #d6a4f0, #fbc2eb);
  }

  .navbar-brand {
    font-family: 'Caveat', cursive;
    font-size: 2rem;
    color: #fff;
  }

  .dropdown-menu {
    background-color: #fff0fc;
    border: 1px solidrgb(221, 195, 243);
  }

  .dropdown-item {
    color: rgba(0, 0, 0, 1);
  }

  .dropdown-item:hover {
    background-color: #f1dafc;
    color: rgba(61, 60, 63, 1);
  }

  .btn-lavender {
    background-color: #af8ece;
    color: black;
    border: none;
  }

  .btn-lavender:hover {
    background-color: #efa5fdff;
  }

  .navbar-brand {
    font-family: 'Caveat', cursive;
    font-size: 2rem;
    color: #fff;
  }

  .footer {
    background: #d6a4f0;
    color: white;
    text-align: center;
    padding: 20px;
    width: 100%;
    margin-top: 50px;
  }

  body,
  html {
    margin: 0;
    padding: 0;
  }

  @keyframes bounce {

    0%,
    100% {
      transform: translateY(0);
    }

    50% {
      transform: translateY(-20px);
    }
  }
</style>

<body>


  <nav class="navbar navbar-expand-lg" style="width: 100%;">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">PlanPal</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="index.php">📝 Todo List</a></li>
              <li><a class="dropdown-item" href="notes.php">📒 Notes</a></li>
              <li><a class="dropdown-item" href="calendar.php">📅 Calendar</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="profile.php">👤 Profile</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="logout.php">🚪 Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div id="header">
    <div style="text-align: center; margin-bottom: 10px;">
      <img src="images/Elephant.png" alt="Cute Baby Elephant"
        style="max-width: 150px; height: auto; animation: bounce 2.5s infinite ease-in-out;">
    </div>
    <h2>Your Profile</h2>
  </div><b></b>
  <!-- #region -->
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="profile-card">
          <h3 class="mb-4 text-center formtitle">Edit Profile</h3>

          <form id="profile-form">
            <input type="hidden" name="id" value="<?= $currentUser->id ?>">

            <div class="mb-3">
              <label for="name" class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control" id="name" value="<?= htmlspecialchars($currentUser->name) ?>">
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($currentUser->email) ?>">
            </div>

            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" name="username" class="form-control" id="username" value="<?= htmlspecialchars($currentUser->username) ?>">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">New Password</label>
              <input type="password" name="password" class="form-control" id="password">
            </div>

            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input type="password" name="confirm_password" class="form-control" id="confirm_password">
            </div>

            <div id="profile_msg" class="mb-3"></div>
            <button type="submit" id="save-profile" class="btn btn-lavender w-100">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>
  </div>


  <script src="JS/main.js" type="text/javascript"> </script>
  <script src="js/jquery.min.js" type="text/javascript"> </script>
  <script src="js/jquery.validate.js" type="text/javascript"> </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    .validate-has-error {
      color: red;
      font-size: 0.875em;
      margin-top: 0.25rem;
      display: block;
    }
  </style>
  <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery('#profile-form').validate({
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
            minlength: 4
          },
          confirm_password: {
            equalTo: "#password"
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
            minlength: "Minimum 4 characters"
          },
          confirm_password: {
            equalTo: "Passwords do not match"
          }
        },
        submitHandler: function(form) {
          var formData = jQuery("#profile-form").serialize();
          jQuery("#save-profile").attr("disabled", true).html('Saving...');

          jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "ajax/tododata.php",
            data: "action=updateProfile&" + formData,
            success: function(data) {
              jQuery("#save-profile").removeAttr("disabled").html('I Got This!');
              jQuery('#profile_msg')
                .html(data.message)
                .removeClass()
                .addClass('alert alert-' + (data.status === 'success' ? 'success' : 'danger'))
                .fadeIn().delay(3000).fadeOut();
            }
          });

          return false;
        }
      });
    });
  </script>

</body>

</html>