<?php
require_once 'config/database.php';
require_once 'model/tododata.php';
require_once 'login_verify.php';

$db = new Database();
$conn = $db->connect();
$todo = new Todo($conn);
$userid = $_SESSION['user_id'];
$todos = $todo->getAll_done($userid);
$tododones = $todo->getAll_active($userid);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#E4C3F5" />
  <meta name="description" content="A dynamic and aesthetic To-Do List WebApp." />

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

    .task-form-box {
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

    .footer {
      background: #d6a4f0;
      color: white;
      text-align: center;
      padding: 20px;
      width: 100%;
      margin-top: 50px;
    }
  </style>

  <title>Todo | PlanPal</title>
</head>

<body>

  <nav class="navbar navbar-expand-lg" style="width: 100%;">
    <div class="container-fluid">
      <a class="navbar-brand" href="dashboard.php">PlanPal</a>
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

 <!-- Greeting Bar -->
<div style="width: 100%; padding: 15px 20px; background-color: #fff; text-align: left; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
  <h2 id="greeting" style="margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333;"></h2>
  <p id="dateOnly" style="margin: 0; font-size: 0.95rem; color: #666;"></p>
</div>

  <div class="container mt-4">
    <!-- Search Bar -->
    <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
      <!-- Category Filter Dropdown -->
      <select id="categoryFilter" class="form-select w-auto">
        <option value="All">All</option>
        <option value="Personal">Personal</option>
        <option value="Work">Work</option>
        <option value="Study">Study</option>
        <option value="Fitness">Fitness</option>
        <option value="Others">Others</option>
      </select>

      <!-- Existing Search Bar -->
      <input type="text" id="searchTask" class="form-control" placeholder="Search tasks...">
    </div>


    <!-- Task List -->
    <ul class="list-group" id="taskList">

      <?php foreach ($todos as $todo): ?>
        <?php $taskId = 'task' . $todo->id; ?>
        <li class="list-group-item d-flex align-items-center justify-content-between" data-task-type="<?= $todo->type ?>" data-task-id="<?= $todo->id ?>">
          <div class="form-check">
            <input class="form-check-input task-check" type="checkbox" id="<?= $taskId ?>">
            <input type="text" class="form-control-plaintext ms-2 task-text d-none" value="<?= htmlspecialchars($todo->title) ?>" readonly>
            <p class="mt-2"><?= htmlspecialchars($todo->title) ?> (<?= htmlspecialchars($todo->type) ?>)</p>
            <input type="hidden" class="task-id" value="<?= $todo->id ?>">
          </div>
          <div>
            <button class="btn btn-primary px-3 py-2 me-2 bg-af8ece" onclick="editTask(this)">Edit</button>
            <button class="btn btn-danger px-3 py-2" onclick="deleteTask(this)">Delete</button>
          </div>
        </li>
      <?php endforeach; ?>

      <?php foreach ($tododones as $todo): ?>
        <?php $taskId = 'task' . $todo->id; ?>
        <li class="list-group-item d-flex align-items-center justify-content-between" data-task-type="<?= $todo->type ?>" data-task-id="<?= $todo->id ?>">
          <div class="form-check">
            <input class="form-check-input task-check" type="checkbox" id="<?= $taskId ?>" checked>
            <input type="text" class="form-control-plaintext ms-2 task-text d-none" value="<?= htmlspecialchars($todo->title) ?>" readonly
              style="text-decoration: line-through; color: rgb(136, 136, 136);">
            <p class="mt-2" style="text-decoration: line-through; color: rgb(136, 136, 136);"><?= htmlspecialchars($todo->title) ?> (<?= htmlspecialchars($todo->type) ?>)</p>
            <input type="hidden" class="task-id" value="<?= $todo->id ?>">
          </div>
          <div>
            <button class="btn btn-primary px-3 py-2 me-2 bg-af8ece" onclick="editTask(this)">Edit</button>
            <button class="btn btn-danger px-3 py-2" onclick="deleteTask(this)">Delete</button>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>

    <!-- Floating Form (Hidden by Default) -->
    <div id="task-form-box" class="task-form-box">
      <h3 id="formTitle">Create New Task</h3>
      <input type="text" id="taskInput" placeholder="Add your new task!" required>
      <select id="taskType">
        <option value="Personal">Personal</option>
        <option value="Work">Work</option>
        <option value="Study">Study</option>
        <option value="Fitness">Fitness</option>
        <option value="Others">Others</option>
      </select>
      <button onclick="addTask()" id="addTaskBtn">Add Task</button>
    </div>

    <!-- Floating Button -->
    <button class="floating-btn" onclick="createNewTask()">Create a Task +</button>
  </div>
  <?php include 'footer.php'; ?>
  </div>
  <!-- <p><span id="datetime"></span></p> -->

  <!-- <script src="JS/time.js"></script> -->
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.validate.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="JS/todo.js"></script> -->

  <script>
    function filterTasks(category) {
      const tasks = document.querySelectorAll("#taskList li");

      tasks.forEach(task => {
        const taskCategory = task.getAttribute("data-category") || "";
        const matches = category === "All" || taskCategory.toLowerCase() === category.toLowerCase();
        task.style.display = matches ? "flex" : "none";
      });
    }

    let currentTaskElement = null;

    function toggleForm(forceShow = false) {
      const formBox = document.getElementById("task-form-box");
      const isVisible = formBox.style.display === "block";

      if (!isVisible || forceShow) {
        formBox.style.display = "block";
      } else {
        formBox.style.display = "none";
      }
    }

    function createNewTask() {
      const formBox = document.getElementById("task-form-box");
      const isHidden = formBox.style.display === "none" || formBox.style.display === "";

      if (isHidden) {
        // Reset form only when opening
        document.getElementById("formTitle").textContent = "Create New Task";
        document.getElementById("addTaskBtn").textContent = "Add Task";
        document.getElementById("taskInput").value = "";
        document.getElementById("taskType").value = "Personal";
        currentTaskElement = null;
      }

      toggleForm(); // Toggles form visibility
    }

    let currentTaskId = null;

    function addTask() {
      const input = document.getElementById("taskInput").value.trim();
      const type = document.getElementById("taskType").value;

      if (!input) {
        alert("Please enter task.");
        return;
      }

      if (currentTaskId) {
        // Update existing task
        // Make an AJAX call to update the task in the database
        $.ajax({
          url: "ajax/tododata.php",
          type: "POST",
          data: {
            action: 'update',
            id: currentTaskId,
            title: input,
            type: type
          },
          success: function(response) {
            alert("Task updated!");
            location.reload();
          },
          error: function() {
            alert("Something went wrong while updating the task.");
          }
        });
      } else {
        // Add new task
        $.ajax({
          url: "ajax/tododata.php",
          type: "POST",
          data: {
            action: 'add',
            title: input,
            type: type
          },
          success: function(response) {
            alert("Task added!");
            location.reload(); // reload to see new task
          },
          error: function() {
            alert("Something went wrong while adding the task.");
          }
        });
      }

      // Clear and hide form
      document.getElementById("taskInput").value = "";
      currentTaskId = null; // Reset the current task ID
      toggleForm();
    }
  </script>

  <script>
    // Mark task done

    document.querySelectorAll('.task-check').forEach((checkbox) => {
      checkbox.addEventListener('change', function() {
        const taskText = this.closest('.form-check').querySelector('.task-text');
        const listItem = this.closest('li');
        const taskId = listItem.getAttribute('data-task-id');

        if (!taskId) {
          alert("Missing task ID.");
          return;
        }

        const isChecked = this.checked;
        const newStatus = isChecked ? 1 : 0;

        // Update visuals
        taskText.style.textDecoration = isChecked ? "line-through" : "none";
        taskText.style.color = isChecked ? "#888" : "#000";

        // Send AJAX request
        $.ajax({
          url: "ajax/tododata.php",
          type: "POST",
          data: {
            action: 'updateStatus',
            id: taskId,
            status: newStatus
          },
          success: function(response) {
            alert(response.message || "❌ Could not update status.");
            location.reload();
          },
          error: function() {
            alert("Failed to update task status.");
          }
        });
      });
    });


    function editTask(button) {
      const listItem = button.closest('li');
      const taskTextInput = listItem.querySelector('.task-text');
      const taskId = listItem.querySelector('.task-id');
      const taskType = listItem.getAttribute('data-task-type');

      const taskInput = document.getElementById("taskInput");
      const taskTypeSelect = document.getElementById("taskType");
      const formTitle = document.getElementById("formTitle");
      const addTaskBtn = document.getElementById("addTaskBtn");

      // ✅ Populate form
      taskInput.value = taskTextInput.value;
      taskTypeSelect.value = taskType;

      // ✅ Update form text
      formTitle.textContent = "Update Task";
      addTaskBtn.textContent = "Update Task";

      currentTaskId = taskId.value;

      // ✅ Show the form
      toggleForm(true);
    }

    function deleteTask(button) {
      const listItem = button.closest('li');
      const taskId = listItem.getAttribute('data-task-id');

      if (!taskId) {
        alert("Task ID not found.");
        return;
      }

      if (!confirm("Are you sure you want to delete this task?")) return;

      $.ajax({
        url: "ajax/tododata.php",
        type: "POST",
        data: {
          action: 'delete',
          id: taskId
        },
        success: function(response) {
          alert(response.message);
          location.reload();
        },
        error: function() {
          alert("Something went wrong while deleting the task.");
        }
      });
    }

    function filterTasksCombined() {
      const searchTerm = document.getElementById("searchTask").value.toLowerCase();
      const selectedCategory = document.getElementById("categoryFilter").value;
      const tasks = document.querySelectorAll("#taskList li");

      tasks.forEach(task => {
        const taskTextInput = task.querySelector(".task-text");
        const taskType = task.getAttribute("data-task-type");

        if (!taskTextInput) return;

        const taskText = taskTextInput.value.toLowerCase();
        const matchesSearch = taskText.includes(searchTerm);
        const matchesCategory = selectedCategory === "All" || taskType === selectedCategory;

        console.log("Search:", searchTerm, "Category:", selectedCategory);
        console.log("Text:", taskText, "Type:", taskType);

        // ✅ Only show if BOTH filters match
        if (matchesSearch && matchesCategory) {
          task.classList.remove("d-none");
        } else {
          task.classList.add("d-none");
        }
      });
    }

    // Attach event listeners AFTER DOM is loaded
    document.addEventListener("DOMContentLoaded", function() {
      document.getElementById("searchTask").addEventListener("keyup", filterTasksCombined);
      document.getElementById("categoryFilter").addEventListener("change", filterTasksCombined);
    });
  </script>

  <script>
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

</body>

</html>