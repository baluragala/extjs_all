<html>
<head>
	<title>Toolbar Example</title>
	<link rel="stylesheet" type="text/css" href="../lib/extjs/resources/css/ext-all.css" />
	<script type="text/javascript" src="../lib/extjs/adapter/ext/ext-base.js"></script>
	<script type="text/javascript" src="../lib/extjs/ext-all.js"></script>
	<script>
	Ext.onReady(function(){
//	    Assign Movies to reference what is *returned* from this function *call*.
		var Movies = function() {
			var helpbody;
			return {
				showHelp : function(btn){
					Movies.doLoad(btn.helpfile);
				},
				doSearch : function(field, keyEvent){
					if (keyEvent.getKey() == Ext.EventObject.ENTER) {
						Movies.doLoad(field.getValue());
					}
				},
				doLoad : function(file){
					helpbody = helpbody || Ext.getBody().createChild({tag:'div'});
					helpbody.load({
						url: 'html/' + file + '.txt'
					});
				},
				setQuality: function(q) {
					helpbody = helpbody || Ext.getBody().createChild({tag:'div'});
					helpbody.update(q);
				}
			};
		}(); // This function literal is executed
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
					text: 'Better'
				},{
					text: 'Good'
				},{
					text: 'Best'
				}]
			},{
				xtype: 'tbseparator'
			},{
				xtype: 'tbsplit',
				text: 'Help',
				helpfile: 'help',
				handler: Movies.showHelp,
				menu: [{
					text: 'Genre',
					helpfile: 'genre',
					handler: Movies.showHelp
				},{
					text: 'Director',
					helpfile: 'director',
					handler: Movies.showHelp
				},{
					text: 'Title',
					helpfile: 'title',
					handler: Movies.showHelp
				}]
			},{
				xtype: 'tbseparator'
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