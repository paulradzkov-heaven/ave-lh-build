/*::::::::::::::::::::::::::::::::::::::::
 System name: cpengine
 Short Desc: Full Russian Security Power Pack
 Version: 2.0 (Service Pack 2)
 Authors:  Arcanum (php@211.ru) &  Censored!
 Date: March 18, 2008
::::::::::::::::::::::::::::::::::::::::*/

// В файле /admin/editor/fckconfig.js указать путь к этому файлу настроек
// FCKConfig.CustomConfigurationsPath = '/admin/editor/aveconfig.js' ;

FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/apanel/' ;
FCKConfig.EditorAreaCSS = '/templates/default/css/global.css';
//FCKConfig.EditorAreaCSS = FCKConfig.BasePath + 'css/fck_editorarea.css' ;
FCKConfig.EditorAreaStyles = '' ;
FCKConfig.ToolbarComboPreviewCSS = '' ;

FCKConfig.DocType = '' ;
FCKConfig.BaseHref = '' ;
FCKConfig.FullPage = false ;

FCKConfig.ProtectedSource.Add( /<\?[\s\S]*?\?>/g ) ; // PHP style server side code

FCKConfig.AutoDetectLanguage = false ;
FCKConfig.DefaultLanguage    = 'ru' ;
FCKConfig.FillEmptyBlocks    = false ;

FCKConfig.ToolbarSets["cpengine"] = [
  ['Source','-','Save','Preview'],
  ['Cut','Copy','Paste','PasteText','PasteWord'],
  ['Undo','Redo'],['Bold','Italic','Underline','StrikeThrough'],
  ['OrderedList','UnorderedList','-','Outdent','Indent','Blockquote','CreateDiv'],
  ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
  ['TextColor','BGColor','RemoveFormat'],['Link','Unlink','Anchor'], ['Image','Flash','Table','Rule','SpecialChar'], '/',
  ['Style','FontFormat','FontName','FontSize'], ['FitWindow','ShowBlocks']  // No comma for the last row.
] ;

FCKConfig.ToolbarSets["cpengine_small"] = [
  ['Source','-','Save'],
  ['Cut','Copy','Paste','PasteText','PasteWord'],
  ['Undo','Redo'],['Bold','Italic','Underline','StrikeThrough'],['OrderedList','UnorderedList'],['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],['Link','Unlink','Anchor','Image']  // No comma for the last row.
] ;



FCKConfig.ToolbarSets["Simple"] = [
  ['Source','Bold','Italic','-','OrderedList','UnorderedList','-','Link','Unlink','-','Image','-','RemoveFormat','-','Preview',
  'FontFormat','FontName','FontSize']
] ;

FCKConfig.EnterMode = 'br' ;       // p | div | br
FCKConfig.ShiftEnterMode = 'br' ;  // p | div | br

FCKConfig.FontFormats = 'div;p;h1;h2;h3;h4;h5;h6;pre;address' ;

FCKConfig.LinkBrowserURL    = "../../../../admin/browser.php?typ=bild&mode=fck&target=link" ;
FCKConfig.LinkBrowserLnkUrl = "../../../../admin/browser.php?typ=bild&mode=fck&target=link_image" ;
FCKConfig.ImageBrowserURL   = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtUrl" ;
FCKConfig.FlashBrowserURL   = "../../../../admin/browser.php?typ=bild&mode=fck&target=txtUrl" ;

FCKConfig.LinkUpload  = false ;
FCKConfig.ImageUpload = false ;
FCKConfig.FlashUpload = false ;
