<?php
require_once 'config/database.php';
require_once 'model/tododata.php';
require_once 'model/note.php';
require_once 'model/calendar.php';
require_once 'login_verify.php';

$db = new Database();
$conn = $db->connect();

// Get counts for dashboard
$todo = new Todo($conn);
$notes = new Note($conn);
$calendar = new Calendar($conn);

$userid = $_SESSION['user_id'];
$todoCount = count($todo->getAll_user($userid));
$completedTodos = $todo->getAll_active($userid); // Assuming this returns completed tasks
$pendingTodos = $todo->getAll_done($userid);

$completedCount = count($completedTodos);
$pendingCount = count($pendingTodos);
$notesCount = count($notes->getAll($userid));
$eventsCount = count($calendar->getAll($userid));
$allEvents = $calendar->getAll($userid);
$eventsCount = count($allEvents);

// Get today's events
$today = date('Y-m-d');
$todaysEvents = array_filter($allEvents, function ($event) use ($today) {
  return $event['event_date'] == $today;
});
$todaysEventsCount = count($todaysEvents);
?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="theme-color" content="#E4C3F5" />
<meta name="description" content="A dynamic and aesthetic To-Do List WebApp." />


<link href="https://fonts.googleapis.com/css2?family=Caveat&family=Work+Sans:wght@300&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />

<link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3468/3468371.png" type="image/x-icon" />

<link rel="stylesheet" href="CSS/main.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<head>
  <style>
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

    .footer {
      background: #d6a4f0;
      color: white;
      text-align: center;
      padding: 20px;
      width: 100%;
      margin-top: 50px;
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

    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background: #fdf6ff;
    }

    #header {
      background: rgb(207, 175, 245);
      color: white;
      padding: 20px;
      text-align: center;
    }

    .floating-btn {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #af8ece;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      cursor: pointer;
      box-shadow: 0 5px 12px rgba(0, 0, 0, 0.2);
    }

    .task-form-box {
      position: fixed;
      bottom: 90px;
      left: 50%;
      transform: translateX(-50%);
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      padding: 20px;
      width: 90%;
      max-width: 350px;
      display: none;
      z-index: 1000;
    }

    .task-form-box input,
    .task-form-box select,
    .task-form-box button {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .task-form-box button {
      background-color: #7c3aed;
      color: white;
      border: none;
      cursor: pointer;
    }

    .task-list {
      padding: 20px;
    }

    .task-item {
      background: white;
      margin-bottom: 10px;
      padding: 15px;
      border-left: 5px solid #7c3aed;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .task-item small {
      color: gray;
    }

    .bg-af8ece {
      background-color: #af8ece !important;
      border: none !important;
    }

    .form-check-input {
      margin-top: 14px !important;
    }

    /* Add dashboard specific styles */
    .dashboard-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      border-left: 5px solid #7c3aed;
    }

    .dashboard-card:hover {
      transform: translateY(-5px);
    }

    .card-icon {
      font-size: 2.5rem;
      color: #7c3aed;
      margin-bottom: 15px;
    }

    .card-count {
      font-size: 2rem;
      font-weight: bold;
      color: #333;
    }

    .card-title {
      color: #555;
      margin-bottom: 5px;
    }

    .card-link {
      color: #7c3aed;
      text-decoration: none;
      font-weight: 500;
    }

    .card-subtext {
      color: #777;
      font-size: 0.9rem;
      margin-top: 5px;
    }

    .dashboard-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 30px;
    }

    .progress-container {
      background: #f0f0f0;
      border-radius: 10px;
      height: 10px;
      margin: 10px 0;
    }

    .progress-bar {
      background: #7c3aed;
      height: 100%;
      border-radius: 10px;
    }

    .today-badge {
      background: #ff6b6b;
      color: white;
      padding: 3px 8px;
      border-radius: 20px;
      font-size: 0.8rem;
      margin-left: 10px;
    }
  </style>
  <title>Dashboard | PlanPal</title>
</head>

<body>
  <nav class="navbar navbar-expand-lg" style="width: 100%;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">PlanPal</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="dashboard.php" id="userDropdown" role="button" data-bs-toggle="dropdown">
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

  <div style="padding: 10px 20px; background-color: #fff; width: 100%; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <h2 style="color: #333333; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 0;" id="greeting"></h2>
    <p style="color: #555555; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;" id="dateOnly"></p>
  </div>

  <div class="container mt-4">
    <img src="images/Elephant.png" alt="Smiling Elephant" class="elephant" style="width: 100px;" />

    <h3 class="mb-4">Your Day at a Glance!🌸</h3>

    <div class="dashboard-row">

      <div class="dashboard-card" onclick="location.href='index.php'" style="cursor: pointer;">
        <div class="card-icon">📝</div>
        <div class="card-count"><?= $todoCount ?></div>
        <h4 class="card-title">Tasks</h4>

        <div class="progress-container">
          <div class="progress-bar" style="width: <?= $todoCount > 0 ? round(($completedCount / $todoCount) * 100) : 0 ?>%"></div>
        </div>

        <div class="d-flex justify-content-between mt-2">
          <span class="card-subtext">✅ <?= $completedCount ?> completed</span>
          <span class="card-subtext">⏳ <?= $pendingCount ?> pending</span>
        </div>

        <div class="card-link">Manage Todos →</div>
      </div>

      <div class="dashboard-card" onclick="location.href='notes.php'" style="cursor: pointer;">
        <div class="card-icon">📒</div>
        <div class="card-count"><?= $notesCount ?></div>
        <h4 class="card-title">Notes</h4>
        <p class="card-subtext">Last updated: <?= $notesCount > 0 ? 'Recently' : 'Never' ?></p>
        <div class="card-link">View Notes →</div>
      </div>

      <div class="dashboard-card" onclick="location.href='calendar.php'" style="cursor: pointer;">
        <div class="card-icon">📅</div>
        <div class="card-count"><?= $eventsCount ?></div>
        <h4 class="card-title">
          Events
          <?php if ($todaysEventsCount > 0): ?>
            <span class="today-badge"><?= $todaysEventsCount ?> today</span>
          <?php endif; ?>
        </h4>
        <div class="card-link">View Events →</div>
      </div>

    </div>
  </div>
  </div>

  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.validate.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Your existing JavaScript for header updates
    const userName = "<?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>";

    function getGreeting() {
      const hour = new Date().getHours();
      if (hour < 12) return "Good Morning";
      if (hour < 17) return "Good Afternoon";
      return "Good Evening";
    }

    function updateHeader() {
      const now = new Date();
      const weekdayFull = now.toLocaleDateString('en-GB', {
        weekday: 'short'
      });
      const day = now.getDate();
      const month = now.toLocaleDateString('en-GB', {
        month: 'long'
      });
      const year = now.getFullYear();

      const weekdayDisplay = (weekdayFull === "Thu") ? "Thurs" : weekdayFull;
      const finalDateStr = `${weekdayDisplay} ${day} ${month} ${year}`;

      document.getElementById('greeting').textContent = `${getGreeting()}, ${userName} 👋`;
      document.getElementById('dateOnly').textContent = `Today, ${finalDateStr}`;
    }

    updateHeader();
    setInterval(updateHeader, 60000);
  </script>
</body>
<?php include 'footer.php'; ?>

</html>