<html>
<head>
	<title>Forms Example</title>
	<link rel="stylesheet" type="text/css" href="../lib/extjs/resources/css/ext-all.css" />
 	<script type="text/javascript" src="../lib/extjs/adapter/ext/ext-base.js"></script>
	<script type="text/javascript" src="../lib/extjs/ext-all.js"></script>
	<script>
	Ext.onReady(function(){
		Ext.BLANK_IMAGE_URL = 'images/s.gif';
		Ext.QuickTips.init();

		Ext.form.VTypes.nameVal  = /^[A-Z][A-Za-z]+\s[A-Z][A-Za-z]+$/;
		Ext.form.VTypes.nameMask = /[A-Za-z ]/;
		Ext.form.VTypes.nameText = 'Invalid Director Name.';
		Ext.form.VTypes.name 	= function(v){
			return Ext.form.VTypes.nameVal.test(v);
		};

		new Ext.FormPanel({ 
			url: 'movie-form-submit.php',
			renderTo: document.body,
			frame: true,
			title: 'Movie Information Form',
			width: 250,
			items: [{
				xtype: 'textfield',
				fieldLabel: 'Title',
				name: 'title',
				allowBlank: false
			},{
				xtype: 'textfield',
				fieldLabel: 'Director',
				name: 'director',
				vtype: 'name'
			},{
				xtype: 'datefield',
				fieldLabel: 'Released',
				name: 'released',
				disabledDays: [1,2,3,4,5]
			}]
		});
	});
	</script>
</head>
<body>
</body>
</html>