<?php include ( 'config.php' ); ?>
<?php
function winCondition($turns) {
    $gameOn=0;
    if ($turns[0]!=0 && $turns[1]!=0 && $turns[2]!=0 && $turns[3]!=0 && $turns[4]!=0){ $gameOn++; }
    if ($turns[5]!=0 && $turns[6]!=0 && $turns[7]!=0 && $turns[8]!=0 && $turns[9]!=0){ $gameOn++; }
    if ($turns[10]!=0 && $turns[11]!=0 && $turns[12]!=0 && $turns[13]!=0 && $turns[14]!=0){ $gameOn++; }
    if ($turns[15]!=0 && $turns[16]!=0 && $turns[17]!=0 && $turns[18]!=0 && $turns[19]!=0){ $gameOn++; }
    if ($turns[20]!=0 && $turns[21]!=0 && $turns[22]!=0 && $turns[23]!=0 && $turns[24]!=0){ $gameOn++; }

    if ($turns[0]!=0 && $turns[5]!=0 && $turns[10]!=0 && $turns[15]!=0 && $turns[20]!=0){ $gameOn++; }
    if ($turns[1]!=0 && $turns[6]!=0 && $turns[11]!=0 && $turns[16]!=0 && $turns[21]!=0){ $gameOn++; }
    if ($turns[2]!=0 && $turns[7]!=0 && $turns[12]!=0 && $turns[17]!=0 && $turns[22]!=0){ $gameOn++; }
    if ($turns[3]!=0 && $turns[8]!=0 && $turns[13]!=0 && $turns[18]!=0 && $turns[23]!=0){ $gameOn++; }
    if ($turns[4]!=0 && $turns[9]!=0 && $turns[14]!=0 && $turns[19]!=0 && $turns[24]!=0){ $gameOn++; }

    if ($turns[0]!=0 && $turns[6]!=0 && $turns[12]!=0 && $turs[18]!=0 && $turns[24]!=0){ $gameOn++; }
    if ($turns[4]!=0 && $turns[8]!=0 && $turns[12]!=0 && $turns[16]!=0 && $turns[20]!=0){ $gameOn++; }
    return $gameOn;
}
function writeBingo($total){
    switch ($total) {
        case 1:
            return 'B----';
            break;
        case 2:
            return 'Bi---';
            break;
        case 3:
            return 'Bin--';
            break;
        case 4:
            return 'Bing-';
            break;
        case 5:
            return 'Bingo!';
            break;
        default:
            if ($total > 5)
                return 'Bingo!';
            else
                return '-----';

    }
}

?>
<?php
if (isset($_GET['game']) && $_GET['game'] == 'login') {
    if ($_SERVER['REQUEST_METHOD']=="POST"){
        $username=filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        mysqli_query ( $website, "INSERT INTO user (username) VALUES ( '$username' )") or die( mysqli_error ( $website ) );
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = mysqli_insert_id($website);
    }
}elseif (isset($_GET['game']) && $_GET['game'] == 'selectRoom') {	?>
    <div>
        <fieldset>
            <form onsubmit="return start_game();">
                <div>
                    <label>
                        <input type="radio" value="2" name="type" required/>
                        <span>2P</span>
                    </label>
                    <label>
                        <input type="radio" value="4" name="type" required/>
                        <span>4P</span>
                    </label>
                </div>
                <button class="btn" type="submit" id="create">Create Room</button>
            </form>
        </fieldset>
    </div>
    <br>
    <fieldset>
        <h4>Exits Room</h4>
        <input type="number" id="game_id" onkeyup="check_game_id();">
    </fieldset>
    <?php
}elseif (isset($_GET['game']) && $_GET['game'] == 'check') {
    $game_id=$_POST['game_id']/3333;
    $stmt=mysqli_query ( $website, "SELECT * FROM game WHERE game_id = '".$game_id."' AND completed = 0 ") or die( mysqli_error ( $website ) );
    echo mysqli_num_rows ($stmt);
}elseif (isset($_GET['game']) && $_GET['game'] == 'insertDate') {
    $game_id=$_SESSION['game_id'];
    $stmt=mysqli_query ( $website, "SELECT * FROM game WHERE game_id = '".$game_id."' ") or die( mysqli_error ( $website ) );
}elseif (isset($_GET['game']) && $_GET['game'] == 'start') {
    if ($_SERVER['REQUEST_METHOD']=="POST"){
        if (isset($_POST['game_id']) && !empty($_POST['game_id'])){
            $game_id=$_POST['game_id']/3333;
            $total_player=selectItem('game','game_id','=',$game_id)['total_player'];
            mysqli_query ( $website, "UPDATE game SET player_two ='".$_SESSION['user_id']."' WHERE game_id ='".$game_id."' AND player_two IS NULL") or die( mysqli_error ( $website ) );
            $checkUpdate = mysqli_affected_rows($website);
            if ($checkUpdate > 0 ){
                $_SESSION['game_id'] = $game_id;
                $_SESSION['player_no']=2;
            }elseif ($checkUpdate == 0 && $total_player > 2){
                mysqli_query ( $website, "UPDATE game SET player_three ='".$_SESSION['user_id']."' WHERE game_id ='".$game_id."' AND player_three IS NULL") or die( mysqli_error ( $website ) );
                $checkUpdate = mysqli_affected_rows($website);
                if ($checkUpdate > 0 ){
                    $_SESSION['player_no']=3;
                    $_SESSION['game_id'] = $game_id;
                }elseif ($checkUpdate == 0 && $total_player > 3){
                    mysqli_query ( $website, "UPDATE game SET player_four ='".$_SESSION['user_id']."' WHERE game_id ='".$game_id."' AND player_four IS NULL") or die( mysqli_error ( $website ) );
                    $checkUpdate = mysqli_affected_rows($website);
                    if ($checkUpdate > 0 ){
                        $_SESSION['player_no']=4;
                        $_SESSION['game_id'] = $game_id;
                    }
                }
            }
            echo $game_id;

        }else{
            mysqli_query ( $website, "INSERT INTO game (player_one,total_player) VALUES ( '".$_SESSION['user_id']."', '".$_POST['total_player']."' )") or die( mysqli_error ( $website ) );
            $_SESSION['game_id'] = mysqli_insert_id($website);
            $_SESSION['player_no']=1;
        }
    }
}elseif (isset($_GET['game']) && $_GET['game'] == 'in_game') {
    if (isset($_SESSION['game_id'])) {
        $winner_name = mysqli_query ( $website, "SELECT user.username FROM game INNER JOIN user ON game.winner_id = user.user_id  WHERE game_id ='".$_SESSION['game_id']."' ") or die( mysqli_error ( $website ) );
        $row_winner_name = mysqli_fetch_assoc($winner_name);
        if (isset($row_winner_name) && !empty($row_winner_name)){ ?>
            <h2 class="bingo-winner"><?php echo writeBingo(5); ?></h2>
            <h3><?php echo $row_winner_name['username'];?> winner</h3>
            <a href="index.php"><button class="btn">New Game</button></a>
        <?php }else{
        $stmt_bingo = mysqli_query($website, "SELECT * FROM start_game WHERE done = 1 AND game_id = '" . $_SESSION['game_id'] . "' AND user_id = '" . $_SESSION['user_id'] . "' ");
        $row_bingo = mysqli_fetch_assoc($stmt_bingo);
        $no_stmt_bingo = mysqli_num_rows($stmt_bingo);

        $stmt_ingame = mysqli_query($website, "SELECT * FROM in_game WHERE game_id = '" . $_SESSION['game_id'] . "' ");
        $row_ingame = mysqli_fetch_assoc($stmt_ingame);
        $turns = array();
        $inputs = array();
        $counter = 0;
        for ($i = 1; $i < 26; $i++) {
            $turns[$i-1]=0;
            if (isset($row_ingame['element_' . $i]) && !empty($row_ingame['element_' . $i])) {
                $inputs[$counter] = $row_ingame['element_' . $i];
                $counter++;
            }
        }
        $counter++;

        $total_player = selectItem('game', 'game_id', '=', $_SESSION['game_id'])['total_player'];
        ?>
        <?php
        if (count($inputs) % $total_player == ($_SESSION['player_no'] - 1)) {
            ?>
            <p>Your turn</p>
            <?php
        }else{
            ?>
            <p>Opposite's turn</p>
            <?php
        }
        ?>
        <ul id="gameBoard">
            <?php for ($i = 1; $i < 26; $i++) { ?>
                <li class="relative">
                    <input readonly class="bingo-number" id="bingo-number<?php echo $i; ?>"
                           value="<?php echo $row_bingo['element_' . $i] ?>"
                        <?php if (in_array($row_bingo['element_' . $i], $inputs)) {
                            $turns[$i-1]=$row_bingo['element_' . $i];
                            ?>
                            style="background-color: #164c08"
                        <?php } else {
                            if (count($inputs) % $total_player == ($_SESSION['player_no'] - 1)) {
                                ?>
                                onclick="playerTurn(<?php echo $counter ?>,<?php echo $row_bingo['element_' . $i] ?>)"
                                <?php
                            }else{
                                ?>
                                onclick="alert('Opposite\'s turn')"
                                <?php
                            }
                        } ?>
                    />
                    <?php if (in_array($row_bingo['element_' . $i], $inputs)) { ?>
                        <span class="absolute slash">|</span>
                    <?php } ?>
                </li>
            <?php } ?>
        </ul>
        <div class="clearfix"></div>
        <h2 class="bingo-winner"><?php echo writeBingo(winCondition($turns)); ?></h2>
        <?php if (winCondition($turns) >= 5){ ?>
            <button class="btn" onclick="endGame()">End The Game</button>
        <?php } ?>
        <?php
        }
    } else {
        echo "<script>window.location='index.php'</script>";
    }
}elseif (isset($_GET['game']) && $_GET['game'] == 'playerTurn') {
    echo $element=(isset($_POST['element']) && is_numeric($_POST['element']))?$_POST['element']:0;
    echo $value=(isset($_POST['value']) && is_numeric($_POST['value']))?$_POST['value']:0;
    $stmt_slot = mysqli_query ( $website, "SELECT * FROM in_game WHERE game_id ='".$_SESSION['game_id']."' ") or die( mysqli_error ( $website ) );
    if ( mysqli_num_rows ($stmt_slot) == 0 ){
        mysqli_query ( $website, "INSERT INTO in_game (game_id, element_".$element.") VALUES ( '".$_SESSION['game_id']."' , '".$value."' )") or die( mysqli_error ( $website ) );
    }else{
        mysqli_query ( $website, "UPDATE in_game SET element_".$element." = '".$value."' WHERE game_id = '".$_SESSION['game_id']."' ") or die( mysqli_error ( $website ) );
    }
}elseif (isset($_GET['game']) && $_GET['game'] == 'newGame') {
    mysqli_query ( $website, "UPDATE game SET win_two = '".$_GET['win_two']."', win_one ='".$_GET['win_one']."' WHERE game_id = '".$_SESSION['game_id']."' ") or die( mysqli_error ( $website ) );
    mysqli_query ( $website, "DELETE FROM in_game WHERE game_id ='".$_SESSION['game_id']."' ") or die( mysqli_error ( $website ) );
    echo "<script>window.location='in_game.php'</script>";
}elseif (isset($_GET['game']) && $_GET['game'] == 'endGame') {
    mysqli_query ( $website, "UPDATE game SET completed = '1',winner_id= '".$_SESSION['user_id']."' WHERE game_id = '".$_SESSION['game_id']."' ") or die( mysqli_error ( $website ) );
}
?>