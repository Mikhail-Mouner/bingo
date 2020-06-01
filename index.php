<?php
include ( 'config.php' );
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
        h1,h2,h3,h4,h5,h6{ color: #fff; }
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
        input[type="radio"] {
            width: 65px;
            height: 30px;
            border-radius: 15px;
            border: 2px solid #444;
            background-color: white;
            -webkit-appearance: none; /*to disable the default appearance of radio button*/
            -moz-appearance: none;
        }

        input[type="radio"]:focus { /*no need, if you don't disable default appearance*/
            outline-color: transparent; /*to remove the square border on focus*/
        }

        input[type="radio"]:checked { /*no need, if you don't disable default appearance*/
            background-color: #212121;
            border: 1px solid #ffe9e9;
        }

        input[type="radio"]:checked ~ span:first-of-type {
            color: white;
        }

        label span:first-of-type {
            position: relative;
            left: -45px;
            font-size: 15px;
            color: #444;
        }

        label span {
            position: relative;
            top: -10px;
        }
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
            color: #fff;
            position: absolute;
            top:0;
            right: 15px;
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
            color: #FFFFFF;
            background: #232323;
            text-shadow: 0 0 5px #FFF, 0 0 10px #FFF, 0 0 15px #FFF, 0 0 20px #49ff18, 0 0 30px #49FF18, 0 0 40px #49FF18, 0 0 55px #49FF18, 0 0 75px #49ff18;
            color: #FFFFFF;
            background: #232323;
            font-family: Impact, Charcoal, sans-serif;
            font-variant: small-caps;
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
    </header>
    <div id="message"></div>
    <div id="game">
        <form onsubmit="return loginForm();">
            <div>
                <input type="text" id="username" placeholder="Enter your username" required autocomplete="off">
            </div>
            <br>
            <footer>
                <button class="btn" type="submit" id="submit">submit</button>
            </footer>
        </form>
    </div>
</div>
<!--include jquery -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
<!--end include jquery -->
<!--include ajax-->
<script>
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
    //login
    function loginForm() {
        myRequest = createRequest();
        var username = document.getElementById("username").value;
        myRequest.onreadystatechange = function myDate() {
            if (this.readyState == 4 && this.status == 200) { selectRoom(); }
        };
        myRequest.open("POST", "code.php?game=login", true);
        myRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        myRequest.send("username="+username);
        return false;
    }
    //select selectRoom
    function selectRoom() {
        myRequest = createRequest();
        myRequest.onreadystatechange = function myDate() {
            var game = document.getElementById("game");
            if (this.readyState == 4 && this.status == 200) {
                game.innerHTML = this.responseText;
            }
        };
        myRequest.open("POST", "code.php?game=selectRoom", true);
        myRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        myRequest.send();
    }
    //start_game
    function start_game() {
        myRequest = createRequest();
        var total_player = document.querySelector('input[name="type"]:checked').value;
        myRequest.onreadystatechange = function myDate() {
            if (this.readyState == 4 && this.status == 200) {
                window.location = "in_game.php";
            }
        };
        myRequest.open("POST", "code.php?game=start", true);
        myRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        myRequest.send("total_player="+total_player);
        return false;
    }
    //start_game
    function check_game_id() {
        myRequest = createRequest();
        var game_id = document.getElementById("game_id").value;
        myRequest.onreadystatechange = function myDate() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText > 0) {
                    start_game_id(game_id);
                }
            }
        };
        myRequest.open("POST", "code.php?game=check", true);
        myRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        myRequest.send("game_id="+game_id);
        return false;
    }
    //start_game
    function start_game_id(game_id) {
        myRequest = createRequest();
        myRequest.onreadystatechange = function myDate() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText > 0) {
                    window.location = "in_game.php";
                }else {
                    window.location = "index.php";
                }
            }
        };
        myRequest.open("POST", "code.php?game=start", true);
        myRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        myRequest.send("game_id="+game_id);
        return false;
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
</script>
<!--end include ajax -->
</body>
</html>