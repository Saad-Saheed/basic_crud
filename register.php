<?php
session_start();
include('Crud.class.php');
include('header.php');

$message = [];
$s_data = [];



if (!empty($_POST) && $_POST['authregister']) {

    $data = [
        "name" => FILTER_SANITIZE_STRING,
        "email" => FILTER_VALIDATE_EMAIL,
        "password" => FILTER_SANITIZE_STRING,
        "cpassword" => FILTER_SANITIZE_STRING
    ];
    $s_data = filter_input_array(INPUT_POST, $data, false);

    store();
}

function store()
{
    global $message, $s_data;

    $message = [];

    // filter all input
    if ($s_data) {
        // Testing each input
        foreach ($s_data as $key => $input) {

            if (empty($input))
                $message[$key] = "Invalid input, Your $key is required";
        }
        // if password match
        if ($s_data['password'] == $s_data['cpassword']) {
            // if their is no error message and user does not exist
            if (empty($message) && !user_exist($s_data['email'])) {
                $s_data = (object) $s_data;
                $sql = "INSERT INTO users (name, email, password) VALUES('$s_data->name', '$s_data->email', md5($s_data->password))";
                $res = Crud::insert($sql);

                if ($res)
                    $message['success'] = "User created successfully";
                else
                    $message['general'] = "Unable to create user";
            } else
                echo "<h2>invalid inputs OR This User has been Registered already!</h2>";
        } else
            $message['password'] = "password does not matched!";
    } else
        echo "<h2>Make sure you supplied all data!</h2>";

    $_SESSION['message'] = $message;
}

function user_exist($email)
{
    //make connection
    $conn = DataObject::connect();

    // if connection was successful 
    if ($conn) {

        $sql2 = "SELECT * FROM users WHERE email = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $email);
        $stmt2->execute();

        $res = $stmt2->get_result();
        if ($res->num_rows > 0)
            return true;

        $stmt2->close();
        DataObject::disConnect($conn);
    } else {

        die("Connection failed! " . $conn->connect_error);
    }
    return false;
}
?>

<main>
    <h1 align="center">User Registeration Page</h1>
    <h1>
        <?php

        if (isset($_SESSION['message'])) {
            echo (isset($_SESSION['message']['general']) ? $_SESSION['message']['general'] : (isset($_SESSION['message']['success']) ? $_SESSION['message']['success'] : ""));
        }

        ?>
    </h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

        <div>
            <label for="name">Name</label><br>
            <input type="text" name="name" id="name" placeholder="Full name" required>
            <h3><?php
                echo isset($_SESSION['message']) ? (isset($_SESSION['message']['name']) ? $_SESSION['message']['name'] : "") : "";
                ?></h3>

        </div>

        <div>
            <label for="email">Email</label><br>
            <input type="email" name="email" id="email" required>
            <h3><?php echo (isset($_SESSION['message']['email']) ? $_SESSION['message']['email'] : "") ?></h3>
        </div>

        <div>
            <label for="password">Password</label><br>
            <input type="password" name="password" id="password">
            <h3><?php echo (isset($_SESSION['message']['password']) ? $_SESSION['message']['password'] : "") ?></h3>
        </div>

        <div>
            <label for="cpassword">Confirm Password</label><br>
            <input type="password" name="cpassword" id="cpassword">
        </div><br>

        <div>
            <input type="submit" name="authregister" value="Register"><br><br>
            <span>Already have an account? <a href="login.php">Login</a></span>
        </div>

    </form>
</main>
<?php

unset($_SESSION['message']);
$_SESSION['message'] = array();
?>

</body>

</html>