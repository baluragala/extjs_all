<html>
<head>
    <title>Grid Interaction</title>
    <link rel="stylesheet" type="text/css" href="../lib/extjs/resources/css/ext-all.css" />
    <style>
    .delete-movie {
        background-image: url(images/cross.png);
        height: 16px;
        width: 16px;
    }
    </style>
    <script type="text/javascript" src="../lib/extjs/adapter/ext/ext-base.js"></script>
    <script type="text/javascript" src="../lib/extjs/ext-all.js"></script>
    <script>
    Ext.onReady(function(){
        Ext.BLANK_IMAGE_URL = 'images/s.gif';
        Ext.QuickTips.init();

        // small override to allow setHidden to accept either an array or single value 
        Ext.override(Ext.grid.ColumnModel, {
            setHidden : function(colIndex, hidden){
                if(Ext.isArray(colIndex)){
                    Ext.each(colIndex, function(n){
                            var c = this.config[n];
                            if(c.hidden !== hidden){
                                c.hidden = hidden;
                            }
                            this.totalWidth = null;
                            this.fireEvent("hiddenchange", this, n, hidden);
                    },this);
                }else{
                    var c = this.config[colIndex];
                    if(c.hidden !== hidden){
                        c.hidden = hidden;
                        this.totalWidth = null;
                        this.fireEvent("hiddenchange", this, colIndex, hidden);
                    }
                }
            }
        });

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

        function deleteMovie(record) {
            Ext.Msg.show({
                title: 'Remove Movie', 
                buttons: Ext.MessageBox.YESNOCANCEL,
                msg: 'Remove '+record.get('title') + '?',
                fn: function(btn){
                    if (btn == 'yes'){
                        // remove the row from our data store
                        grid.getStore().remove(record);
                    }
                }
            });
        }

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
            height: 300,
            width: 520,
            store: store,
            autoExpandColumn: 'title',
            colModel: new Ext.grid.ColumnModel({
                defaultSortable: true,
                columns: [{
                      id: 'title',
                      header: "Title",
                      dataIndex: 'title',
                      xtype: 'templatecolumn',
                      tpl: '<div style="overflow:hidden;zoom:1">' +
                            '<img src="images/{coverthumb}" style="height:68px;width:50px;float:left;margin-right:3px">' +
                            '<b style="font-size:13px;">{title}</b><br>' +
                            'Director:<i> {director}</i><br>{tagline}' +
                            '</div>'
                  },
                  {header: "Director", dataIndex: 'director', hidden: true},
                  {header: "Released", dataIndex: 'released', xtype: 'datecolumn', format: 'M d Y', id: 'released'},
                  {header: "Genre", dataIndex: 'genre', renderer: genre_name},
                  {header: "Tagline", dataIndex: 'tagline', hidden: true},
                  {header: "Price", dataIndex: 'price', renderer: 'usMoney', id: 'price'},
                  {
                      header: 'Delete',
                      sortable: false,
                      xtype: 'actioncolumn',
                      width: 40,
                      align: 'center',
                      iconCls: 'delete-movie',
                      handler: function(grid, rowIndex, colIdex, item, e) {
                          deleteMovie(grid.getStore().getAt(rowIndex));
                      }
                  }
                ]
            }),
            columnLines: true,
            selModel: new Ext.grid.RowSelectionModel({
                singleSelect: true,
                listeners: {
                    selectionchange: function(selModel) {
                        var e = !selModel.hasSelection();
                        grid.changeTitleButton.setDisabled(e);
                        grid.deleteButton.setDisabled(e);
                    }
                }
            }),
            tbar: [{
                // changes the title of the currently selected row using a messagebox
                ref: '/changeTitleButton',
                disabled: true,
                text: 'Change Title',
                handler: function(){
                    var sm = grid.getSelectionModel(),
                        sel = sm.getSelected();
                    Ext.Msg.show({
                        title: 'Change Title', 
                        prompt: true,
                        buttons: Ext.MessageBox.OKCANCEL,
                        value: sel.get('title'), 
                        fn: function(btn,text){
                            if (btn == 'ok'){
                                // set a new value for one of the
                                // columns in our selected row
                                sel.set('title', text); 
                            }
                        }
                    });
                }
            },'-',{
                // hides or shows a single pre-defined column
                text: 'Hide Price',
                handler: function(btn){
                    var cm = grid.getColumnModel(),
                        pi = cm.getIndexById('price');
                    // is this column visible?
                    if (cm.isHidden(pi)){
                        cm.setHidden(pi,false);
                        btn.setText('Hide Price');
                    }else{
                        cm.setHidden(pi,true);
                        btn.setText('Show Price');
                    }
                }
            },'-',{
                // hides or shows two pre-defined columns
                text: 'Hide Price & Released',
                handler: function(btn){
                    var cm = grid.getColumnModel();
                    var pi = cm.getIndexById('price');
                    var rl = cm.getIndexById('released');
                    // is this column visible
                    if (cm.isHidden(pi)){
                        cm.setHidden([pi,rl],false);
                        btn.setText('Hide Price & Released');
                    }else{
                        cm.setHidden([pi,rl],true);
                        btn.setText('Show Price & Released');
                    }
                }
            },'-',{
                // removes the currently selected row
                ref: '/deleteButton',
                disabled: true,
                text: 'Remove Movie',
                handler: function(){
                    var sm = grid.getSelectionModel();
                    deleteMovie(sm.getSelected());
                }
            }]
         });

        var gridTip = new Ext.ToolTip({
            renderTo: document.body,
            target: grid.getView().mainBody,
            delegate: ':any(td.x-grid3-col|img.delete-movie)',
            tpl: '<div>{title}.</div><div><div style="float:left">{header} =&#160;</div>{value}</div>',
            listeners: {
                beforeshow: function() {
                    var gridView = grid.getView(),
                        store = grid.getStore(),
                        target = this.triggerElement,
                        rowIndex = gridView.findRowIndex(target),
                        rec = store.getAt(rowIndex),
                        cellIndex = gridView.findCellIndex(target),
                        column = grid.getColumnModel().getColumnAt(cellIndex),
                        fieldName = column.dataIndex;

                    if (fieldName) {
                        this.update({
                            title: rec.get('title'),
                            header: column.header,
                            value: column.renderer(rec.get(fieldName), {}, rec, rowIndex, cellIndex, store)
                        });
                    } else if (Ext.fly(target).is('img.delete-movie')) {
                        this.update("Click to delete " + rec.get('title'));
                    } else {
                        return false;
                    }
                }
            }
        });
    });
    </script>
</head>
<body>
</body>
</html>