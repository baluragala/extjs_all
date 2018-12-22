<html>
<head>
	<title>Toolbar Example</title>
	<link rel="stylesheet" type="text/css" href="../lib/extjs/resources/css/ext-all.css" />
	<script type="text/javascript" src="../lib/extjs/adapter/ext/ext-base.js"></script>
	<script type="text/javascript" src="../lib/extjs/ext-all.js"></script>
	<script>
	Ext.onReady(function(){
		new Ext.Toolbar({
			renderTo: document.body,
			items: [{
				xtype: 'button',
				text: 'Button'
			},{
				xtype: 'button',
				text: 'Menu Button',
				menu: [{
					text: 'Better'
				},{
					text: 'Good'
				},{
					text: 'Best'
				}]
			},{
				xtype: 'splitbutton',
				text: 'Split Button',
				menu: [{
					text: 'Item One'
				},{
					text: 'Item Two'
				},{
					text: 'Item Three'
				}]
			}, {
				xtype: 'cycle',
				showText: true,
				minWidth: 100,
				prependText: 'Quality: ',
				items: [{
					text: 'High',
					checked: true
				}, {
					text: 'Medium'
				}, {
					text: 'Low'
				}]
			}, {
				text: 'Horizontal',
				toggleGroup: 'orientation-selector'
			}, {
				text: 'Vertical',
				toggleGroup: 'orientation-selector'
			}]
		});
	});
	</script>
</head>
<body>
</body>
</html>