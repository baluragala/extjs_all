<html>
<head>
    <title>Basic Layout</title>
    <link rel="stylesheet" type="text/css" href="../lib/extjs/resources/css/ext-all.css" />
    <script type="text/javascript" src="../lib/extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../lib/extjs/ext-all.js"></script>
    <script>
    Ext.onReady(function(){
        Ext.BLANK_IMAGE_URL = 'images/s.gif';
        Ext.QuickTips.init();

        var viewport = new Ext.Viewport({
            layout: "border",
            defaults: {
                bodyStyle: 'padding:5px;',
            },
            items: [{
                region: "north",
                html: 'North',
                margins: '5 5 5 5'
            },{
                region: 'west',
                split: true,
                collapsible: true,
                collapseMode: 'mini',
                title: 'Some Info',
                width: 200,
                minSize: 200,
                html: 'West',
                margins: '0 0 0 5'
            },{
                region: 'center',
                html: 'Center',
                margins: '0 0 0 0'
            },{
                region: 'east',
                split: true,
                width: 200,
                html: 'East',
                margins: '0 5 0 0'
            },{
                region: 'south',
                html: 'South',
                margins: '5 5 5 5'
            }]
        });
    });
    </script>
</head>
<body>
</body>
</html>