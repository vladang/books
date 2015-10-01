	$(document).ready(function() {
		loadModule('Список библиотек', 'library');
	});

	function loadModule(title, mod, clear) {

		if (clear != 1) $('#confirm-alert').html('');

		$('.list-group').on('click', '.list-group-item', function(){
    		$('.list-group-item').removeClass('active');
    		$(this).addClass('active');
		});

		$('.panel-content').html('<img src="images/loading.gif">');
		$('.panel-title').html(title);
	    $('.panel-content').load('engine.php?mod='+mod);
		return false;
	}


	function saveLib() {

    	var id   = $('#id_lib').val();
    	var name = $('#lib').val();

        $('#confirm-alert').html('');

		$.post('engine.php?mod=saveLib', {id: id, name: name},

		function(data, status){

			var jdata = JSON.parse(data);

			switch (jdata.status) {

				case 'success':
					$('.panel-content').html('<p class="alert alert-success">Данные успешно сохранены!</p> <a href="#" onClick="$(\'#lib_list\').click();" class="btn btn-primary">Перейти к списку библиотек</a>');
				break;

				case 'error':
					$('#confirm-alert').html('<p class="alert alert-danger">Неизвестная ошибка!</p>');
				break;

				case 'error_name':
					$('#confirm-alert').html('<p class="alert alert-danger">Ошибка! Заполните название библиотеки!</p>');
					$('.form-group').addClass('has-error');
				break;

				case 'error_id':
					$('#confirm-alert').html('<p class="alert alert-danger">Ошибка! Библиотека не найдена!</p>');
				break;
			}
		});
		return false;
	}


	function deleteLib(id) {

		$('.modal-body').html('Вы действительно хотите удалить эту библиотеку? Все книги находящиеся в данной библиотеке будут удалены!');
        $('#confirm_button').attr('onClick', 'deleteLibPost('+id+')');

	}


	function deleteLibPost(id) {

		$('#confirm-alert').html('');

		$.post('engine.php?mod=deleteLib', {id: id},

		function(data, status){

			var jdata = JSON.parse(data);

			switch (jdata.status) {

				case 'success':
					$('#confirm-alert').html('<p class="alert alert-success">Библиотека успешно удалена!</p>');
					loadModule('Список библиотек', 'library', 1);
				break;

				case 'error':
					$('#confirm-alert').prepend('<p class="alert alert-danger">Ошибка!</p>');
				break;

			}
		});

        $('#cancel_button').click();

		return false;
	}




	function saveBook() {

		var selectedItems = new Array();
		$("input[name='libr[]']:checked").each(function() {
				selectedItems.push($(this).val());
		});

    	var id   = $('#id_book').val();
    	var name = $('#name_book').val();
    	var author = $('#author_book').val();

        $('#confirm-alert').html('');

		$.post('engine.php?mod=saveBook', {id: id, name: name, author: author, checks: selectedItems},

		function(data, status){

			var jdata = JSON.parse(data);

			switch (jdata.status) {

				case 'success':
					$('.panel-content').html('<p class="alert alert-success">Данные успешно сохранены!</p> <a href="#" onClick="$(\'#book_list\').click();" class="btn btn-primary">Перейти к списку книг</a>');
				break;

				case 'error':
					$('#confirm-alert').html('<p class="alert alert-danger">Неизвестная ошибка!</p>');
				break;

				case 'error_name':
					$('#confirm-alert').html('<p class="alert alert-danger">Ошибка! Заполните название книги!</p>');
					$('.name-book').addClass('has-error');
				break;

				case 'error_id':
					$('#confirm-alert').html('<p class="alert alert-danger">Ошибка! Книга не найдена!</p>');
				break;
			}


		});
		return false;
	}


	function deleteBook(id) {

		$('.modal-body').html('Вы действительно хотите удалить эту книгу?');
        $('#confirm_button').attr('onClick', 'deleteBookPost('+id+')');

	}



	function deleteBookPost(id) {

		$('#confirm-alert').html('');

		$.post('engine.php?mod=deleteBookPost', {id: id},

		function(data, status){

			var jdata = JSON.parse(data);

			switch (jdata.status) {

				case 'success':
					$('#confirm-alert').html('<p class="alert alert-success">Книга успешно удалена!</p>');
					loadModule('Список книг', 'bookList', 1);
				break;

				case 'error':
					$('#confirm-alert').prepend('<p class="alert alert-danger">Ошибка!</p>');
				break;

			}
		});

        $('#cancel_button').click();

		return false;
	}


	function selectLib() {

    	var lib = $('#lib_select').val();
        if (lib != 0) {

        	loadModule('Список книг', 'bookList&id_lib='+lib, 1);

        }

	}



