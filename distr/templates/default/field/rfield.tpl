{strip}

{*
Ўаблон вывода полей-изображений в запросах
≈сли создать шаблон с именем rfield-XXX.tpl, где XXX это id пол€
то дл€ вывода будет использоватьс€ персональный шаблон пол€.

доступные переменные
$imgtype - тип пол€
$imgtitle - подпись к изображению
$imglink - ссылка на изображение, в виде указанном в поле
$imgsize[0] - ширина(width) изображени€ в пикселах
$imgsize[1] - высота(height) изображени€ в пикселах
$imgsize[2] - тип изображени€ (1-GIF, 2-JPG, 3-PNG, 4-SWF, 5-PSD, 6-BMP, 7-TIFF(байтовый пор€док Intel), 8-TIFF(байтовый пор€док Motorola), 9-JPC, 10-JP2, 11-JPX)
$imgsize[3] - строка height="yyy" width="xxx", котора€ может использоватьс€ непосредственно в тэге IMG
$imgsize['bits'] - количество бит используемое дл€ хранени€ иформации о цвете
$imgsize['channels'] - количество каналов дл€ JPG (3-RGB, 4-CMYK)
$imgsize['mime'] - mime тип изображени€
ћассив $imgsize это результат выполнени€ функции getimagesize()
подробнее о данных содержащихс€ в массиве смотрите в описании GD функций
≈сли GD не установлена массив $imgsize не доступен
*}

{if $imgtype == 'bild'}
	<img alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{elseif $imgtype == 'bild_links'}
	<img style="margin-right:5px" align="left" alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{elseif $imgtype == 'bild_rechts'}
	<img style="margin-left:5px" align="right" alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{/if}

{/strip}