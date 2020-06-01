<?php
//$bingo_array = array();
//$i=1;
//do{
//    $no=rand ( 1, 25 );
//    if (!in_array ($no,$bingo_array ))
//    {echo $bingo_array[$i-1] = $no.'<br>';$i++;}
//}while(sizeof ($bingo_array) < 25);


include ( 'config.php' );
if (!isset($_SESSION['game_id'])){
	echo "<script>window.location ='index.php';</script>";
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	mysqli_query ( $website, "INSERT INTO start_game (game_id, user_id, done) VALUE ('".$_SESSION['game_id']."', '".$_SESSION['user_id']."', 1)") or die( mysqli_error ( $website ) );
	$start_game = mysqli_insert_id($website);
	$bingo_array = array();
	for ($i=1;$i<26;$i++){
		$bingo=(isset($_POST['bingo'.$i]) && is_numeric($_POST['bingo'.$i]))?$_POST['bingo'.$i]:0;
		if ($bingo > 0 && $bingo < 26 ){
			if (!in_array ($bingo,$bingo_array ))
			{ $bingo_array[$i-1] = $bingo; }
		}
	}
	$i=1;

	do{
		if ($bingo_array[$i-1] == 0){
			$no=rand ( 1, 25 );
			if (!in_array ($no,$bingo_array ))
			{$bingo_array[$i-1] = $no;$i++;}
		}else{$i++;}
	}while(sizeof ($bingo_array) < 25);

	for ($i=1;$i<26;$i++) {
		$no=$bingo_array[$i-1];
		mysqli_query ( $website, "UPDATE start_game SET element_".$i." = '".$no."'  WHERE start_game_id = '".$start_game."'") or die( mysqli_error ( $website ) );
	}
	echo "<script>window.location ='in_game.php';</script>";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="description" content="Bingo">
    <meta name="keywords" content="Bingo,free ,game">
    <meta name="author" content="Eng.Mikhail">

    <title>Bingo</title>
    <style>
        ::selection{
            color: #212121;
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        body{
            background: #212121;
            color: #666;
            text-align: center;
        }
        h1,h2,h3,h4,h5,h6,p{ color: #fff; }
        .clearfix{ clear: both; }
        #box{
            width: 350px;
            overflow: auto;
            margin: 40px auto;
            background: #666;
            padding-bottom: 40px;
            border-radius: 10px;
        }
        input,select{
            background: #444;
            color: #fff;
            border: none;
            padding: 15px 15px;
        }
        input[readonly]{
            cursor: pointer;
        }
        input[value]{text-align: center}
        #message{
            background: #333;
            color: #fff;
        }
        #gameBoard li{
            float: left;
            margin: 10px;
            height: 35px;
            width: 35px;
            font-size: 30px;
            background: #333;
            color: #ccc;
            list-style: none;
            border-radius: 5px;
            position: relative;
        }

        #gameBoard li .slash{
            color: black;
            top:2px;
            right: 6px;
            transform: rotate(45deg);
        }

        #gameBoard li:hover, #reset:hover, #submit:hover{
            cursor: pointer;
            background: #000;
        }
        #gameBoard .selected{
            cursor: default;
            background: #b1b1b1;
            color: #000;
        }
        .bingo-number{
            background: #333;
            color: #fff;
            border: none;
            width: 100%;
            height: 100%;
            padding:5px;
            font-size: 30px;
        }
        .bingo-winner {
            text-shadow: 0 0 5px #FFF, 0 0 10px #FFF, 0 0 15px #FFF, 0 0 20px #49ff18, 0 0 30px #49FF18, 0 0 40px #49FF18, 0 0 55px #49FF18, 0 0 75px #49ff18;
            color: #232323;
            background: #232323;
            font-family: Impact, Charcoal, sans-serif;
            font-variant: small-caps;
            word-spacing: 6px;
            font-size: 40px;
            letter-spacing: 6px;
            font-weight: 700;
        }
        .btn{
            border: 0;
            background: #444;
            color: #fff;
            width: 70%;
            padding: 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .d-none{display: none}
        .relative{position: relative}
        .absolute{position: absolute}
        .inline-block{display: inline-block}
        .m-0{margin: 0}
        footer{
            display: block;
            padding-top: 20px;
        }

        @media (max-width:320px ) {
            #box { width:100%;}
            #gameBoard li{
                margin: 6px;
                height: 35px;
            }
        }
    </style>
</head>
<body>
<div id="box">
    <header>
        <h1>Play Bingo</h1>
        <div>
            <p class="inline-block m-0" >Room no:</p>
            <h6 class="inline-block m-0"><?php echo 3333*$_SESSION['game_id'];?></h6>
        </div>
    </header>
    <div id="message"></div>
    <div id="game">
		<?php
		$stmt_bingo = mysqli_query ($website,"SELECT * FROM start_game WHERE done = 1 AND game_id = '".$_SESSION['game_id']."' AND user_id = '".$_SESSION['user_id']."' ");
		$no_stmt_bingo = mysqli_num_rows ($stmt_bingo );
		if ($no_stmt_bingo == 0) {
		?>
            <form method="post" action="in_game.php" target="_self">
                <ul id="gameBoard">
					<?php for ($i=1;$i<26;$i++){ ?>
                        <li>
                            <input class="bingo-number" name="bingo<?php echo $i;?>" id="bingo-number<?php echo $i;?>" onblur="maxLengthCheck(this,<?php echo $i;?>)"
                                   type="number" maxlength="2" min="1" max="25" />

                        </li>
					<?php } ?>
                </ul>
                <div class="clearfix"></div>
                <button class="btn" type="submit" style="width: 100%;margin-top: 15px">Let's Go</button>
            </form>
		<?php } ?>
    </div>
</div>
<!--include jquery -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
<!--end include jquery -->
<!--include ajax-->
<script>
    var game_over =true;
    function createRequest() {
        var myRequest;
        try{
            if (window.XMLHttpRequest) {
                myRequest = new XMLHttpRequest();
            } else {
                myRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
        }catch (error){
            console.log("There's an error on creating a request");
        }
        return myRequest;
    }
    var inputArray = new Array();

    function maxLengthCheck(object,element) {
        if (object.value.length > object.maxLength){
            object.value = object.value.slice(0, object.maxLength);
        }
        if (object.value > 25 || object.value == 0){
            object.style.backgroundColor = '#4c0808';
        }else{
            if(!!~jQuery.inArray(object.value, inputArray)) {
                object.style.backgroundColor = '#4c0808';
            } else {
                inputArray.push(object.value);
                object.style.backgroundColor = '#164c08';
            }
        }
    }

    //start game
    function playerTurn(element,value) {
        myRequest = createRequest();
        myRequest.onreadystatechange = function myDate() {
            if (this.readyState == 4 && this.status == 200) {
                in_game();
            }
        };
        myRequest.open("POST", "code.php?game=playerTurn", true);
        myRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        myRequest.send("element="+element+"&value="+value);
    }
    //start game
    function in_game() {
        myRequest = createRequest();
        myRequest.onreadystatechange = function myDate() {
            var game = document.getElementById("game");
            if (this.readyState == 4 && this.status == 200) {
                game.innerHTML = this.responseText;
            }

        };
        myRequest.open("POST", "code.php?game=in_game", true);
        myRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        myRequest.send();
    }
    function endGame(app) {
        myRequest = createRequest();
        myRequest.onreadystatechange = function myDate() {
            var game = document.getElementById("game");
            if (this.readyState == 4 && this.status == 200) {
                game.innerHTML = this.responseText;
                game_over = false;
            }

        };
        myRequest.open("POST", "code.php?game=endGame", true);
        myRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        myRequest.send('app='+app);
    }
    <?php  if ($no_stmt_bingo > 0) { ?>
        in_game();
        setInterval(in_game,1000);
    <?php } ?>
</script>
<!--end include ajax -->
</body>
</html>