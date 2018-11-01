/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'zh';
	// config.uiColor = '#AADC6E';
	config.pasteFromWordRemoveFontStyles = false; 
    config.pasteFromWordRemoveStyles = false;
	config.toolbar = [
        { name: 'basicstyles', items: [ 'Superscript' ] }
    ];
	// config.removePlugins = 'resize';
	config.resize_enabled = false;
	config.autoUpdateElement = true;
	config.enterMode = CKEDITOR.ENTER_BR;
	config.removePlugins = 'elementspath';
	config.fontSize_defaultLabel = '0.9em';
	config.font_defaultLabel = 'Arial';
};
