
<form method="get" action="/index.php">
            <input type="hidden" name="module" value="search" />
            <input style="width:99%" class="query" value="Поиск по сайту..." name="query" type="text" id="query" onfocus="if (this.value == 'Поиск по сайту...') {ldelim}this.value = '';{rdelim}" onblur="if (this.value=='') {ldelim}this.value = 'Поиск по сайту...';{rdelim}" /><br />
            
			<div align="right" style="float:right;"><input type="submit" class="button" value="{#SEARCH_BUTTON#}" style="width:100px;"/></div>
</form>


