function save(id) {   // Сохранение изменений при редактировании статической страницы
	  $.post('statica/db.php', $('#statForma').serialize(), standartAlert, 'xml');
}