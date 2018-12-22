<html>
<head>
	<title>Toolbar Example</title>
	<link rel="stylesheet" type="text/css" href="../lib/extjs/resources/css/ext-all.css" />
	<script type="text/javascript" src="../lib/extjs/adapter/ext/ext-base.js"></script>
	<script type="text/javascript" src="../lib/extjs/ext-all.js"></script>
	<script>
	Ext.onReady(function(){
		var Movies = function() {
			var helpbody,
				winhelp;
			return {
				showHelp : function(btn){
					Movies.doLoad(btn.helpfile);
				},
				doSearch : function(field, keyEvent){
					if (keyEvent.getKey() == Ext.EventObject.ENTER) {
						Movies.doLoad(field.getValue());
					}
				},
				showWinHelp : function(btn){
					if (!winhelp){
						winhelp = new Ext.Window({
							title: 'Help',
							width: 300,
							height: 300,
							renderTo: document.body,
							closeAction: 'hide',
							layout: 'fit',
							tbar: [{
								text: 'Close',
								handler: function(){
									winhelp.hide();
								}
							},{
								text: 'Disable',
								handler: function(t){
									t.disable();
								}
							}]
						});
					}
					Ext.Ajax.request({
						url: 'html/' + btn.helpfile + '.json',
						success: function(xhr, options) {
							var newComponentConfig = Ext.util.JSON.decode(xhr.responseText);
							winhelp.removeAll();
							winhelp.add(newComponentConfig);
							winhelp.doLayout();
						}
					});
					winhelp.show();
				},
				doLoad : function(file){
					helpbody = helpbody || Ext.getBody().createChild({tag:'div'});
					helpbody.load({
						url: 'html/' + file + '.txt'
					});
				}
			};
		}();
		var genres = new Ext.data.SimpleStore({
			fields: ['id', 'genre'],
			data : [['1','Comedy'],['2','Drama'],['3','Action']]
		});
		var toolbar = new Ext.Toolbar({
			renderTo: document.body,
			items: [{
				xtype: 'tbspacer'
			},{
				xtype: 'tbbutton',
				text: 'Button',
				handler: function(btn){
					btn.disable();
				}
			},{
				xtype: 'tbfill'
			},{
				xtype: 'tbbutton',
				cls: 'x-btn-icon',
				icon: 'images/bomb.png'
			},{
				xtype: 'tbbutton',
				text: 'Menu Button',
				menu: [{
					text: 'Better',
					checked: true,
					group: 'quality'
				}, {
					text: 'Good',
					checked: false,
					group: 'quality'
				}, {
					text: 'Best',
					checked: false,
					group: 'quality'
				}]
			},{
				xtype: 'tbseparator'
			},{
				xtype: 'tbsplit',
				text: 'Help',
				helpfile: 'help',
				handler: Movies.showWinHelp,
				menu: [{
					text: 'Genre',
					helpfile: 'genre',
					handler: Movies.showWinHelp
				},{
					text: 'Director',
					helpfile: 'director',
					handler: Movies.showWinHelp
				},{
					text: 'Title',
					helpfile: 'title',
					handler: Movies.showWinHelp
				}]
			},{
				xtype: 'tbseparator'
			},{
				xtype: 'tbtext',
				text: 'Genre:'
			},{
				xtype: 'tbspacer'
			},{
				xtype: 'combo',
				triggerAction: 'all',
				editable: false,
				id: 'genre',
				name: 'genre',
				mode: 'local',
				store: genres,
				displayField:'genre',
				width: 70
			},{
				xtype: 'tbspacer'
			},{
				xtype: 'textfield',
				listeners: {
					specialkey: Movies.doSearch
				}
			},{
				xtype: 'tbspacer'
			}]
		});
	});
	</script>
</head>
<body>
</body>
</html>