<?php
require_once 'config/database.php';
require_once 'model/note.php';
require_once 'login_verify.php';

$db = new Database();
$conn = $db->connect();
$notes = new Note($conn);
$userid = $_SESSION['user_id'];
$notesdata = $notes->getAll($userid);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#E4C3F5" />
  <meta name="description" content="Your personal notes and file vault in PlanPal." />

  <!-- Fonts and Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Caveat&family=Work+Sans:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />

  <!-- Favicon -->
  <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3468/3468371.png" type="image/x-icon" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="CSS/main.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <title>Notes | PlanPal</title>

  <style>
    html, body {
  height: 100%;
  margin: 0;
  padding: 0;
  display: flex;
  flex-direction: column;
}

body > .container {
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
      border: 1px solidrgb(221, 195, 243);
    }

    .dropdown-item {
      color: rgba(0, 0, 0, 1);
    }

    .dropdown-item:hover {
      background-color: #f1dafc;
      color: rgba(61, 60, 63, 1);
    }

    .footer {
      background: #d6a4f0;
      color: white;
      text-align: center;
      padding: 20px;
      width: 100%;
      margin-top: 50px;
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

    .note-form-box {
      position: fixed;
      bottom: 140px;
      left: 50%;
      transform: translateX(-50%);
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      padding: 20px;
      width: 90%;
      max-width: 400px;
      display: none;
      z-index: 1000;
    }

    .note-form-box input,
    .note-form-box textarea,
    .note-form-box button {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .note-form-box button {
      background-color: #7c3aed;
      color: white;
      border: none;
      cursor: pointer;
    }

    .note-item {
      background: white;
      margin-bottom: 10px;
      padding: 15px;
      border-left: 5px solid #7c3aed;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .note-item small {
      color: gray;
    }

    .note-image {
      width: 100px;
      height: auto;
      object-fit: cover;
      border-radius: 8px;
      flex-shrink: 0;
      max-width: 100%;
    }

    .note-text p {
      overflow-y: auto;
      max-height: 150px;
    }


    .bg-af8ece {
      background-color: #af8ece !important;
      border: none !important;
    }
  </style>
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

  <div class="container mt-4">
    <!-- Search Bar -->
    <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
      <!-- Existing Search Bar -->
      <input type="text" id="searchTask" class="form-control" placeholder="Search notes...">
    </div>

    <div class="row" id="notes-list">
      <?php foreach ($notesdata as $note): ?>
        <div class="col-md-4 note-card"
          data-title="<?= htmlspecialchars(strtolower($note['title'])) ?>"
          data-content="<?= htmlspecialchars(strtolower($note['content'])) ?>">

          <div class="note-item d-flex align-items-start">
            <div class="note-text me-3">
              <h5><?= htmlspecialchars($note['title']) ?></h5>
              <p><?= nl2br(htmlspecialchars($note['content'])) ?></p>
              <div class="d-flex mt-2" style="gap: 12px;">
                <button
                  class="btn btn-sm btn-primary edit-note"
                  data-id="<?= $note['id'] ?>"
                  data-title="<?= htmlspecialchars($note['title'], ENT_QUOTES) ?>"
                  data-content="<?= htmlspecialchars($note['content'], ENT_QUOTES) ?>"
                  data-file="<?= htmlspecialchars($note['fileupload']) ?>">Edit</button>

                <button class="btn btn-sm btn-danger delete-note" data-id="<?= $note['id'] ?>">Delete</button>
              </div>
            </div>
            <?php if (!empty($note['fileupload']) && file_exists($note['fileupload'])): ?>
              <img src="<?= htmlspecialchars($note['fileupload']) ?>" alt="Note Image" class="note-image ms-auto">
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>



    <!-- Floating Form -->
    <div id="note-form-box" class="note-form-box">
      <h3 id="formTitle">Create New Note</h3>
      <input type="hidden" id="note_id">

      <input type="text" id="note-title" class="form-control mb-3" placeholder="Note Title">

      <textarea id="note-content" class="form-control mb-3" rows="4" placeholder="Write your note..."></textarea>

      <!-- File Upload Section -->
      <div class="mb-3">
        <label for="file-input" class="form-label"><strong>Upload Image</strong></label>
        <input type="file" name="file" id="file-input" class="form-control">
        <div id="upload-result" class="form-text mt-2 text-muted"></div>
      </div>

      <button onclick="saveNote()" id="saveNoteBtn" class="btn btn-success mt-2">Save Note</button>
    </div>


    <!-- Floating Button -->
    <button class="floating-btn" onclick="toggleNoteForm()">Create a Note +</button>
  </div>
  <?php include 'footer.php'; ?>
  <script src="js/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
      });
      const day = now.getDate();
      const month = now.toLocaleDateString('en-GB', {
        month: 'long'
      });
      const year = now.getFullYear();
      const finalDateStr = `${weekdayFull} ${day} ${month} ${year}`;
      document.getElementById('greeting').textContent = `${getGreeting()}, ${userName} 👋`;
      document.getElementById('dateOnly').textContent = `Today, ${finalDateStr}`;
    }

    updateHeader();
    setInterval(updateHeader, 60000);

    let currentNoteId = null;

    function toggleNoteForm(forceShow = false) {
      const formBox = document.getElementById("note-form-box");
      const isVisible = formBox.style.display === "block";
      if (!isVisible || forceShow) {
        formBox.style.display = "block";
      } else {
        formBox.style.display = "none";
      }
    }

    function saveNote() {
      const title = document.getElementById("note-title").value.trim();
      const content = document.getElementById("note-content").value.trim();
      const id = document.getElementById("note_id").value;
      const fileInput = document.getElementById("file-input");

      if (!title || !content) {
        alert("Please fill in all fields.");
        return;
      }

      const formData = new FormData();
      formData.append("action", id ? "update" : "create");
      formData.append("id", id);
      formData.append("title", title);
      formData.append("content", content);

      if (fileInput.files.length > 0) {
        formData.append("file", fileInput.files[0]);
      }

      $.ajax({
        type: "POST",
        url: "ajax/notecontrol.php",
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            alert(response.message);
            location.reload();
          } else {
            alert(response.message || "An error occurred.");
          }
        },
        error: function() {
          alert("Request failed.");
        }
      });
    }


    $(document).on('click', '.delete-note', function() {
      const noteId = $(this).data('id');
      if (confirm('Are you sure you want to delete this note?')) {
        $.ajax({
          type: "POST",
          url: "ajax/notecontrol.php",
          data: {
            action: 'delete',
            id: noteId
          },
          dataType: 'json',
          success: function(response) {
            if (response.status === 'success') {
              alert(response.message);
              location.reload();
            } else {
              alert('Error: ' + (response.message || 'Failed to delete note'));
            }
          },
          error: function() {
            alert('Server error.');
          }
        });
      }
    });

    $(document).on("click", ".edit-note", function() {
      const id = $(this).data("id");
      const title = $(this).data("title");
      const content = $(this).data("content");
      const file = $(this).data("file");

      $("#note_id").val(id);
      $("#note-title").val(title);
      $("#note-content").val(content);
      $("#formTitle").text("Edit Note");

      // Optional: Show existing image preview
      if (file) {
        $("#upload-result").html(`<img src="${file}" alt="Note Image" class="img-thumbnail mt-2" style="max-height: 150px;">`);
      } else {
        $("#upload-result").html('');
      }

      toggleNoteForm(true);
    });

    document.getElementById('searchTask').addEventListener('input', function() {
      const searchText = this.value.toLowerCase();
      const noteCards = document.querySelectorAll('.note-card');

      noteCards.forEach(card => {
        const title = card.getAttribute('data-title');
        const content = card.getAttribute('data-content');

        if (title.includes(searchText) || content.includes(searchText)) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  </script>


</body>

</html>