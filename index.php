<?php

include 'database.php';

class TaskManager // MULAINYA INHERITANCE
{
    private $conn;

    public function __construct($conn) // BERJALANNYA CONSTRUCTOR
    {
        $this->conn = $conn;
    }

    public function addTask($taskLabel)
    {
        $q_insert = "INSERT INTO tasks (tasklabel, taskstatus) VALUES ('$taskLabel', 'open')";
        $run_q_insert = mysqli_query($this->conn, $q_insert);

        return $run_q_insert;
    }

    public function getTasks()
    {
        $q_select = "SELECT * FROM tasks ORDER BY taskid DESC";
        $run_q_select = mysqli_query($this->conn, $q_select);

        return $run_q_select;
    }

	public function deleteTask($taskId)
    {
        // TERJADINYA OVERRIDING
        $q_delete = "DELETE FROM tasks WHERE taskid = '$taskId'";
        $run_q_delete = mysqli_query($this->conn, $q_delete);

        return $run_q_delete;
    }


    public function updateTaskStatus($taskId, $status)
    {
        $q_update = "UPDATE tasks SET taskstatus = '$status' WHERE taskid = '$taskId'";
        $run_q_update = mysqli_query($this->conn, $q_update);

        return $run_q_update;
    }
}

$taskManager = new TaskManager($conn); // BERJALANNYA ENKAPSULASI
										// FINISH INHERITANCE

if (isset($_POST['add'])) {
    $taskLabel = $_POST['task'];
    $result = $taskManager->addTask($taskLabel);

    if ($result) {
        header('Refresh:0; url=index.php');
    }
}

// Fetch tasks using the TaskManager class
$run_q_select = $taskManager->getTasks();

// Process delete data using the TaskManager class
if (isset($_GET['delete'])) {
    $result = $taskManager->deleteTask($_GET['delete']);

    if ($result) {
        header('Refresh:0; url=index.php');
    }
}

// Process update data (close or open) using the TaskManager class
if (isset($_GET['done'])) {
    $status = ($_GET['status'] == 'open') ? 'close' : 'open';
    $result = $taskManager->updateTaskStatus($_GET['done'], $status);

    if ($result) {
        header('Refresh:0; url=index.php');
    }
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>To Do List</title>
	<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

		* {
			padding: 0;
			margin: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Roboto', sans-serif;
			background: url('bgindex.jpg') no-repeat center center fixed;
			background-size: cover;
		}

		.container {
			width: 590px;
			height: 100vh;
			margin: 0 auto;
		}

		.header {
			padding: 15px;
			color: #fff;
		}

		.header .title {
			display: flex;
			align-items: center;
			margin-bottom: 7px;
		}

		.header .title i {
			font-size: 24px;
			margin-right: 10px;
		}

		.header .title span {
			font-size: 18px;
		}

		.header .description {
			font-size: 13px;
		}

		.content {
			padding: 15px;
		}

		.card {
			background-color: #fff;
			padding: 15px;
			border-radius: 5px;
			margin-bottom: 10px;
		}

		.input-control {
			width: 100%;
			display: block;
			padding: 0.5rem;
			font-size: 1rem;
			margin-bottom: 10px;
		}

		.text-right {
			text-align: right;
		}

		button {
			padding: 0.5rem 1rem;
			font-size: 1rem;
			cursor: pointer;
			background: #667db6;
			/* fallback for old browsers */
			background: -webkit-linear-gradient(to right, #667db6, #0082c8, #0082c8, #667db6);
			/* Chrome 10-25, Safari 5.1-6 */
			background: linear-gradient(to right, #667db6, #0082c8, #0082c8, #667db6);
			/* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
			color: #fff;
			border: 1px solid;
			border-radius: 3px;
		}

		.task-item {
			display: flex;
			justify-content: space-between;
		}

		.text-orange {
			color: black;
			font-size: 1.5rem;
		}

		.text-red {
			color: black;
			font-size: 1.5rem;
		}

		.task-item.done span {
			text-decoration: line-through;
			color: #ccc;
		}

		@media (max-width: 768px) {
			.container {
				width: 100%;
			}
		}
	</style>
</head>

<body>

	<div class="container">

		<div class="header">

			<div class="title">
				<i class='bx bx-sun'></i>
				<span>To Do List</span>
			</div>

			<div class="description">
				<?= date("l, d M Y") ?>
			</div>

		</div>

		<div class="content">

			<div class="card">

				<form action="" method="post">

					<input type="text" name="task" class="input-control" placeholder="Add task">

					<div class="text-right">
						<button type="submit" name="add">Add</button>
					</div>

				</form>

			</div>
			<?php


			if (mysqli_num_rows($run_q_select) > 0) {
				while ($r = mysqli_fetch_array($run_q_select)) {
			?>
					<div class="card">
						<div class="task-item <?= $r['taskstatus'] == 'close' ? 'done' : '' ?>">
							<div>
								<input type="checkbox" onclick="window.location.href = '?done=<?= $r['taskid'] ?>&status=<?= $r['taskstatus'] ?>'" <?= $r['taskstatus'] == 'close' ? 'checked' : '' ?>>
								<span><?= $r['tasklabel'] ?></span>
							</div>
							<div>
								<a href="edit.php?id=<?= $r['taskid'] ?>" class="text-orange" title="Edit"><i class="bx bx-edit"></i></a>
								<a href="?delete=<?= $r['taskid'] ?>" class="text-red" title="Remove" onclick="return confirm('Are you sure ?')"><i class="bx bx-trash"></i></a>
							</div>
						</div>
					</div>
				<?php }
			} else { ?>
				<div>Belum ada task</div>
			<?php } ?>


		</div>

	</div>

</body>

</html>
