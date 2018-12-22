/*
 * Chapter 12 Example 3
 * Data Store from custom reader
 */

// Save all processing until the
// DOM is completely loaded
Ext.onReady(function(){
	var recordModel = [
		{name:'file_name',mapping:'NAME'},
		{name:'file_size',mapping:'SIZE'},
		{name:'type',mapping:'TYPE'},
		{name:'lastmod',mapping:'DATELASTMODIFIED'},
		{name:'file_attributes',mapping:'ATTRIBUTES'},
		{name:'mode',mapping:'MODE'},
		{name:'directory',mapping:'DIRECTORY'}
	];
	
	var ourReader = new Ext.data.CFJsonReader({id:'NAME',root:'DATA'},recordModel);
	
	var ourStore = new Ext.data.Store({
		url:'Chapter12Example.cfc',
		baseParams:{
			method: 'getFileInfoByPath',
			returnFormat: 'JSON',
			queryFormat: 'column',
			startPath: '/images/'
		},
		reader: ourReader,
		fields: recordModel,
		listeners:{
			beforeload:{
				fn: function(store, options){
					if (options.startPath && (options.startPath.length > 0)){
						store.baseParams.startPath = options.startPath;	
					}
				},
				scope:this
			},
			load: {
				fn: function(store,records,options){
					console.log(records);
				}
			},
			scope:this
		}
	});
	
	ourStore.load();
});
