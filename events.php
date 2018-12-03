<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apps for the simplification of Life</title>
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:300,300i,400,400i" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.0/clipboard.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css">
    <script defer="" src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <script src="medium/rangy-core.js"></script>
    <script src="medium/rangy-classapplier.js"></script>
    <script src="medium/undo.js"></script>
    <script src="medium/medium.js"></script>
    <link rel="stylesheet" href="medium/medium.css">
    <script>
      $(function() {
        $("#eventsStart").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#eventsStart").datepicker('setDate', +1);
        $("#eventsEnd").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#eventsEnd").datepicker('setDate', +30);
        $("#btnGetEvents").click(function(e) {
            $.ajaxSetup({
                beforeSend:function() {
                    $("#loading").show();
                    $("#ajaxStuffs").empty();
                },
                complete:function() {
                    $("#loading").hide();
                }
            });
            $.ajax({
                type: "POST",
                url: "formEvents.php",
                data: $("#eventsForm").serialize(),
                success: function(result) {
                  $("#ajaxStuffs").html(result);
                },
                error: function(xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    alert(err.Message);
                }
            });
            e.preventDefault();
          });
        var header = $(".formatBar");
        var pos = header.position();
        $(window).scroll(function() {
          var windowpos = $(window).scrollTop();
          if (windowpos >= pos.top) {
            header.addClass("sticky");
          } else {
            header.removeClass("sticky");
          }
        });
        var clip = new ClipboardJS('.clipIt', {
            text: function() {
                var htmlBlock = document.querySelector('#copyThis');
                htmlBlock = htmlBlock.innerHTML;
                var cleanedUp = htmlBlock.replace('<b>','<b style="font-weight:400!important;">');
                return cleanedUp;
            }
        });
        clip.on('success', function(e) {
            swal({
                title: "Copied!",
                icon: "success",
                button: "Let's go!"
            });
            e.clearSelection();
        });
    });
    </script>
</head>
<body>
  <?php require 'views/partials/nav.php'; ?>

  <!-- FORM BAR -->
  <div class="container mx-auto py-3 px-2 bg-grey-darkest">
      <form id="eventsForm" class="flex justify-center items-end" action="forms/formEvents.php" method="POST">
          <div class="mr-2 flex align-middle">
              <label class="label text-grey text-2xl pr-2 pt-1">Get Events from</label>
              <input class="input rounded p-2" type="text" name="eventsStart" id="eventsStart" required="required">
              <label class="label text-grey text-2xl px-2 pt-1">to</label>
              <input class="input rounded p-2" type="text" name="eventsEnd" id="eventsEnd" required="required">
          </div>
          <div>
              <button class="button bg-red-light py-2 px-4 rounded hover:bg-green-light" id="btnGetEvents">Go</button>
          </div>
      </form>
  </div>
  <!-- END FORM BAR -->

  <!-- FORMAT BAR -->
    <div class="formatBar container mx-auto bg-grey flex p-2 justify-center text-grey rounded-b">
        <a class="bold block py-2 px-6 bg-grey-darker mr-2 hover:bg-grey-lighter rounded no-underline text-inherit cursor-pointer">
            <span class="icon is-medium">
                <i class="fas fa-bold"></i>
            </span>
            <span>Boldicize</span>
        </a>
        <a class="italic block py-2 px-6 bg-grey-darker mr-2 hover:bg-grey-lighter rounded no-underline text-inherit cursor-pointer">
            <span class="icon is-medium">
                <i class="fas fa-italic"></i>
            </span>
            <span>Italicate</span>
        </a>
        <a class="clipIt block py-2 px-6 bg-grey-darker mr-2 hover:bg-grey-lighter rounded no-underline text-inherit cursor-pointer" data-clipboard-target="#copyThis">
            <span class="icon is-medium">
                <i class="fas fa-clipboard"></i>
            </span>
            <span>Copy List</span>
        </a>
    </div>
    <!-- END FORMAT BAR -->
    <div class="dynamicContent">
      <div id="loading" class="hidden text-center p-6">
        <h2 class="text-5xl font-normal">Coming mother...</h2>
        <img src="sonarLoading.svg">
      </div>

      <div id="copyThis" class="container mx-auto justify-center py-8 px-4">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans:300,300i,400,400i" rel="stylesheet">
        <div class="eventList mx-auto" style="max-width: 550px; margin: auto;">
            <div id="ajaxStuffs"></div>
        </div>
      </div>
    </div>
    <script>
      (function(){
        var article = document.getElementById('copyThis'),
          container = article.parentNode,
            medium = new Medium({
                element: article,
                mode: Medium.richMode,
                placeholder: 'Your Article',
                attributes: null,
                tags: null,
              pasteAsText: false
            });
          article.highlight = function() {
          if (document.activeElement !== article) {
            medium.select();
          }
        };
        container.querySelector('.bold').onmousedown = function() {
          article.highlight();
          medium.invokeElement('b', {
              style: "font-weight:400!important;"
            });
          return false;
        };
        container.querySelector('.italic').onmousedown = function() {
          article.highlight();
          medium.invokeElement('i', {
            style: "font-style:italic!important;"
          });
          return false;
        };
    })();
  </script>
</body>
</html>