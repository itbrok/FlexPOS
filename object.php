<?php
class ProgressObj{

var $increment;
var $addWidth;
var $text;

    function ProgressObj(){
        $this->text = "Loading...";
    }

    function DisplayMeter(){
        print(' <link rel="stylesheet" href="assets/bootstrap-5.2.1-dist/css/bootstrap.rtl.css">');
        print(" <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js'></script> ");
        print(" <link rel='stylesheet' type='text/css' href='style.css' /> ");
        print(" <body style='background-color: #ededed;'><div class='col'><div class='d-flex justify-content-center'>");
        print("     <h3>" . $this->text . "</h3></div>");
		print('         <div class="progress"><div class="progress-bar" style="width: 25%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div></div></body>');

    }

    function Calculate($count){
        $this->increment = 100 / $count;
    }

    function Animate(){
        $this->addWidth += $this->increment;
        $rounded = round($this->addWidth, 2);

        ?><script> 
            $('.progress-bar').stop().animate({width: "<?= $this->addWidth ?>%"}, "slow");
            $('.progress-bar').attr("aria-valuenow","<?= $this->addWidth ?>");
            $('.progress-bar').html("<?= $rounded ?>%");
            $('h3').html("<?= $this->text; ?>");
        </script><?php    
    }

    function Hide(){
        ?><script>
            function wait(ms) {
                var start = new Date().getTime();
                var end = start;
                while (end < start + ms) {
                    end = new Date().getTime();
                }
            }
            setTimeout(function(){
                $(".meter_container").fadeOut();
            }, 2000);
            wait(5000);
            parent.location.reload();
        </script><?php
    }

} //end class

?>