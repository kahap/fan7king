/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.pasteFromWordRemoveFontStyles = false; 
    config.pasteFromWordRemoveStyles = false;
	config.font_names = "新細明體;標楷體;微軟正黑體;" +config.font_names ;
};
  // 把圖片預設style取代掉
  CKEDITOR.on( 'instanceReady', function( ev )
  {
    var editor = ev.editor,
    dataProcessor = editor.dataProcessor,
    htmlFilter = dataProcessor && dataProcessor.htmlFilter;

    // Output properties as attributes, not styles.
    htmlFilter.addRules(
    {
      elements :
      {
        $ : function( element )
        {
          // Output dimensions of images as width and height
          if ( element.name == 'img' )
          {
            var style = element.attributes.style;

            if ( style )
            {
              // Get the width from the style.
              var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec( style ),
                width = match && match[1];

              // Get the height from the style.
              match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec( style );
              var height = match && match[1];

              if ( width )
              {
                element.attributes.style = element.attributes.style.replace( /(?:^|\s)width\s*:\s*(\d+)px;?/i , '' );
                // element.attributes.width = width;
              }

              if ( height )
              {
                element.attributes.style = element.attributes.style.replace( /(?:^|\s)height\s*:\s*(\d+)px;?/i , '' );
                // element.attributes.height = height;
              }
            }
          }

          if ( !element.attributes.style )
            delete element.attributes.style;

          return element;
        }
      }
    });
  });
