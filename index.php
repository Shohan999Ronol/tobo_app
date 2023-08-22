<?php
 include('database.php');


if (isset($_POST['addTask'])) {
    $taskName = $_POST['task_name'];
    $stmt = $conn->prepare("INSERT INTO tasks (task_name, task_status) VALUES (?, 0)");
    $stmt->bind_param("s", $taskName);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['markImportant'])) {
    $taskId = $_GET['markImportant'];
    $stmt = $conn->prepare("UPDATE tasks SET task_status = 1 WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['markDone'])) {
    $taskId = $_GET['markDone'];
    $stmt = $conn->prepare("UPDATE tasks SET task_status = 2 WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['deleteTask'])) {
    $taskId = $_GET['deleteTask'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TO-DO App</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles.css">

</head>
<body>
  <div class="container mt-4">
    <h1>TO-DO App</h1>
    <form action="index.php" method="post">
      <div class="input-group mb-3">
        <input type="text" class="form-control" name="task_name" placeholder="Enter a new task" required>
        <div class="input-group-append">
          <button class="btn btn-primary" type="submit" name="addTask">Add Task</button>
        </div>
      </div>
    </form>
    <ul id="taskList" class="list-group">



      <?php

         include('database.php');
     
        $result = $conn->query("SELECT * FROM tasks");
          while ($row = $result->fetch_assoc()) {
          $taskStatus = $row['task_status'];
          $taskClass = $taskStatus === '1' ? 'list-group-item-warning' : ($taskStatus === '2' ? 'list-group-item-success' : '');
    
          echo "<li class='list-group-item $taskClass d-flex justify-content-between align-items-center'>" . $row['task_name'] . "
            <div>
              <a href='index.php?markImportant={$row['id']}' class='btn btn-warning btn-sm ml-2'>Important</a>
              <a href='index.php?markDone={$row['id']}' class='btn btn-success btn-sm ml-2'>Done</a>
              <a href='index.php?deleteTask={$row['id']}' class='btn btn-danger btn-sm ml-2'>Delete</a>
            </div>
          </li>";
      }

      $conn->close();
      ?>

    </ul>
  </div>

</body>
</html>
