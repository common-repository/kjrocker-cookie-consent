(function() {
	tinymce.PluginManager.add('my_mce_button', function( editor, url ) {
		var elem = url.split("/");
	var str = "";
 	for (var i = 0; i < elem.length-1; i++)
	str += elem[i] + "/";
		editor.addButton('my_mce_button', {
			title: 'kjrocker Cookie Consent',
			tooltip: 'kjrocker Cookie Consent',
			image: str + 'img/cookie.png',
			onclick: function() {
				
				editor.windowManager.open( {
                    title: 'kjrocker Cookie Consent',
                    body: [
					{
                        type: 'textbox',
                        name: 'height',
                        label: 'Height:',
						value: '100%'
                    },
					{
                        type: 'textbox',
                        name: 'width',
                        label: 'Width:',
						value: '200px'
                    }
					],
                    onsubmit: function( e ) {
						if (!e.data.height.includes('px') && !e.data.height.includes('%')) {
							e.data.height = e.data.height + 'px';
						}
						if (!e.data.width.includes('px') && !e.data.width.includes('%')) {
							e.data.width = e.data.width + 'px';
						}
						selected = tinyMCE.activeEditor.selection.getContent();
						if( selected ){
							editor.insertContent( '[cookie height="' + e.data.height + '" width="' + e.data.width + '"]' + selected + '[/cookie]' );
						}else{
							editor.insertContent( '[cookie height="' + e.data.height + '" width="' + e.data.width + '"]' );
						}
                    }

                } );
			}
		});
	});
})();