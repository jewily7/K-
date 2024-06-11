<?php
@include 'config.php';

if (isset($_POST['submit'])) {
   // Trim and sanitize input
   $name = mysqli_real_escape_string($conn, trim($_POST["name"]));
   $email = mysqli_real_escape_string($conn, trim($_POST['email']));
   $pass = $_POST['password'];
   $cpass = $_POST['cpassword'];
   $user_type = $_POST['user_type'];

   // Check if user already exists
   $select = "SELECT * FROM user_form WHERE email = ?";
   $stmt = mysqli_prepare($conn, $select);
   mysqli_stmt_bind_param($stmt, "s", $email);
   mysqli_stmt_execute($stmt);
   mysqli_stmt_store_result($stmt);

   if (mysqli_stmt_num_rows($stmt) > 0) {
      $error[] = 'User already exists!';
   } else {
      // Check if passwords match
      if ($pass != $cpass) {
         $error[] = 'Passwords do not match!';
      } else {
         // Hash the password
         $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
         
         // Insert the new user into the database
         $insert = "INSERT INTO user_form (name, email, password, user_type) VALUES (?, ?, ?, ?)";
         $stmt = mysqli_prepare($conn, $insert);
         mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_pass, $user_type);
         if (mysqli_stmt_execute($stmt)) {
            header('Location: login_form.php');
            exit();
         } else {
            $error[] = 'Error in registration!';
         }
      }
   }
   mysqli_stmt_close($stmt);
   mysqli_close($conn);
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <?php
      if (isset($error)) {
         foreach ($error as $err) {
            echo '<span class="error-msg">' . htmlspecialchars($err) . '</span>';
         }
      }
      ?>
      <input type="text" name="name" required placeholder="enter your name">
      <input type="email" name="email" required placeholder="enter your email">
      <input type="password" name="password" required placeholder="enter your password">
      <input type="password" name="cpassword" required placeholder="confirm your password">
      <select name="user_type">
         <option value="user">user</option>
         <option value="admin">admin</option>
      </select>
      <input type="submit" name="submit" value="register now" class="form-btn">
      <p>already have an account? <a href="login_form.php">login now</a></p>
   </form>

</div>

</body>
</html>