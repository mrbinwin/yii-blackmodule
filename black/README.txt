BlackModule
@author Dmitry Kuznetsov (mrbinwin@gmail.com)
@link https://github.com/MrBinWin/BlackModule
@version 1.0

This module protects your website content from being copied.
The following arrangements will not guarantee the safety of the content, and are not recommended for old popular
sites, but for new sites it can be useful:
- Disabling shortcut keys Ctrl+C, Ctrl+X, Ctrl+A, Ctrl+U
- Disabling the context menu on the site
- Disabling .oncopy and .ondragstart js events
- When the visitor try to copy a large amount of text (more then 200 characters)
or try to view the source code current ip is added to the list of banned and cookies is installed in the browser.
- If visitors User-Agent found in the list of known spam bots and parsers, the current ip is added to the list of
banned.

Installation:

1. Add the table 'banned' in your database (import the file sql/banned.sql), do not forget to add a table prefix,
if you use it in your application.
2. Extract the files into your modules folder (for example, /protected/modules/).
3. Edit your configuration file to register the module:
	'modules'=>array(
	    ...
		'black'
	),
	
4. Add widget to your pages:
<? Yii::app()->getModule('black'); $this->widget('NoCopyWidget'); ?>

 ****************************************
 
BlackModule

Данный модуль защищает контент сайта от копирования.
Следующие меры не гарантируют сохранность материалов сайта, и не рекомендуются для старых и посещаемых сайтов,
но для молодых сайтов могут оказаться полезными:
- Отключение сочетаний клавиш Ctrl+C, Ctrl+X, Ctrl+A, Ctrl+U
- Отключение контекстного меню на страницах сайта
- Запрет копирования (.oncopy) и перетаскивания (.ondragstart) на страницах сайта
- При попытке скопировать большой объем текста (более 200 символов) или при просмотре исходного кода сайта ip
посетителя отправляется в список забаненных, также в браузер устанавливается cookies.
При наличии cookies и заходе с другого ip, он также добавляется в бан;
При заходе с забаненного ip и другого браузера, также устанавливаются cookies.
- При совпадении User-Agent посетителя со списком известных спам-ботов и парсеров ip добавляется в список забаненных.

Установка:

1. Добавьте таблицу banned в вашу базу данных, выполнив импорт файла sql/banned.sql,
не забудьте добавить префикс таблиц, если вы используете его в вашем приложении.
2. Распакуйте файлы в вашу папку модулей (например, /protected/modules/)
3. Добавьте модуль в конфиг сайта:
	'modules'=>array(
		...
		'black'
	),
	
4. Добавьте виджет на страницы сайта:
<? Yii::app()->getModule('black'); $this->widget('NoCopyWidget'); ?>
