<?php
session_start();
include('Crud.class.php');
include('header.php');

if (!isset($_SESSION['auth_user']))
    header("location: login.php");

$message = [];
$s_data = [];



if (!empty($_POST) && $_POST['coursereg']) {

    $data = [
        "course_name" => FILTER_SANITIZE_STRING,
        "course_track" => FILTER_SANITIZE_STRING,
    ];
    $s_data = filter_input_array(INPUT_POST, $data, false);

    store();
}

function store()
{
    global $message, $s_data, $user;

    $message = [];

    // filter all input
    if ($s_data) {
        // Testing each input
        foreach ($s_data as $key => $input) {

            if (empty($input))
                $message[$key] = "Invalid input, Your $key is required";
        }
        // if their is no error message
        if (empty($message)) {
            $s_data = (object) $s_data;
            $sql = "INSERT INTO courses (name, track, user_id) VALUES('$s_data->course_name', '$s_data->course_track', $user->id)";
            $res = Crud::insert($sql);

            if ($res)
                $message['success'] = "Course created successfully";
            else
                $message['general'] = "Unable to create Course";
        } else
            echo "<h2>invalid inputs!</h2>";
    } else
        echo "<h2>Make sure you supplied all data!</h2>";

    $_SESSION['message'] = $message;
}


?>


<main>
    <h1 align="center">Courses Module</h1>

    <fieldset style="display: inline-block;">
        <h1>
            <?php

            if (isset($_SESSION['message'])) {
                echo (isset($_SESSION['message']['general']) ? $_SESSION['message']['general'] : (isset($_SESSION['message']['success']) ? $_SESSION['message']['success'] : ""));
            }

            ?>
        </h1>
        <legend>Add Course</legend>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
            <div>
                <label for="course_name">Course Name</label><br>
                <input type="text" name="course_name" id="course_name" placeholder="Course Name" required>
                <h3><?php
                    echo isset($_SESSION['message']) ? (isset($_SESSION['message']['course_name']) ? $_SESSION['message']['course_name'] : "") : "";
                    ?></h3>

            </div>

            <div>
                <label for="course_track">Tracks</label><br>
                <select name="course_track" id="course_track" required>
                    <option value="">Choose Track</option>
                    <option value="frontend">Frontend</option>
                    <option value="backend">Backend</option>
                    <option value="UI/UX">UI/UX</option>
                    <option value="mobile">Mobile</option>
                </select>
                <h3><?php echo (isset($_SESSION['message']['course_track']) ? $_SESSION['message']['course_track'] : "") ?></h3>
            </div>


            <div>
                <input type="submit" name="coursereg" value="Register"><br><br>
            </div>

        </form>

    </fieldset>

</main>
<?php

unset($_SESSION['message']);
$_SESSION['message'] = array();
?>
</body>

</html>