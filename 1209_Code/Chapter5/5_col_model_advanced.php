<html>
<head>
    <title>Grid Column Model</title>
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
            return genres.queryBy(function(rec, id){
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
            data: [
                [1, "84m.jpg", "Office Space",     "Mike Judge",        89,  "1999-02-19", 1, "Work Sucks",                              19.95, true],
                [3, "42m.jpg", "Super Troopers",   "Jay Chandrasekhar", 100, "2002-02-15", 1, "Altered State Police",                    14.95, 1],
                [4, "12m.jpg", "American Beauty",  "Sam Mendes",        122, "1999-10-01", 2, "... Look Closer",                         19.95, true],
                [5, "49m.jpg", "The Big Lebowski", "Joel Coen",         117, "1998-03-06", 1, "The \"Dude\"",                            21.9,  true],
                [6, "94m.jpg", "Fight Club",       "David Fincher",     139, "1999-10-15", 3, "How much can you know about yourself...", 19.95, true]
            ],
            reader: new Ext.data.ArrayReader({
                idIndex: 0
            }, Movie)
        });

        var grid = new Ext.grid.GridPanel({
            renderTo: document.body,
            frame: true,
            title: 'Movie Database',
            height: 400,
            width: 520,
            store: store,
            autoExpandColumn: 'title',
            colModel: new Ext.grid.ColumnModel({
                defaultSortable: true,
                columns: [
                  {
                      id: 'title',
                      header: "Title",
                      dataIndex: 'title',
                      xtype: 'templatecolumn',
                      tpl: '<img src="images/{coverthumb}" width="50" height="68" align="left">'+
                            '<b style="font-size:13px;">{title}</b><br>'+
                            'Director:<i> {director}</i><br>{tagline}'
                  },
                  {header: "Director", dataIndex: 'director', hidden: true},
                  {header: "Released", dataIndex: 'released', xtype: 'datecolumn', format: 'M d Y', width: 80},
                  {header: "Genre", dataIndex: 'genre', renderer: genre_name, width: 80},
                  {header: "Tagline", dataIndex: 'tagline', hidden: true},
                  {header: "Price", dataIndex: 'price', renderer: 'usMoney', width: 60}
                ]
            }),
            columnLines: true
        });

        grid.getColumnModel().on('columnmoved',function(cm,oindex,nindex) {
            var dirmsg = '', title = 'You Moved '+cm.getColumnHeader(nindex);
            if (oindex > nindex){
                dirmsg = (oindex-nindex)+' Column(s) to the Left';
            }else{
                dirmsg = (nindex-oindex)+' Column(s) to the Right';
            }
            Ext.Msg.alert(title,dirmsg);
        });
    });
    </script>
</head>
<body>
</body>
</html>