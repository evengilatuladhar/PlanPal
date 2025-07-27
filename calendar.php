<?php
require_once 'config/database.php';
require_once 'model/calendar.php';
require_once 'login_verify.php';

$db = new Database();
$conn = $db->connect();
$calendar = new Calendar($conn);
$userid = $_SESSION['user_id'];
$events = $calendar->getAll($userid);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#E4C3F5" />
  <meta name="description" content="A dynamic and aesthetic Event Calendar WebApp." />

  <!-- Fonts and Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Caveat&family=Work+Sans:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />

  <!-- Favicon -->
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3468/3468371.png" type="image/x-icon" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="CSS/main.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
    }

    body>.container {
      flex: 1;
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
      border: 1px solid rgb(221, 195, 243);
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
      bottom: 70px;
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

    .event-form-box {
      position: fixed;
      bottom: 140px;
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

    .event-form-box input,
    .event-form-box select,
    .event-form-box button {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .event-form-box button {
      background-color: #7c3aed;
      color: white;
      border: none;
      cursor: pointer;
    }

    .event-list {
      padding: 20px;
    }

    .event-item {
      background: white;
      margin-bottom: 10px;
      padding: 15px;
      border-left: 5px solid #7c3aed;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .event-item small {
      color: gray;
    }

    .bg-af8ece {
      background-color: #af8ece !important;
      border: none !important;
    }

    .event-date {
      color: #7c3aed;
      font-weight: bold;
    }

    .footer {
      background: #d6a4f0;
      color: white;
      text-align: center;
      padding: 20px;
      width: 100%;
      margin-top: 50px;
    }
  </style>

  <title>Events | PlanPal</title>
</head>

<body>

  <nav class="navbar navbar-expand-lg" style="width: 100%;">
    <div class="container-fluid">
      <a class="navbar-brand" href="dashboard.php">PlanPal</a>
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

  <!-- Header Section -->
 <div style="width: 100%; padding: 15px 20px; background-color: #fff; text-align: left; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <h2 style="color: #333333; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 0;" id="greeting">
      <!-- Greeting inserted here -->
    </h2>
    <p style="color: #555555; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;" id="dateOnly">
      <!-- Date inserted here -->
    </p>
  </div>

  <!-- With this: -->
  <div class="container mt-4" style="flex: 1;">
    <!-- Search Bar -->
    <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
      <button id="showAllBtn" class="btn btn-secondary">All</button>

      <!-- From Input Group -->
      <div class="input-group w-50">
        <span class="input-group-text">From</span>
        <input type="date" id="dateFilterin" class="form-control">
      </div>

      <!-- To Input Group -->
      <div class="input-group w-50">
        <span class="input-group-text">To</span>
        <input type="date" id="dateFilterout" class="form-control">
      </div>

      <!-- Search Bar -->
      <input type="text" id="searchEvent" class="form-control" placeholder="Search events...">
    </div>


    <!-- Event List -->
    <ul class="list-group" id="eventList">
      <?php foreach ($events as $event): ?>
        <li class="list-group-item d-flex align-items-center justify-content-between"
          data-event-id="<?= $event['id'] ?>"
          data-event-date="<?= $event['event_date'] ?>">
          <div>
            <span class="event-title"><?= htmlspecialchars($event['title']) ?></span>
            <small class="event-date">(<?= htmlspecialchars($event['event_date']) ?>)</small>
          </div>
          <div>
            <button class="btn btn-primary px-3 py-2 me-2 bg-af8ece" onclick="editEvent(this)">Edit</button>
            <button class="btn btn-danger px-3 py-2" onclick="deleteEvent(this)">Delete</button>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Floating Form (Hidden by Default) -->
    <div id="event-form-box" class="event-form-box">
      <h3 id="formTitle">Create New Event</h3>
      <input type="text" id="eventInput" placeholder="Event title" required>
      <input type="date" id="eventDate" required>
      <button onclick="saveEvent()" id="saveEventBtn">Add Event</button>
    </div>

    <!-- Floating Button -->
    <button class="floating-btn" onclick="showEventForm()">Create an Event +</button>
  </div>
  <?php include 'footer.php'; ?>
  </div>
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.validate.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    let currentEventId = null;

    function showEventForm() {
      const formBox = document.getElementById("event-form-box");
      const isHidden = formBox.style.display === "none" || formBox.style.display === "";

      if (isHidden) {
        // Reset form only when opening
        document.getElementById("formTitle").textContent = "Create New Event";
        document.getElementById("saveEventBtn").textContent = "Add Event";
        document.getElementById("eventInput").value = "";
        document.getElementById("eventDate").value = new Date().toISOString().split('T')[0];
        currentEventId = null;
      }

      toggleForm(); // Toggles form visibility
    }

    function toggleForm(forceShow = false) {
      const formBox = document.getElementById("event-form-box");
      const isVisible = formBox.style.display === "block";

      if (!isVisible || forceShow) {
        formBox.style.display = "block";
      } else {
        formBox.style.display = "none";
      }
    }

    function saveEvent() {
      const titleInput = document.getElementById("eventInput");
      const dateInput = document.getElementById("eventDate");

      if (!titleInput || !dateInput) {
        alert("Form elements not found.");
        return;
      }

      const title = titleInput.value.trim();
      const date = dateInput.value;

      if (!title || !date) {
        alert("Please fill in all fields.");
        return;
      }

      const action = currentEventId ? 'update' : 'add';
      const data = {
        action: action,
        title: title,
        event_date: date,
        user_id: <?php echo json_encode($_SESSION['user_id'] ?? 0); ?>
      };

      if (currentEventId) {
        data.id = currentEventId;
      }

      $.ajax({
        url: "ajax/calendar.php",
        type: "POST",
        data: data,
        success: function(response) {
          if (response.status === 'success') {
            alert(response.message);
            location.reload();
          } else {
            alert(response.message || "Operation failed");
          }
        },
        error: function(xhr, status, error) {
          alert("Error: " + error);
          console.error(xhr.responseText);
        }
      });
    }

    function editEvent(button) {
      const listItem = button.closest('li');
      const eventId = listItem.getAttribute('data-event-id');
      const eventTitle = listItem.querySelector('.event-title').textContent;
      const eventDate = listItem.getAttribute('data-event-date');

      document.getElementById("eventInput").value = eventTitle;
      document.getElementById("eventDate").value = eventDate;
      document.getElementById("formTitle").textContent = "Edit Event";
      document.getElementById("saveEventBtn").textContent = "Update Event";

      currentEventId = eventId;
      toggleForm(true);
    }

    function deleteEvent(button) {
      const listItem = button.closest('li');
      const eventId = listItem.getAttribute('data-event-id');

      if (!confirm("Are you sure you want to delete this event?")) return;

      $.ajax({
        url: "ajax/calendar.php",
        type: "POST",
        data: {
          action: 'delete',
          id: eventId
        },
        success: function(response) {
          alert(response.message || "Event deleted!");
          location.reload();
        },
        error: function() {
          alert("Something went wrong while deleting the event.");
        }
      });
    }

    function filterEvents() {
      const searchInput = document.getElementById("searchEvent");
      const dateIn = document.getElementById("dateFilterin");
      const dateOut = document.getElementById("dateFilterout");
      const eventList = document.getElementById("eventList");

      const searchTerm = searchInput.value.trim().toLowerCase();
      const dateInValue = dateIn.value;
      const dateOutValue = dateOut.value;
      const events = eventList.querySelectorAll("li");

      events.forEach(event => {
        const eventTitle = event.querySelector(".event-title").textContent.toLowerCase();
        const eventDate = event.getAttribute("data-event-date");

        // Search filter
        const matchesSearch = searchTerm === '' || eventTitle.includes(searchTerm);

        // Date range filter
        const matchesDate = (dateInValue === '' || eventDate >= dateInValue) &&
          (dateOutValue === '' || eventDate <= dateOutValue);

        // Show/hide
        if (matchesSearch && matchesDate) {
          event.style.cssText = "display: flex !important";
        } else {
          event.style.cssText = "display: none !important";
        }
      });
    }

    function resetFilters() {
      // Clear both filters
      document.getElementById("dateFilterin").value = '';
      document.getElementById("dateFilterout").value = '';
      document.getElementById("searchEvent").value = '';
      // Show all events
      filterEvents();
    }

    document.addEventListener("DOMContentLoaded", function() {
      // Initialize date filter with today's date
      const showAllBtn = document.getElementById("showAllBtn");
      const dateFilter = document.getElementById("dateFilterin");
      const dateFilterout = document.getElementById("dateFilterout");
      const searchInput = document.getElementById("searchEvent");

      // Set default date to empty (shows all events)
      dateFilter.value = '';
      dateFilterout.value = '';

      // Add event listeners
      showAllBtn.addEventListener("click", resetFilters);
      dateFilter.addEventListener("change", filterEvents);
      dateFilterout.addEventListener("change", filterEvents);
      searchInput.addEventListener("keyup", filterEvents);

      // Apply initial filter (shows all events)
      filterEvents();

      // Check if we should show the form after reload
      if (sessionStorage.getItem('showEventForm') === 'true') {
        showEventForm();
        sessionStorage.removeItem('showEventForm');
      }

      // Header initialization
      updateHeader();
      setInterval(updateHeader, 60000);
    });
  </script>

  <script>
    const userName = "<?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>";

    function getGreeting() {
      const hour = new Date().getHours();
      if (hour < 12) return "Good morning";
      if (hour < 17) return "Good afternoon";
      return "Good evening";
    }

    function updateHeader() {
      const now = new Date();
      const weekdayFull = now.toLocaleDateString('en-GB', {
        weekday: 'short'
      }); // e.g. "Thu"
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
  <?php
  if (isset($_GET['focus']) && $_GET['focus'] === 'today') {
    $today = date('Y-m-d');
    // Set the date filter inputs to today's date
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('dateFilterin').value = '$today';
            document.getElementById('dateFilterout').value = '$today';
            filterEvents();
        });
    </script>";
  }
  ?>

</body>

</html>