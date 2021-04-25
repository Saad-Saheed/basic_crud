<?php
session_start();
include('Crud.class.php');
include('header.php');

if (!isset($_SESSION['auth_user']))
    header("location: login.php");


if (isset($_GET['id'])) {
    $current_course_id = htmlspecialchars($_GET['id']);
    $_SESSION['sel_course_id'] = htmlspecialchars($_GET['id']);
} elseif (isset($_SESSION['sel_course_id'])) {
    $current_course_id = $_SESSION['sel_course_id'];
}

$message = [];
$s_data = [];
$lock = true;
$current_course = "";


//edit
if (isset($_GET['action'])  && $_GET['action'] == "edit") {
    $lock = false;
    $sql1 = "SELECT * FROM courses WHERE id = '$current_course_id'";
    $course = Crud::get($sql1);
    if ($course) {
        $current_course = (object) $course[0];
        // print_r($current_course);die;
    }

    // delete
} else if (isset($_GET['action'])  && $_GET['action'] == "delete") {

    $sql = "DELETE FROM courses WHERE id = '$current_course_id'";
    $res = Crud::delete($sql);
}

if (!empty($_POST) && isset($_POST['updatecourse'])) {

    $data = [
        "course_name" => FILTER_SANITIZE_STRING,
        "course_track" => FILTER_SANITIZE_STRING,
    ];
    $s_data = filter_input_array(INPUT_POST, $data, false);

    update();
}

function update()
{
    global $message, $s_data, $user, $current_course_id;

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
            $sql = "UPDATE courses SET name = '$s_data->course_name', track = '$s_data->course_track' WHERE id = '$current_course_id'";
            $res = Crud::update($sql);

            if ($res) {
                $message['success'] = "Course updated successfully";
                $lock = true;
            } else
                $message['general'] = "Unable to update Course";
        } else
            echo "<h2>invalid inputs!</h2>";
    } else
        echo "<h2>Make sure you supplied all data!</h2>";

    $_SESSION['message'] = $message;
}

function selectedoption(object $course, string $value)
{
    if ($course && $course->track == $value) {
        echo "selected";
    }
}

?>

<main>
    <h1 align="center">Course Module</h1>


    <fieldset style="display: inline-block;">

        <legend>Available Courses</legend>
        <h1>Available Courses</h1>
        <?php

        $sql = "SELECT * FROM courses WHERE user_id = '$user->id'";
        $res = Crud::get($sql);

        if ($res) {
            $i = 1;

        ?>
            <table border="1" cellspacing="0" cellpadding="10">
                <thead>
                    <th>S/N</th>
                    <th>TRACK NAME</th>
                    <th>COURSE NAME</th>
                    <th>CREATED ON</th>
                    <th>ACTIONS</th>
                </thead>

                <tbody>
                    <?php

                    foreach ($res as $course) {
                        $course = (object) $course;
                    ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo $course->track ?></td>
                            <td><?php echo $course->name ?></td>
                            <td><?php echo $course->created_at ?></td>
                            <td>
                                <a href="?action=edit&id=<?php echo $course->id ?>">Edit</a>&nbsp; &nbsp;
                                <a href="?action=delete&id=<?php echo $course->id ?>">Delete</a>
                            </td>
                        </tr>
                    <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        <?php } else {
            echo "<h2>Course Not Found!</h2>";
        }


        ?>


    </fieldset>





    <fieldset style="display: inline-block;">
        <legend>Update Course</legend>
        <h1>Update Course</h1>
        <h2>
            <?php

            if (isset($_SESSION['message'])) {
                echo (isset($_SESSION['message']['general']) ? $_SESSION['message']['general'] : (isset($_SESSION['message']['success']) ? $_SESSION['message']['success'] : ""));
            }

            ?>
        </h2>


        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
            <div>
                <label for="course_name">Course Name</label><br>
                <input type="text" name="course_name" id="course_name" style="display:block !important; width: 300px;" value="<?php echo $current_course ? $current_course->name : "" ?>" placeholder="Course Name" required <?php echo ($lock ? "disabled" : "") ?>>
                <h3><?php
                    echo isset($_SESSION['message']) ? (isset($_SESSION['message']['course_name']) ? $_SESSION['message']['course_name'] : "") : "";
                    ?></h3>

            </div>

            <div>
                <label for="course_track">Tracks</label><br>
                <select name="course_track" id="course_track" required <?php echo ($lock ? "disabled" : "") ?> style="display:block !important; width: 300px;">
                    <option value="">Choose Track</option>
                    <option value="fronten" <?php selectedoption($current_course, "frontend") ?>>Frontend</option>
                    <option value="backend" <?php selectedoption($current_course, "backend") ?>>Backend</option>
                    <option value="UI/UX" <?php selectedoption($current_course, "UI/UX") ?>>UI/UX</option>
                    <option value="mobile" <?php selectedoption($current_course, "mobile") ?>>Mobile</option>
                </select>
                <h3><?php echo (isset($_SESSION['message']['course_track']) ? $_SESSION['message']['course_track'] : "") ?></h3>
            </div>


            <div>
                <input type="submit" name="updatecourse" value="Update"><br><br>
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