<html>
<head>
    <title>Grid Selection Model</title>
    <link rel="stylesheet" type="text/css" href="../lib/extjs/resources/css/ext-all.css" />
    <script type="text/javascript" src="../lib/extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../lib/extjs/ext-all.js"></script>
    <script>
    Ext.onReady(function(){
        Ext.BLANK_IMAGE_URL = 'images/s.gif';
        Ext.QuickTips.init();

        var genres = new Ext.data.SimpleStore({
            fields: ['id', 'genre'],
            data : [['1','Comedy'],['2','Drama'],['3','Action']]
        });

        function genre_name(val){
            return genres.queryBy(function(rec){
                return rec.data.id == val;
            }).itemAt(0).data.genre;
        }

        // A longer format that does the same thing as above, but is easier to read
        /*function genre_name(val){
            return genres.queryBy(function(rec){
                if (rec.data.id == val){
                    return true;
                }else{
                    return false;
                }
            }).itemAt(0).data.genre;
        }*/

        var Movie = Ext.data.Record.create([
            'id',
            'coverthumb',
            'title',
            'director',
            'runtime',
            {name: 'released', type: 'date', dateFormat: 'Y-m-d'},
            'genre',
            'tagline',
            {name: 'price', type: 'float'},
            {name: 'available', type: 'bool'}
        ]);
        var store = new Ext.data.Store({
            url: 'movies.xml',
            reader: new Ext.data.XmlReader({
                record: 'row',
                idPath: 'id'
            }, Movie),
            autoLoad: true
        });

        var grid = new Ext.grid.GridPanel({
            renderTo: document.body,
            frame: true,
            title: 'Movie Database',
            height: 400,
            width: 600,
            store: store,
            autoExpandColumn: 'title',
            colModel: new Ext.grid.ColumnModel({
                defaultSortable: true,
                columns: [{
                      id: 'title',
                      header: "Title",
                      dataIndex: 'title',
                      xtype: 'templatecolumn',
                      tpl: '<img src="images/{coverthumb}" width="50" height="68" align="left">'+
                            '<b style="font-size:13px;">{title}</b><br>'+
                            'Director:<i> {director}</i><br>{tagline}'
                  },
                  {header: "Director", dataIndex: 'director', hidden: true},
                  {header: "Released", dataIndex: 'released', xtype: 'datecolumn', format: 'M d Y'},
                  {header: "Genre", dataIndex: 'genre', renderer: genre_name},
                  {header: "Tagline", dataIndex: 'tagline', hidden: true},
                  {header: "Price", dataIndex: 'price', renderer: 'usMoney'}
                ]
            }),
            columnLines: true,
            selModel: new Ext.grid.RowSelectionModel({
                singleSelect: true,
                listeners: {
                    rowselect: function(selModel, index, record) {
                       Ext.Msg.alert('You Selected', record.get('title'));
                    },
                    selectionchange: function(selModel) {
                        grid.setButton.setDisabled(!selModel.hasSelection());
                    }
                }
            }),
            tbar: [{
                ref: '/setButton',
                disabled: true,
                text: 'Set',
                handler: function(){
                    grid.getSelectionModel().getSelected().set('title', 'New Value');
                }
            }]
         });
    });
    </script>
</head>
<body>
</body>
</html>