<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <title>Q-Learning</title>
</head>
<body>

<div class="container">
    <div class="row">
        <h1 align="center"><b>Q-learning</b></h1>
    </div>
    <div class="row">
        <div class="col-xs-2">
            <form class="form-horizontal" role="form" action="index.php" method="post">
                <div class="row">
                    <div class="form-group">
                        <label for="verticalSize" class="control-label"><span
                                class="glyphicon glyphicon-resize-vertical"></span></label>

                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="verticalSize" id="verticalSize" value="10">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="horizontalSize" class=" control-label"><span
                                class="glyphicon glyphicon-resize-horizontal"></span></label>

                        <div class="col-lg-4">
                            <input type="text" class="form-control" name="horizontalSize" id="horizontalSize"
                                   value="10">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-default submit-button" style="font-size: 12px">NEW WALKTHROUGH</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xs-7 inputText">
            <br><br>

            <h3>Maze walkthrough based on Q-learning algorithm.</h3>
            <br>

            <h2>Made by:</h2>

            <h3 class="col-xs-offset-2">Butova D.A.</h3>

            <h3 class="col-xs-offset-2">Denisko A.V.</h3>

            <h3 class="col-xs-offset-2">Maslov A.I.</h3>

            <h3 class="col-xs-offset-2">Prudskii Y.Y.</h3>
        </div>
        <?php
        if (isset($_POST['verticalSize']) and isset($_POST['horizontalSize'])) {
            echo "<div class='col-xs-10'>";
            $rows = $_POST['verticalSize'];
            $columns = $_POST['horizontalSize'];
            echo 'Maze <span id="height"><b>' . $rows . '</b></span>x<span id="width"><b>' . $columns . '</b></span>';
            echo "<table id='cellTable'>";
            $index = 0;
            for ($i = 1; $i <= $rows; $i++) {
                echo "<tr>";
                for ($j = 1; $j <= $columns; $j++) {
                    $index++;
                    echo "<td id=\"cell_$index\" class='cell'><span class='numberInCell'></span></td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "<br>";
            echo "<button class='btn btn-default' id='startButton'>GET OBSTACLES</button><br><br>";
            echo "<button class='btn btn-default' id='goButton'>GO</button><br><br>";
            echo "<button class='btn btn-default' id='instantButton' style='font-size: 10px'>INSTANT WALKTHROUGH</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }

        ?>


</body>
<script>
    var n = parseInt($("#height").text());  //vertical size of matrix
    var m = parseInt($("#width").text());   //horizontal size of matrix
    $(".verticalSize").attr("value",n);
    $(".horizontalSize").attr("value",m);
    $('#retryButton').click(function(){
        location.reload();
    })
    if ((n >= 1) && (m >= 1)) {
        $('.inputText').addClass("displayNone");
    }
    $("#cell_" + n * m).css("background-color", "#47a447");
    $("#cell_" + 1).css("background-color", "#f5e79e");
    $("<span class='glyphicon glyphicon-star agent'></span>").appendTo("#cell_" + 1);
    var Q = [];         //array of weights
    var R = [];         //array of ability to go to another cell
    $("#startButton").click(function () {
        var ASSOC = [];     //array, that associates an element 1 with 1.1 in matrix, element n*m with n.m etc...
        var assocIndex = 0;
        for (var i = 1; i <= n; i++) {
            for (var j = 1; j <= m; j++) {
                assocIndex++;
                ASSOC[assocIndex] = i + '.' + j;
            }
        }
        for (var i = 0; i <= n * m + 1; i++) {
            R[i] = [];
            Q[i] = [];
            for (var j = 0; j <= m * n + 1; j++) {
                Q[i][j] = 0;
                R[i][j] = -1;
            }
        }

        //Loop to generate matrix R
        for (var j = 1; j <= m * n; j++) {
            var firstIndex = "";
            var secondIndex = "";
            var separator = ASSOC[j].indexOf('.');
            for (var i = 0; i < separator; i++) {
                firstIndex += ASSOC[j][i];
            }
            for (var i = separator + 1; i < ASSOC[j].length; i++) {
                secondIndex += ASSOC[j][i];
            }
            firstIndex = parseInt(firstIndex);
            secondIndex = parseInt(secondIndex);
            var currentColor = $("#cell_" + ASSOC.indexOf(firstIndex + '.' + (secondIndex + 1))).css("background-color");
            if (currentColor != "rgb(0, 0, 0)") {
                if (currentColor == "rgb(71, 164, 71)") {
                    R[j][ASSOC.indexOf(firstIndex + '.' + (secondIndex + 1))] = 100;
                }
                else {
                    R[j][ASSOC.indexOf(firstIndex + '.' + (secondIndex + 1))] = 0;
                }
            }
            if ($("#cell_" + ASSOC.indexOf(firstIndex + '.' + (secondIndex - 1))).css("background-color") != "rgb(0, 0, 0)") {
                R[j][ASSOC.indexOf(firstIndex + '.' + (secondIndex - 1))] = 0;
            }
            currentColor = $("#cell_" + ASSOC.indexOf((firstIndex + 1) + '.' + secondIndex)).css("background-color");
            if (currentColor != "rgb(0, 0, 0)") {
                if (currentColor == "rgb(71, 164, 71)") {
                    R[j][ASSOC.indexOf((firstIndex + 1) + '.' + secondIndex)] = 100;
                }
                else {
                    R[j][ASSOC.indexOf((firstIndex + 1) + '.' + secondIndex)] = 0;
                }
            }
            if ($("#cell_" + ASSOC.indexOf((firstIndex - 1) + '.' + secondIndex)).css("background-color") != "rgb(0, 0, 0)") {
                R[j][ASSOC.indexOf((firstIndex - 1) + '.' + secondIndex)] = 0;
            }
            if ($("#cell_" + j).css("background-color") == "rgb(0, 0, 0)") {
                for (var i = 1; i <= n * m; i++) {
                    R[j][i] = -1;
                }
            }
        }
        R[n * m][n * m] = 100; //last element of matrix R
    });

    function getMaxValueIndex(arr) {
        var max = -1;
        var indexes = [];
        for (var i = 0; i <= arr.length; i++) {
            if (arr[i] >= max) {
                max = arr[i];
            }
        }
        for (var i = 0; i <= arr.length; i++) {
            if (arr[i] == max) {
                indexes.push(i);
            }
        }
        return indexes;
    }
    $("#goButton").click(function () {
        $(".agent").remove();
        $("<span class='glyphicon glyphicon-star agent'></span>").appendTo("#cell_1");
        var agentIndex = 1;
        var interval = setInterval(function () {
            var firstPossibleWays = [];
            var firstPossibleWaysValue = [];
            for (var j = 1; j <= m * n; j++) {
                if (R[agentIndex][j] != -1) {
                    firstPossibleWays.push(j);
                    firstPossibleWaysValue.push(Q[agentIndex][j]);
                }
            }
            var ways = [];
            var test = [];
//            console.log(firstPossibleWays);
//            console.log(firstPossibleWaysValue);
//            console.log(getMaxValueIndex(firstPossibleWaysValue));
//            var superImportantAndCleverChoose = firstPossibleWays[Math.floor(Math.random() * firstPossibleWays.length)];
            var superImportantAndCleverChoose = firstPossibleWays[getMaxValueIndex(firstPossibleWaysValue)[Math.floor(Math.random() * getMaxValueIndex(firstPossibleWaysValue).length)]];

            var secondPossibleWaysValue = [];
            for (var j = 1; j <= m * n; j++) {
                if (R[superImportantAndCleverChoose][j] != -1) {
                    secondPossibleWaysValue.push(Q[superImportantAndCleverChoose][j]);
                    test.push(R[superImportantAndCleverChoose][j]);
                    ways.push(j);
                }
            }
            Q[agentIndex][superImportantAndCleverChoose] = R[agentIndex][superImportantAndCleverChoose] + 0.8 * Math.max.apply(Math, secondPossibleWaysValue); //WHOLE neural FUCKING network
            //console.log(agentIndex, superImportantAndCleverChoose, R[agentIndex][superImportantAndCleverChoose], secondPossibleWaysValue);
            $(".agent").remove();
            $("<span class='glyphicon glyphicon-star agent'></span>").appendTo("#cell_" + superImportantAndCleverChoose);

            agentIndex = superImportantAndCleverChoose;
            if (agentIndex == n * m) {
                clearInterval(interval);
            }
        }, 100);
        for (var i = 1; i <= n * m; i++) {
            for (var j = 1; j <= n * m; j++) {
                if (Q[i][j] != 0) {
                    console.log(".cell_" + i);
                    $(".agent").css("opacity", 1);
                    $("#cell_" + i).css({"background-color": "blue", "opacity": 0.3+(Q[i][j] / 90)});
                }
            }
        }
        console.log(Q);
    });

    $("#instantButton").click(function () {
        $(".agent").remove();
        $("<span class='glyphicon glyphicon-star agent'></span>").appendTo("#cell_1");
        var agentIndex = 1;
        while (agentIndex != n * m) {
            var firstPossibleWays = [];
            var firstPossibleWaysValue = [];
            for (var j = 1; j <= m * n; j++) {
                if (R[agentIndex][j] != -1) {
                    firstPossibleWays.push(j);
                    firstPossibleWaysValue.push(Q[agentIndex][j]);
                }
            }
            var ways = [];
            var test = [];
//            console.log(firstPossibleWays);
//            console.log(firstPossibleWaysValue);
//            console.log(getMaxValueIndex(firstPossibleWaysValue));
//            var superImportantAndCleverChoose = firstPossibleWays[Math.floor(Math.random() * firstPossibleWays.length)];
            var superImportantAndCleverChoose = firstPossibleWays[getMaxValueIndex(firstPossibleWaysValue)[Math.floor(Math.random() * getMaxValueIndex(firstPossibleWaysValue).length)]];

            var secondPossibleWaysValue = [];
            for (var j = 1; j <= m * n; j++) {
                if (R[superImportantAndCleverChoose][j] != -1) {
                    secondPossibleWaysValue.push(Q[superImportantAndCleverChoose][j]);
                    test.push(R[superImportantAndCleverChoose][j]);
                    ways.push(j);
                }
            }
            Q[agentIndex][superImportantAndCleverChoose] = R[agentIndex][superImportantAndCleverChoose] + 0.8 * Math.max.apply(Math, secondPossibleWaysValue); //WHOLE neural FUCKING network
            //console.log(agentIndex, superImportantAndCleverChoose, R[agentIndex][superImportantAndCleverChoose], secondPossibleWaysValue);
            $(".agent").remove();
            $("<span class='glyphicon glyphicon-star agent'></span>").appendTo("#cell_" + superImportantAndCleverChoose);

            agentIndex = superImportantAndCleverChoose;
        }
        for (var i = 1; i <= n * m; i++) {
            for (var j = 1; j <= n * m; j++) {
                if (Q[i][j] != 0) {
                    $(".agent").css("opacity", 1);
                    $("#cell_" + i).css({"background-color": "blue", "opacity": 0.3+(Q[i][j] / 90)});
                }
            }
        }
    });


    $("#cellTable").on("click", "td", function () {
        if (($(this).attr('id') != 'cell_1') && ($(this).attr('id') != 'cell_' + n * m)) {
            if ($(this).css("background-color") == "rgb(255, 255, 255)") {
                $(this).css("background-color", "black");
            }
            else {
                $(this).css("background-color", "rgb(255, 255, 255)");
            }
        }
    });
</script>
</html>
