<html>
<head>
<title>Layout With Many Additions</title>
<link rel="stylesheet" type="text/css" href="../lib/extjs/resources/css/ext-all.css" />
<script type="text/javascript" src="../lib/extjs/adapter/ext/ext-base.js"></script>
<script type="text/javascript" src="../lib/extjs/ext-all.js"></script>
<script>
    Ext.onReady(function(){
        Ext.BLANK_IMAGE_URL = 'images/s.gif';
        Ext.chart.Chart.CHART_URL = '../resources/charts.swf';
        Ext.QuickTips.init();

        Ext.form.VTypes.nameVal  = /^[A-Z][A-Za-z]+\s[A-Z][A-Za-z]+$/;
        Ext.form.VTypes.nameMask = /[A-Za-z ]/;
        Ext.form.VTypes.nameText = 'Invalid Director Name.';
        Ext.form.VTypes.name     = function(v){
            return Ext.form.VTypes.nameVal.test(v);
        };

        var Movies = function() {

            var editRecord;

            return {
                showHelp : function(btn){
                    if (!moreInfo.isVisible()){
                        moreInfo.expand();
                    }
                    moreInfo.load({url:'html/'+btn.helpfile+'.txt'});
                },
                showFullDesc : function(grid, index){
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
                loadMovieForm: function(sm, rowIndex, rec) {
                    editRecord = rec;
                    movieForm.getForm().loadRecord(rec);
                },
                submitMovieForm: function(){
                    if (editRecord) {
                        movieForm.getForm().updateRecord(editRecord);
                        if (store.groupField && editRecord.modified[store.groupField]) {
                            store.groupBy(store.groupField, true);
                        }
                        movieForm.getForm().submit({
                            success: function(form, action){
                                Ext.Msg.alert('Success', 'It worked');
                            },
                            failure: function(form, action){
                                Ext.Msg.alert('Warning', action.result.errormsg);
                            }
                        });
                    }
                },
                resetMovieForm: function(){
                    movieForm.getForm().reset();
                },
                addPriceInput: function(btn) {
                    btn.disable();
                    var fieldCtr = movieForm.getComponent('field-container');
                    fieldCtr.add({
                        xtype: 'numberfield',
                        fieldLabel: 'Price',
                        anchor: '100%',
                        name: 'price',
                        minValue: 10,
                        allowNegative: false
                    });
                    movieForm.ownerCt.doLayout();
                }
            };
        }();

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

        function title_img(val, x, store){
            return  '<img src="images/'+store.data.coverthumb+'" width="50" height="68" align="left">'+
                    '<b style="font-size: 13px;">'+val+'</b><br>'+
                    'Director:<i> '+store.data.director+'</i><br>'+
                    store.data.tagline;
        }

        var chartStore = new Ext.data.JsonStore({
            fields: ['year', 'comedy', 'drama', 'action'],
            root: 'data'
        });

        var loadChartFn = function(s){
            var tmpData = {}, yearData = [];
            s.each(function(rec){
                var genre = genre_name(rec.get('genre')),
                    rel_year = rec.get('released').format('Y');
                
                if(!tmpData[''+rel_year]){
                    tmpData[''+rel_year] = {Comedy:0,Drama:0,Action:0};
                }
                tmpData[''+rel_year][genre]++;
            });
            for (year in tmpData){
                yearData.push({
                    year: year,
                    comedy: tmpData[year].Comedy,
                    drama: tmpData[year].Drama,
                    action: tmpData[year].Action
                });
            }
            chartStore.loadData({data:yearData});
        };

         var Movie = Ext.data.Record.create([
            'id',
            'coverthumb',
            'title',
            'director',
            {name: 'released', type: 'date', dateFormat: 'Y-m-d'},
            'filmed_in',
            {name: 'genre', type: 'int'},
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
                root: 'rows',
                idProperty: 'id'
            }, Movie),
            autoLoad: true,
            listeners: {
                load: loadChartFn,
                add: loadChartFn,
                remove: loadChartFn,
                update: loadChartFn,
                clear: loadChartFn
            }
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
                text: 'Add Price Input',
                handler: Movies.addPriceInput
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
            autoScroll: true,
            bodyStyle: 'padding:5px;font-family:Arial;font-size:10pt;',
            margins: '0 5 5 0',
            cmargins: '0 5 5 5'
        });

        var movieForm = new Ext.form.FormPanel({
            region: 'center',
            title: 'Movie Information Form',
            width: 250,
            minSize: 250,
            layout: {
                type: 'vbox',
                align: 'stretch',
                padding: 5
            },
            items: [{
                id: 'field-container',
                autoHeight: true,
                xtype: 'container',
                layout: 'form',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: 'Title',
                    name: 'title',
                    anchor: '100%',
                    allowBlank: false,
                    listeners: {
                        specialkey: function(f,e){
                            if (e.getKey() == e.ENTER) {
                                Movies.submitMovieForm();
                            }
                        }
                    }
                },{
                    xtype: 'textfield',
                    fieldLabel: 'Director',
                    name: 'director',
                    anchor: '100%',
                    vtype: 'name'
                },{
                    xtype: 'datefield',
                    fieldLabel: 'Released',
                    anchor: '100%',
                    name: 'released'
                },{
                    xtype: 'radiogroup',
                    fieldLabel: 'Filmed In',
                    name: 'filmed_in',
                    columns: 1,
                    items: [{
                        name: 'filmed_in',
                        boxLabel: 'Color',
                        inputValue: '0'
                    },{
                        name: 'filmed_in',
                        boxLabel: 'Black & White',
                        inputValue: '1'
                    }]
                },{
                    xtype: 'checkbox',
                    fieldLabel: 'Available?',
                    name: 'available'
                },{
                    xtype: 'combo',
                    name: 'genre',
                    fieldLabel: 'Genre',
                    anchor: '100%',
                    mode: 'local',
                    store: genres,
                    displayField: 'genre',
                    valueField: 'id',
                    triggerAction: 'all',
                    listeners: {
                        select: function(field, rec, idx){
                            if (idx === 0){
                                Ext.Msg.prompt('New Genre', 'Name', Ext.emptyFn);
                            }
                        }
                    }
                }]
            },{
                xtype: 'textarea',
                name: 'description',
                flex: 1
            }],
            buttons: [{
                text: 'Save',
                handler: Movies.submitMovieForm
            }, {
                text: 'Reset',
                handler: Movies.resetMovieForm
            }]
        });

        var mainTabPanel = new Ext.TabPanel({
            region: 'center',
            id: 'movietabs',
            xtype: 'tabpanel',
            deferredRender: false,
            hideMode: 'offsets',
            activeTab: 0,
            items: [{
                title: 'Movie Grid',
                xtype: 'grid',
                store: store,
                autoExpandColumn: 'title',
                columns: [
                    {header: "Title", dataIndex: 'title', renderer: title_img, id: 'title', sortable: true},
                    {header: "Director", dataIndex: 'director', hidden: true},
                    {header: "Released", dataIndex: 'released', sortable: true, renderer: Ext.util.Format.dateRenderer('m/d/Y'), width: 80},
                    {header: "Genre", dataIndex: 'genre', renderer: genre_name, sortable: true, width: 80},
                    {header: "Tagline", dataIndex: 'tagline', hidden: true},
                    {header: "Price", dataIndex: 'price', renderer: 'usMoney', sortable: true, width: 60}
                ],
                listeners: {
                    rowclick: {
                        fn: Movies.showFullDesc
                    }
                },
                view: new Ext.grid.GroupingView(),
                sm: new Ext.grid.RowSelectionModel({
                    singleSelect: true,
                    listeners: {
                        rowselect: Movies.loadMovieForm
                    }
                })
            }],
            margins: '0 0 5 0'
        });

        var viewport = new Ext.Viewport({
            layout: 'border',
            id: 'movieview',
            items: [ toolbarConfig, {
                region: 'west',
                layout: 'border',
                xtype: 'container',
                split: true,
                width: 250,
                minSize: 250,
                split: true,
                margins: '0 0 5 5',
                collapseMode: 'mini',
                items: [{
                    region: 'north',
                    items: {
                        xtype: 'stackedbarchart',
                        store: chartStore,
                        xAxis: new Ext.chart.NumericAxis({majorUnit: 1, minorUnit: .25,stackingEnabled: true}),
                        yField: 'year',
                        series: [{
                             xField: 'comedy',
                             displayName: 'Comedy',
                             style:{
                                 color:0x953030
                             }
                        },{
                             xField: 'drama',
                             displayName: 'Drama',
                             style:{
                                 color:0xFF8C40
                             }
                        },{
                             xField: 'action',
                             displayName: 'Action',
                             style:{
                                 color:0xFFDB59
                             }
                        }],
                        extraStyle: {
                            padding: 2,
                            animationEnabled: true,
                            xAxis: {
                                majorGridLines: {
                                    color: 0xEBEBEB
                                },
                                minorTicks: {
                                    color: 0xEBEBEB
                                }
                            },
                            yAxis: {
                                labelRotation: -45
                            },
                            legend: {
                                display: 'bottom'
                            }
                        }
                    },
                    split: true,
                    height: 250
                }, movieForm ]
            }, mainTabPanel, moreInfo ]
        });
    });
    </script>
</head>
<body>
</body>
</html>