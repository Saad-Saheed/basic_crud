<?php
session_start();
include('Crud.class.php');
include('header.php');
$errmessage = $s_data = [];

if (!empty($_POST) && $_POST['authlogin']) {
    $data = [
        "email" => FILTER_VALIDATE_EMAIL,
        "password" => FILTER_SANITIZE_STRING
    ];
    $s_data = filter_input_array(INPUT_POST, $data);

    login();
}

function login()
{
    global $errmessage, $s_data;
    $errmessage = [];


    // filter all input
    if ($s_data) {
        $path = "database/users.txt";
        // Testing each input
        foreach ($s_data as $key => $input) {

            if (empty($input))
                $errmessage[$key] = "Invalid input, Your $key is required";
        }

        // if their is no error message
        if (empty($errmessage)) {
            $s_data = (object) $s_data;
            $sql = "SELECT * FROM users WHERE email = '$s_data->email' AND password= md5($s_data->password)";
            $user = Crud::get($sql);
            if ($user) {
                $_SESSION['auth_user'] = (object) $user[0];
                header("location: index.php");
            } else
                $errmessage['general'] = "invalid credentials!";
        } else
            echo "<h2>invalid input!</h2>";
    } else {
        echo "<h1>Make sure you supplied all data!</h1>";
    }
    $_SESSION['errmessage'] = $errmessage;
}

?>

<main>
    <h1 align="center">Login Page</h1>
    <fieldset style="display: inline-block;">

        <legend>Sign In</legend>
        <h1>
            <?php
            echo (isset($_SESSION['errmessage']['general']) ? $_SESSION['errmessage']['general'] : "");
            unset($_SESSION['errmessage']['general']);
            ?>
        </h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

            <div>
                <label for="email">Email</label><br>
                <input type="email" name="email" id="email" required>
                <h3><?php echo isset($_SESSION['errmessage']) ? (isset($_SESSION['errmessage']['email']) ? $_SESSION['errmessage']['email'] : "") : "" ?></h3>
            </div>
            <br>
            <div>
                <label for="password">Password</label><br>
                <input type="password" name="password" id="password" required>
                <h3><?php echo isset($_SESSION['errmessage']) ? (isset($_SESSION['errmessage']['password']) ? $_SESSION['errmessage']['password'] : "") : "" ?></h3>
            </div>
            <br>
            <div>
                <input type="submit" name="authlogin" value="Login"><br><br>
                <span>I am new here? <a href="register.php">Register</a></span><br><br>

            </div>

        </form>
    </fieldset>
</main>


</body>

</html>