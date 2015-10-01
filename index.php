<?php
header('Content-Type: text/html; charset=utf-8');
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Тестовое задание</title>
    <!-- Bootstrap -->
    <link href="bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>


    <div class="container">


	<div class="page-header"><h1>Тестовое задание</h1></div>

     <div class="row">

        <div class="col-md-8">

		<div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title"></h3>
            </div>

            <div class="panel-body">
            	<div id="confirm-alert"></div>
            	<div class="panel-content"></div>
            </div>
          </div>

        </div>

        <div class="col-md-4">

		<div class="list-group">
            <a href="#" onClick="return loadModule('Список библиотек', 'library');" class="list-group-item active" id="lib_list">Список библиотек</a>
            <a href="#" onClick="return loadModule('Добавление новой библиотеки', 'add_lib');" class="list-group-item">Добавить библиотеку</a>
            <a href="#" onClick="return loadModule('Список книг', 'bookList');" class="list-group-item" id="book_list">Список книг</a>
            <a href="#" onClick="return loadModule('Добавление книги', 'addBook');" class="list-group-item">Добавить книгу</a>
          </div>

        </div>

      </div>
    </div>

    <div style="display:none;"><img src="images/loading.gif"></div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
    <script src="script.js"></script>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Подтвердите выполнение действий</h4>
      </div>
      <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="cancel_button" data-dismiss="modal">Отмена</button>
        <button type="button" class="btn btn-primary" id="confirm_button">Подтверждаю</button>
      </div>
    </div>
  </div>
</div>








  </body>
</html>