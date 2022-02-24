<?php

require_once 'init.php';

require_once 'db_conn.php';

require_once 'functions.php';



 if (isset($_POST['username']) && isset($_POST['age']) && isset($_POST['gender']) && isset($_POST['race'])) {
    $username = validate($_POST['username']);
    $age = validate($_POST['age']);

    $gender = validate($_POST['gender']);

    $race = validate($_POST['race']);
    if(strlen($username)>30){
        $_SESSION['username_error'] = 'Username length must not exceed 30 characters';
          header("Location: game");
          exit();
    }

    if(!preg_match('/^[a-zA-Z0-9_]+$/',$username)){

        $_SESSION['username_error'] = 'Username must only contain letters (a-z), numbers (0-9),

and/or underscores (_)';

        header("location: game");

        exit();

    }

    $msg = '';

    if ($username ==='' || $age==='' || $gender==='' || $race ==='') {

        header("Location: game");

    } else {

        if(check_username_exists($username)){

          $_SESSION['username_error'] = 'Username is already taken';

          header("Location: game");

          exit();

        }

        $_SESSION['username'] = $username;
        setcookie('username',$username,time() + (86400 * 30), "/");

        if (!empty($_SESSION)) {

            // $msg = "Your session was successful. Welcome, ".$_SESSION['username']."!";

        } else {

            $msg = "Your session failed";

        }
        if (create_player_info($username,$age,$gender,$race)) {

            // $msg.= "<br>Your player info was inserted successfully!";

        } else {

            $msg.= "<br>Your player info could not be sent.";

        }

    }



  } else {

    header("Location: game");

  }



?>

<?php if(isset($_SESSION['user_id']) && intval($_SESSION['user_id']) > 0 && $_SESSION['check_url'] == "pre-game-quiz") { ?>


<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="UTF-8">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">



  <link rel="icon" type="image/png" sizes="32x32" href="./assets/favicon-stiletto.png">

  <!-- Google fonts -->

  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700&family=Bellefair&family=Barlow:wght@400;700&display=swap"

      rel="stylesheet">

      

  <!-- Our custom CSS -->

  <link rel="stylesheet" href="index.css">

  <title>In Our Red Stilettos | Game</title>

  <script src="navigation.js" defer></script>

  <script src="tabs.js" defer></script>



</head>



<body class="pre-game-quiz">

  <a class="skip-to-content" href="#main">Skip to content</a>

  <header class="primary-header flex">

    <div>

      <img src="./assets/shared/stiletto-logo.png" alt="In Our Red Stilettos Logo" class="logo">

    </div>

    <button class="mobile-nav-toggle" aria-controls="primary-navigation"><span class="sr-only" aria-expanded="false">Menu</span></button>

    <nav> 

        <?php include 'parts/menu.php'; ?>

    </nav>

  </header>



<?php

   if(isset($msg)){

    echo "<center> $msg </center>";

   }

?>



  <div class="container-quick-quiz">

    <div id="pre-game-quiz" class="justify-center flex-column fs-500 ff-sans-cond letter-spacing-3 uppercase">

        <div id="hud">

            <div id="hud-item">

                <p id="progressText" class="hud-prefix">

                    Question

                </p>



                <div id="progressBar">

                    <div id="progressBarFull"></div>

                </div>

            </div>



            <div id="hud-item">

                <p class="hud-prefix">

                    Score

                </p>

                <h1 class="hud-main-text" id="score">

                    0

                </h1>

            </div>

        </div>



        <div class="quiz-divider"></div>



        <h2 id="question">What is the answer to this question?</h2>



        <div class="choice-container">

            <p class="choice-prefix">A</p>

            <p class="choice-text" data-number="1">Choice 1</p>

        </div>



        <div class="choice-container">

            <p class="choice-prefix">B</p>

            <p class="choice-text" data-number="2">Choice 2</p>

        </div>



        <div class="choice-container">

            <p class="choice-prefix">C</p>

            <p class="choice-text" data-number="3">Choice 3</p>

        </div>



        <div class="choice-container">

            <p class="choice-prefix">D</p>

            <p class="choice-text" data-number="4">Choice 4</p>

        </div>

        <br><br>



    </div>

  </div>
  <script src="pre-game-quiz.js"></script>

  </body>

</html>

<?php }else{ 
    header("location: game");
    exit();
}
?>
