<?php

error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

//Check PHP version
if (version_compare(phpversion(), '5.1.0', '<') == true) {
	exit('PHP5.1+ Required');
}


class Engine {
	function __construct() {
		mysql_connect("localhost", "testovoe", "testovoe") or die ("Error connect SQL");
		mysql_select_db("testovoe") or die ("Error select db SQL");
		mysql_query("SET NAMES 'utf8'");

	}

	private function str($string) {
		$string = trim(htmlspecialchars($string));
		return mysql_real_escape_string($string);
	}


	private function formLib($name='', $id=0) {
		$html = '<form>
			<input name="id_lib" id="id_lib" type="hidden" value="'.$id.'">
  			<div class="form-group">
    			<label for="nameLib">Название библиотеки</label>
    			<input name="lib" id="lib" class="form-control" value="'.$name.'">
  			</div>
			<button type="submit" class="btn btn-default" onClick="return saveLib();">Сохранить</button>
		</form>';

	    return $html;

	}


	public function addLib() {
		return $this->formLib();
	}

	public function editLib() {
	    $id = (int)$_GET['id'];

		$result = mysql_query("SELECT `name` FROM `library` WHERE `id_library`='$id'");
        $data = mysql_fetch_assoc($result);

		return $this->formLib($data['name'], $id);
	}


	public function saveLib() {

        $id = (int)$_POST['id'];
        $name = $this->str($_POST['name']);

        $json = array();

        $json['status'] = 'error';

        if (empty($name)) { $json['status'] = 'error_name'; } else {

    		if (empty($id)) {            	//Если ИД библиотеки пуст, значит добавим библиотеку
    			mysql_query("INSERT INTO `library` (`name`) VALUES ('$name')");
    			$json['status'] = 'success';
    		} else {    			//иначе редактирование библиотеки, проверим существует ли библиотека с переданным id
    			$result = mysql_query("SELECT `id_library` FROM `library` WHERE `id_library`='$id'");
    			if ($result) {    				//Библиотека существует, обновляем название            		mysql_query("UPDATE `library` SET `name`='$name' WHERE `id_library`='$id'");
            		$json['status'] = 'success';
            	} else {            		//Иначе ошибка (ид не найден)
            		$json['status'] = 'error_id';
            	}
    		}

		}

    	return json_encode($json);

	}




	public function deleteLib() {
		$id = (int)$_POST['id'];

        $json = array();

        if (empty($id)) {
        	$json['status'] = 'error';

        } else {            //Удаление библиотеки
        	mysql_query("DELETE FROM `library` WHERE `id_library`='$id'");
        	//Удаление связей библиоткека->книги
        	mysql_query("DELETE FROM `books_library` WHERE `id_library`='$id'");

            $json['status'] = 'success';
        }

		return json_encode($json);

	}

	public function library() {

		$html = '<table class="table">
				<tr>
        			<th>Название библиотеки</th>
        			<th>опции</th>
        		</tr>';

		$result = mysql_query("SELECT * FROM `library`");
		while ($data = mysql_fetch_assoc($result)) {
        	$html .= '<tr>
        		<td>'.$data['name'].'</td>
        		<td><a href="#" class="btn btn-primary" onClick="return loadModule(\'Редактирование библиотеки\', \'editLib&id='.$data['id_library'].'\');"><i class="glyphicon glyphicon-pencil"></i></a>
        		    <a href="#" onClick="deleteLib(\''.$data['id_library'].'\');" data-toggle="modal" data-target="#myModal" class="btn btn-primary"><i class="glyphicon glyphicon-remove"></i></a>
        		</td>
        	</tr>';

		}


        $html .= '</table>';

        return $html;


	}




	private function libraryCheckbox($id_book) {
		$checkbox = '';
		$result = mysql_query("SELECT * FROM `library`");
		while ($data = mysql_fetch_assoc($result)) {            $checked = '';

            if (!empty($id_book)) {            	$res = mysql_query("SELECT `id_library` FROM `books_library` WHERE `id_library`='".(int)$data['id_library']."' AND `id_book`='".(int)$id_book."'");
                $res = mysql_fetch_assoc($res);
                if ($res['id_library'] == $data['id_library']) $checked = 'checked';
            }

			$checkbox .= '<input name="libr[]" type="checkbox" value="'.$data['id_library'].'" '.$checked.'> '.$data['name'].' <br /> ';

		}

		return $checkbox;

	}



	private function addBookForm($name='', $author='', $id=0) {

		$html = '<form id="book_form">
			<input name="id_book" id="id_book" type="hidden" value="'.$id.'">
  			<div class="name-book">
    			<label for="name_book">Название книги</label>
    			<input name="name_book" id="name_book" class="form-control" value="'.$name.'">
  			</div>
  			<div class="author-book">
    			<label for="author_book">Автор</label>
    			<input name="author_book" id="author_book" class="form-control" value="'.$author.'">
  			</div>
  			<div class="library">
    			<label for="authorBook">Библиотеки</label>
    			<br />
                '.$this->libraryCheckbox($id).'
  			</div>
			<button type="submit" class="btn btn-default" onClick="return saveBook();">Сохранить</button>
		</form>';

	    return $html;

	}


    //Функция для добавления связей книги->библиотеки
	private function books_library($array, $id_book, $edit) {		if (!empty($array)) {	    	//На всякий случай, убираем повторяющиеся элементы
	    	$array = array_unique($array);
	    	//Если редактирование, то удалим прежнии связи (книга->библиотеки)
			if ($edit == 1) mysql_query("DELETE FROM `books_library` WHERE `id_book`='".(int)$id_book."'");
	    	//Отмеченные библиотеки
	    	foreach ($array as $key => $val) {
	    		//Перед добавлением, проверим реальное существование библиотеки
	    		$result = mysql_query("SELECT `id_library` FROM `library` WHERE `id_library`='".(int)$val."'");
    			//Библиотека существует, можно добавить связь
    			if ($result) mysql_query("INSERT INTO `books_library` (`id_library`,`id_book`) VALUES ('".(int)$val."', '".(int)$id_book."')");
	    	}
        }
	}


	public function saveBook() {

        $id = (int)$_POST['id'];
        $name = $this->str($_POST['name']);
        $author = $this->str($_POST['author']);

        $library = @$_POST['checks'];

        $json = array();

        $json['status'] = 'error';

        if (empty($name)) { $json['status'] = 'error_name'; } else {

    		if (empty($id)) {
            	//Если ИД книги пуст, значит добавление новой книги
    			mysql_query("INSERT INTO `books` (`name`,`author`) VALUES ('$name', '$author')");
    			$book_id = mysql_insert_id();
    			//Добавим связь
                $this->books_library($library, $book_id, 0);

    			$json['status'] = 'success';
    		} else {
    			//иначе редактирование книги, проверим существует ли книга
    			$result = mysql_query("SELECT `id_book` FROM `books` WHERE `id_book`='$id'");
    			if ($result) {
                    //Редактируем книгу
            		mysql_query("UPDATE `books` SET `name`='$name', `author`='$author' WHERE `id_book`='$id'");
            		//Редактируем связи
            		$this->books_library($library, $id, 1);
            		$json['status'] = 'success';
            	} else {
            		//Иначе ошибка (ид не найден)
            		$json['status'] = 'error_id';
            	}
    		}

		}

    	return json_encode($json);

	}


	public function addBook() {
		return $this->addBookForm();
	}

	public function editBook() {

	    $id = (int)$_GET['id'];

		$result = mysql_query("SELECT `name`,`author` FROM `books` WHERE `id_book`='$id'");
        $data = mysql_fetch_assoc($result);

		return $this->addBookForm($data['name'], $data['author'], $id);
	}



	public function bookList() {

        $select = '<select name="library" onChange="selectLib();" id="lib_select">
        		<option value="0">Все библиотеки</option>';

		$result = mysql_query("SELECT * FROM `library`");
		while ($data = mysql_fetch_assoc($result)) {
			$select .= '<option value="'.$data['id_library'].'">'.$data['name'].'</option>';
		}

        $select .= '</select><br /><br />';

		$html = $select.'<table class="table">
				<tr>
        			<th>Название книги</th>
        			<th>Автор</th>
        			<th>Опции</th>
        		</tr>';

        $sql = 'SELECT * FROM `books`';
        if (!empty($_GET['id_lib'])) $sql = "SELECT * FROM `books` a LEFT JOIN `books_library` b ON a.id_book = b.id_book WHERE b.id_library='".(int)$_GET['id_lib']."' ";

		$result = mysql_query($sql);
		while ($data = mysql_fetch_assoc($result)) {

        	$html .= '<tr>
        		<td>'.$data['name'].'</td>
        		<td>'.$data['author'].'</td>
        		<td><a href="#" class="btn btn-primary" onClick="return loadModule(\'Редактирование библиотеки\', \'editBook&id='.$data['id_book'].'\');"><i class="glyphicon glyphicon-pencil"></i></a>
        		    <a href="#" onClick="deleteBook(\''.$data['id_book'].'\');" data-toggle="modal" data-target="#myModal" class="btn btn-primary"><i class="glyphicon glyphicon-remove"></i></a>
        		</td>
        	</tr>';

		}


        $html .= '</table>';

        return $html;


	}



	public function deleteBookPost() {

		$id = (int)$_POST['id'];

        $json = array();

        if (empty($id)) {

        	$json['status'] = 'error';

        } else {
            //Удаление библиотеки
        	mysql_query("DELETE FROM `books` WHERE `id_book`='$id'");
        	//Удаление связей библиоткека->книги
        	mysql_query("DELETE FROM `books_library` WHERE `id_book`='$id'");

            $json['status'] = 'success';
        }

		return json_encode($json);

	}


}


$Engine = new Engine();


switch ($_GET['mod']) {
	case 'add_lib':
		echo $Engine->addLib();
	break;

	case 'saveLib':
		echo $Engine->saveLib();
	break;

	case 'editLib':
		echo $Engine->editLib();
	break;

	case 'library':
		echo $Engine->library();
	break;

	case 'deleteLib':
		echo $Engine->deleteLib();
	break;

	case 'addBook':
		echo $Engine->addBook();
	break;

	case 'editBook':
		echo $Engine->editBook();
	break;

	case 'saveBook':
		echo $Engine->saveBook();
	break;

	case 'bookList':
		echo $Engine->bookList();
	break;

	case 'deleteBookPost':
		echo $Engine->deleteBookPost();
	break;

	default:
    	echo'Ошибка 404, страница не найдена!';
	break;

}



