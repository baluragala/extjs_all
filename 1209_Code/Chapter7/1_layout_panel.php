<html>
<head>
    <title>Basic Layout</title>
    <link rel="stylesheet" type="text/css" href="../lib/extjs/resources/css/ext-all.css" />
    <script type="text/javascript" src="../lib/extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../lib/extjs/ext-all.js"></script>
    <script>
    Ext.BLANK_IMAGE_URL = 'images/s.gif';
    Ext.onReady(function(){

        new Ext.Panel({
            renderTo: document.body,
            title: "I arrange two child boxes horizontally using 'hbox' layout!",
            height: 400,
            width: 600,
            layout: {
                type: 'hbox',
                align: 'stretch',
                padding: 5
            },
            items: [{
                xtype: 'box',
                flex: 1,
                style: 'border: 1px solid #8DB2E3',
                margins: '0 3 0 0',
                html: 'Left box'
            }, {
                xtype: 'box',
                flex: 1,
                style: 'border: 1px solid #8DB2E3',
                margins: '0 0 0 2',
                html: 'Right box'
            }],
            style: 'padding:10px'
        });
    });
    </script>
</head>
<body>
</body>
</html>