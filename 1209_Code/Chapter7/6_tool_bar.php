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

        var Movies = function() {
            return {
                showHelp: function(btn){
                    if (!moreInfo.isVisible()){
                        moreInfo.expand();
                    }
                    moreInfo.load({url:'html/'+btn.helpfile+'.txt'});
                },
                showFullDesc : function(grid,index){
                    var record = grid.getSelectionModel().getSelected().data;
                    if (!moreInfo.isVisible()){
                        moreInfo.expand();
                    }
                    moreInfo.load({url:'html/'+record.id+'.txt'});
                },
                doSearch : function(field, evt){
                    if (!moreInfo.isVisible()){
                        moreInfo.expand();
                    }
                    if (evt.getKey() == evt.ENTER) {
                        moreInfo.body.dom.innerHTML = Ext.getCmp('genre').getValue()+' : '+field.getValue();
                    }
                },
                showFullDetails : function(grid,index){
                    if (moreInfo.isVisible()){
                        moreInfo.collapse();
                    }
                    var record = grid.getSelectionModel().getSelected().data;
                    Ext.getCmp('movieview').findById('movietabs').add({
                        title: record.title,
                        close: true
                    }).show().load({url: 'html/'+record.id+'.txt'});
                }
            };
        }();

        function genre_name(val){
            return genres.queryBy(function(rec){
                return rec.data.id == val;
            }).itemAt(0).data.genre;
        }

        function title_img(val, x, store){
            return  '<img src="images/'+store.data.coverthumb+'" width="50" height="68" align="left">'+
                    '<b style="font-size: 13px;">'+val+'</b><br>'+
                    'Director:<i> '+store.data.director+'</i><br>'+
                    store.data.tagline;
        }

        var Movie = Ext.data.Record.create([
            'id',
            'coverthumb',
            'title',
            'director',
            {name: 'released', type: 'date', dateFormat: 'Y-m-d'},
            'genre',
            'tagline',
            {name: 'price', type: 'float'},
            {name: 'available', type: 'bool'}
        ]);

        var store = new Ext.data.GroupingStore({
            url: 'movies.json',
            sortInfo: {
                field: 'genre', 
                direction: "ASC"
            },
            groupField: 'genre',
            reader: new Ext.data.JsonReader({
                root:'rows',
                id:'id'
            }, Movie),
            autoLoad: true
        });

        var genres = new Ext.data.SimpleStore({
            fields: ['id', 'genre'],
            data : [['0','New Genre'],['1','Comedy'],['2','Drama'],['3','Action']]
        });

        var toolbarConfig = {
            region: 'north',
            height: 27,
            xtype: 'toolbar',
            items: [' ', {
                text: 'Button',
                handler: function(btn){
                    btn.disable();
                }
            }, '->', {
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
            }, '-', {
                xtype: 'splitbutton',
                text: 'Help',
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
            }, '-', {
                xtype: 'tbtext',
                text: 'Genre:'
            }, ' ', {
                xtype: 'combo',
                id: 'genre',
                name: 'genre',
                mode: 'local',
                store: genres,
                displayField: 'genre',
                triggerAction: 'all',
                width: 70
            }, ' ',{
                xtype: 'textfield',
                listeners: {
                    specialkey: Movies.doSearch
                }
            }, ' '
            ],
            margins: '0 0 5 0'
        };

        var moreInfo = new Ext.Panel({
            title: 'More Info',
            collapsible: true,
            region: 'east',
            split: true,
            width: 200,
            bodyStyle: 'padding:5px;font-family:Arial;font-size:10pt;',
            margins: '0 5 0 0'
        });

        var viewport = new Ext.Viewport({
            layout: "border",
            defaults: {
                bodyStyle: 'padding:5px;',
            },
            items: [toolbarConfig,{
                region: 'west',
                xtype: 'panel',
                split: true,
                width: 200,
                html: 'West',
                margins: '0 0 0 5'
            },{
                region: 'center',
                xtype: 'tabpanel',
                bodyStyle: '',
                activeTab: 0,
                items: [{
                    title: 'Movie Grid',
                    xtype: 'grid',
                    store: store,
                    autoExpandColumn: 'title',
                    colModel: new Ext.grid.ColumnModel({
                        columns: [
                            {header: "Title", dataIndex: 'title', renderer: title_img, id: 'title', sortable: true},
                            {header: "Director", dataIndex: 'director', hidden: true},
                            {header: "Released", dataIndex: 'released', sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), width: 80},
                            {header: "Genre", dataIndex: 'genre', renderer: genre_name, sortable: true, width: 80},
                            {header: "Tagline", dataIndex: 'tagline', hidden: true},
                            {header: "Price", dataIndex: 'price', renderer: 'usMoney', sortable: true, width: 60}
                        ]
                    }),
                    view: new Ext.grid.GroupingView(),
                    sm: new Ext.grid.RowSelectionModel({
                        singleSelect: true
                    }) 
                },{
                    xtype: 'container',
                    title: 'Movie Descriptions',
                    layout: 'accordion',
                    defaults: {
                        border: false,
                        autoScroll: true
                    },
                    items: [{
                        title: 'Office Space',
                        autoLoad: 'html/1.txt'
                    },{
                        title: 'Super Troopers',
                        autoLoad: 'html/3.txt'
                    },{
                        title: 'American Beauty',
                        autoLoad: 'html/4.txt'
                    }]
                }],
                margins: '0 0 0 0'
            }, moreInfo, {
                region: 'south',
                xtype: 'panel',
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