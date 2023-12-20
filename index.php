<?php

include 'database.php';

class DatabaseConnector
{
    protected $conn; // ENCAPSILATION

    public function __construct($conn) // CONSTRUCTOR
    {
        $this->conn = $conn;
    }

    public function exampleMethod()
    {
        return "Example method from DatabaseConnector";
    }
}

class TaskManager extends DatabaseConnector // INHERITANCE

{
    private $reminder;
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
    // OVERRIDING EXAMPLEMETHOD 1
    public function exampleMethod1(){
        $parentexampleMethod1 = parent::exampleMethod();
        $parentexampleMethod1['reminder'] = $this->reminder; //

        return $parentexampleMethod1;
    }
}

$taskManager = new TaskManager($conn);

if (isset($_POST['add'])) {
    $taskLabel = $_POST['task'];
    $result = $taskManager->addTask($taskLabel);

    if ($result) {
        header('Refresh:0; url=index.php');
    }
}

$run_q_select = $taskManager->getTasks();

if (isset($_GET['delete'])) {
    $result = $taskManager->deleteTask($_GET['delete']);

    if ($result) {
        header('Refresh:0; url=index.php');
    }
}

if (isset($_GET['done'])) {
    $status = ($_GET['status'] == 'open') ? 'close' : 'open';
    $result = $taskManager->updateTaskStatus($_GET['done'], $status);

    if ($result) {
        header('Refresh:0; url=index.php');
    }
}
echo $taskManager->exampleMethod();

?>


<!DOCTYPE html>
<html lang="en">

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
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 590px;
            margin: 0 auto;
            /* Mengatur margin auto untuk membuatnya berada di tengah */
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            /* Menambah properti justify-content untuk konten vertikal di tengah */
        }


        .header {
            padding: 15px;
            color: #fff;
            background-color: #3498db;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            text-align: center;
        }

        .header .title {
            display: flex;
            align-items: center;
            margin-bottom: 7px;
        }

        .header .title i {
            font-size: 24px;
            margin-right: 10px;
            color: #fff;
        }

        .header .title span {
            font-size: 24px;
            color: #fff;
        }

        .header .description {
            font-size: 13px;
            color: #fff;
        }

        .content {
            padding: 15px;
            text-align: center;
            width: 100%;
        }

            .card {
                background-color: #fff;
                padding: 15px;
                border-radius: 5px;
                margin-bottom: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .input-control {
                width: 100%;
                display: block;
                padding: 0.5rem;
                font-size: 1rem;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 3px;
                outline: none;
            }

            .input-control::placeholder {
                color: #999;
            }

            .text-right {
                text-align: right;
            }

            button {
                padding: 0.5rem 1rem;
                font-size: 1rem;
                cursor: pointer;
                background: #3498db;
                color: #fff;
                border: none;
                border-radius: 3px;
                transition: background 0.3s;
            }

            button:hover {
                background: #007bb5;
            }

            .task-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #eee;
                padding: 10px 0;
            }

            .text-orange,
            .text-red {
                font-size: 1.2rem;
                margin-right: 10px;
                cursor: pointer;
            }

            .text-orange:hover,
            .text-red:hover {
                text-decoration: underline;
            }

            .task-item.done span {
                text-decoration: line-through;
                color: #ccc;
            }

            @media (max-width: 768px) {
                .container {
                    width: 100%;
                    border-radius: 0;
                    box-shadow: none;
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
                                <a href="edit.php?id=<?= $r['taskid'] ?>" class="text-orange" title="Edit"><i class="bx bx-edit"></i>Edit</a>
                                <a href="?delete=<?= $r['taskid'] ?>" class="text-red" title="Remove" onclick="return confirm('Are you sure ?')"><i class="bx bx-trash"></i>Remove</a>
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
