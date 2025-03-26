<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="card-left">
                <div class="slideshow">
                    <img class="slide" src="doctor1.png" alt="Doctor 1">
                    <img class="slide" src="doctor2.png" alt="Doctor 2">
                    <img class="slide" src="doctor3.png" alt="Doctor 3">
                </div>
            </div>
            <div class="card-right">
                <h1 class="title">WELCOME BACK!</h1>
          

                <form action="loginProcess.php" method="POST">
                    <div class="input-group">
                        <label for="username">Email</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="input-group role-selection">
                        <label>I am a</label>
                        <div class="role-options">
                            <input type="radio" id="patient" name="role" value="patient" required>
                            <label for="patient" class="role-card">
                                <span>👤 Patient</span>
                            </label>
                            <input type="radio" id="doctor" name="role" value="doctor" required>
                            <label for="doctor" class="role-card">
                                <span>🩺 Doctor</span>
                            </label>
                        </div>
                    </div>
         <?php
          if (isset($_GET['error'])) {
              $error = $_GET['error'];
              
              if ($error === 'missingFields') {
                  echo "<p style='color:red; font-weight:bold;'>Please fill in all required fields.</p>";
              } elseif ($error === 'invalidCredentials') {
                  echo "<p style='color:red; font-weight:bold;'>Email or password is incorrect. Please try again.</p>";
             } elseif ($error === 'wrongRole') {
    echo "<p style='color:red; font-weight:bold;'>wrong role.</p>";
} else {
                  echo "<p style='color:red; font-weight:bold;'>An unknown error occurred. Please try again.</p>";
              }
          }
        ?>
                    <div class="actions">
                        <a href= #><button type="submit" class="btn">Log in</button></a>
                    </div>
                    <div class="create-account">
                        <p>New to Hope clinec? <a href="#"><u>Create Account</u></a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const slides = document.querySelectorAll(".slide");
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove("active");
                if (i === index) {
                    slide.classList.add("active");
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        setInterval(nextSlide, 2000);
    </script>
</body>
</html>
