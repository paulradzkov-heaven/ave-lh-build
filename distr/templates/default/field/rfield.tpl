{strip}

{*
������ ������ �����-����������� � ��������
���� ������� ������ � ������ rfield-XXX.tpl, ��� XXX ��� id ����
�� ��� ������ ����� �������������� ������������ ������ ����.

��������� ����������
$imgtype - ��� ����
$imgtitle - ������� � �����������
$imglink - ������ �� �����������, � ���� ��������� � ����
$imgsize[0] - ������(width) ����������� � ��������
$imgsize[1] - ������(height) ����������� � ��������
$imgsize[2] - ��� ����������� (1-GIF, 2-JPG, 3-PNG, 4-SWF, 5-PSD, 6-BMP, 7-TIFF(�������� ������� Intel), 8-TIFF(�������� ������� Motorola), 9-JPC, 10-JP2, 11-JPX)
$imgsize[3] - ������ height="yyy" width="xxx", ������� ����� �������������� ��������������� � ���� IMG
$imgsize['bits'] - ���������� ��� ������������ ��� �������� ��������� � �����
$imgsize['channels'] - ���������� ������� ��� JPG (3-RGB, 4-CMYK)
$imgsize['mime'] - mime ��� �����������
������ $imgsize ��� ��������� ���������� ������� getimagesize()
��������� � ������ ������������ � ������� �������� � �������� GD �������
���� GD �� ����������� ������ $imgsize �� ��������
*}

{if $imgtype == 'bild'}
	<img alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{elseif $imgtype == 'bild_links'}
	<img style="margin-right:5px" align="left" alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{elseif $imgtype == 'bild_rechts'}
	<img style="margin-left:5px" align="right" alt="{$imgtitle}" src="{$imglink}" border="0" {$imgsize[3]} />

{/if}

{/strip}