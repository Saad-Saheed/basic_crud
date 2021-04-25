<?php
session_start();
include('Crud.class.php');
include('header.php');
$errmessage = $s_data = [];

if (!empty($_POST) && isset($_POST['authreset'])) {
    $data = [
        "password" => FILTER_SANITIZE_STRING,
        "cpassword" => FILTER_SANITIZE_STRING
    ];
    $s_data = filter_input_array(INPUT_POST, $data);

    reset_password();
}
function reset_password()
{
    global $errmessage, $s_data, $user;
    $errmessage = [];


    // filter all input
    if ($s_data) {

        foreach ($s_data as $key => $input) {

            if (empty($input))
                $errmessage[$key] = "Invalid input, Your $key is required";
        }

        // if their is no error message
        if (empty($errmessage)) {

            // if password match
            if ($s_data['password'] == $s_data['cpassword']) {

                $s_data = (object) $s_data;
                $sql = "UPDATE users SET password = md5($s_data->password) WHERE id = '$user->id'";
                $res = Crud::update($sql);
                if ($res) {
                    $errmessage['general'] = "Password change successfully! ";
                } else {
                    $errmessage['general'] = "Unable to update Password";
                }
            } else
                $errmessage['password'] = "password does not matched!";
        } else
            echo "<h1>Make sure you supplied all data!</h1>";
    }
    $_SESSION['errmessage'] = $errmessage;
}

?>

<main>
    <h1 align="center">Reset Password</h1>
    <fieldset style="display: inline-block;">
        <legend>Reset Password</legend>
    <h1>
        <?php
        echo (isset($_SESSION['errmessage']['general']) ? $_SESSION['errmessage']['general'] : "");
        unset($_SESSION['errmessage']['general']);
        ?>
    </h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">


        <div>
            <label for="password">New Password</label><br>
            <input type="password" name="password" id="password">
            <h3><?php echo isset($_SESSION['errmessage']) ? (isset($_SESSION['errmessage']['password']) ? $_SESSION['errmessage']['password'] : "") : "" ?></h3>
        </div>

        <div>
            <label for="cpassword">Confirm Password</label><br>
            <input type="password" name="cpassword" id="cpassword">
            <h3><?php echo isset($_SESSION['errmessage']) ? (isset($_SESSION['errmessage']['cpassword']) ? $_SESSION['errmessage']['cpassword'] : "") : "" ?></h3>
        </div><br>

        <div>
            <input type="submit" name="authreset" value="Change Password"><br>
        </div>

    </form>
    </fieldset>
</main>


</body>

</html>