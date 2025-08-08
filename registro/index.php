<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro y Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
  <link rel="stylesheet" href="styles.css">
</head>
<body>


  <!-- Register Form -->
  <div class="container" id="signUp" style="display:none">
    <h1 class="form-title">Register</h1> 
    <form method="post" action="register.php">
      <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" name="fName" id="fName" placeholder="First Name" required>
        <label for="fName">First Name</label>
      </div>
      <div class="input-group">
        <i class="fas fa-user"></i>    
        <input type="text" name="lName" id="lName" placeholder="Last Name" required> 
        <label for="lName">Last Name</label>
      </div>
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="reg_email" id="reg_email" placeholder="Email" required>
        <label for="reg_email">Email</label>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="reg_password" id="reg_password" placeholder="Password" required>
        <label for="reg_password">Password</label>
      </div>
      <input type="submit" class="btn" value="Sign Up" name="signUp">
    </form>
    <p class="or">-------------or--------</p>
    <div class="icons">
      <i class="fab fa-google"></i>
      <i class="fab fa-facebook"></i>
    </div>
    <div class="links">
      <p>Already Have Account?</p>
      <button id="signInButton">Sign In</button>
    </div>
  </div>

  <!-- Login Form -->
  <div class="container" id="signIn">
    <h1 class="form-title">Sign In</h1> 
    <form method="post" action="register.php">
      <div class="input-group">
        <i class="fas fa-envelope"></i>
        <input type="email" name="login_email" id="login_email" placeholder="Email" required>
        <label for="login_email">Email</label>
      </div>
      <div class="input-group">
        <i class="fas fa-lock"></i>
        <input type="password" name="login_password" id="login_password" placeholder="Password" required>
        <label for="login_password">Password</label>
      </div>
      <p class="recover"><a href="#">Recover Password</a></p>
      <input type="submit" class="btn" value="Sign In" name="signIn">
    </form>
    <p class="or">-------------or--------</p>
    <div class="icons">
      <i class="fab fa-google"></i>
      <i class="fab fa-facebook"></i>
    </div>
    <div class="links">
      <p>Don't have an account yet?</p>
      <button id="signUpButton">Sign Up</button>
    </div>
  </div>
       <script src="script.js"></script>
</body>
</html>
