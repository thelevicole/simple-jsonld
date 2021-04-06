window.jsonlint = require( 'jsonlint-mod' );

require( 'codemirror/mode/javascript/javascript' );
require( 'codemirror/addon/edit/matchbrackets' );
require( 'codemirror/addon/comment/continuecomment' );
require( 'codemirror/addon/comment/comment' );
require( 'codemirror/addon/lint/lint' );
require( 'codemirror/addon/lint/javascript-lint' );
require( 'codemirror/addon/lint/json-lint' );
require( 'codemirror/addon/display/autoRefresh' );
const CodeMirror = require( 'codemirror' );

( function( $ ) {
	'use strict';

	if ( typeof simpleJsonLdInputVars === 'object' ) {
		$( `textarea.${simpleJsonLdInputVars.dom_class}` ).each( function() {
			const $textarea = $( this );

			const codeEditor = CodeMirror.fromTextArea( $textarea.get( 0 ), {
				lineNumbers: true,
				matchBrackets: true,
				autoCloseBrackets: true,
				mode: 'application/' + simpleJsonLdInputVars.format,
				lineWrapping: true,
				gutters: [ 'CodeMirror-lint-markers' ],
				line: true,
				lint: true,
				autoRefresh: true
			} );

			codeEditor.on( 'change', () => {
				codeEditor.save();
			} );

		} );
	}

} )( jQuery );

