<?php
//JF: Page to allow user to select how many decks to use, add more money to account, change password, and view stats 9still working on making sure stats table gets updated as user plays.)
include_once "bj_package.php";

session_start();
Htmller::print_html_header();
Htmller::print_play_header();

//Stats Go Here
// print_r($_SESSION['userObj']);
// $stats = $_SESSION['userObj']->getUserStats();
// echo<<<e
// <aside>
// $stats[0]
// $stats[1]


// </aside>


// e;

if(isset($_POST["submitPlay"])) {
    
    //$_SESSION['hands'] = $_POST['hands'];
    $_SESSION['decks'] = $_POST['decks'];
    $game = new Game();
    $game->setStatus("just_started");
    $_SESSION['game'] = serialize($game);
    
    if(isset($_POST["cheat"]) && $_POST["cheat"] == "on") {
        $_SESSION["cheat"] = "on";
    }
    else {
        $_SESSION["cheat"] = "off";
    }
    
    //header("Location: play.php?from=settings");
    header("Location: play.php");
    exit();
    
    
}


echo <<<e
<div class="container mt-3">
<h3>Game Settings</h3>
<form method="POST" action="">

<div class="form-group">
    <label for="decks">Number of Decks: </label>
    <input style="width: 100px;" class="form-control" type="number" id="decks" name="decks" min="1" step="1" max="12" value="3">
</div>
<div class="form-group">
    <label for="cheat">Enable Cheat Mode </label>
    <input class="form-check" style="height: 20px; width: 20px;" type="checkbox" id="cheat" name="cheat" value="on" checked>
    <p>(Card counting, next cards, and view dealer's hidden card)</p>
</div>
<button type="submit" name="submitPlay" class="btn btn-primary">Let's Play!</button>
</form>
</div>
</article>

e;



echo <<<e
<div class="container mt-3">
<h3>Add more money to your account?</h3>
<form method="POST" action="settings.php">
<div class="form-group">
<label id="addMoney">Add funds to your balance (CAD)? </label>
<input class="form-control" style="width: 100px;" type="number" id="addMoney" name="increaseBalance" min="1" step="1">
<button type="submit" name="newFunds" class="btn btn-primary mt-3">Update Funds</button>
</div>
</form>
</div>

e;

if(isset($_POST["newFunds"])) {
    
    $id = $_SESSION["id"];
    $money = $_POST["increaseBalance"];
    $addFunds = DB_connection::addMoney($id, $money);
    echo "<br>Funds added successfully! <br>Your updated balance is: $" . $_SESSION["userObj"]->getBalance();  

}


echo <<<e
<article>
<div class="container mt-3">
<h3>Change Password</h3>
<form method="POST" action="settings.php">
  <div class="form-group">
    <label for="change">Select New Password</label>
<small id="passwordHelp" class="form-text lead mt-0">(Must be at least 8 characters, and have one or more: letter, number, special character.)</small>
    <input type="password" style="width: 300px;" class="form-control" id="change" name="change" placeholder="Enter New Password" pattern="(?=.*\d)(?=.*[A-Za-z])(?=.*[$&+,:;=?@#|'.^*()%!-]).{8,}" required>
    
  </div>
  <div class="form-group">
    <label for="confirm">Confirm New Password (must match password entered above)</label>
    <input type="password" class="form-control" style="width: 300px;" id="confirm" name="confirm" pattern="(?=.*\d)(?=.*[A-Za-z])(?=.*[$&+,:;=?@#|'.^*()%!-]).{8,}" placeholder="Reenter New Password" required>
  </div>
  <button type="submit" name="submitPassword" class="btn btn-primary">Change Password</button>
</form>
</div>

e;


if(isset($_POST["submitPassword"]) && $_POST["change"] == $_POST["confirm"]) {
    
    $user = $_SESSION["user"];
    $new = $_POST["change"];
    $pdo = DB_connection::createPDO();
    
    $pdo->query("UPDATE users SET password = '$new' WHERE username = '$user' ");
    echo "New password submitted!";
    
    
    
}


echo "<br><br>";
$tables = $_SESSION["userObj"]->getUserStats();
echo "$tables[0] <br><br>$tables[1]";

Htmller::print_html_footer();
?>