/*
* Ext JS Library 2.0
* Copyright(c) 2006-2007, Ext JS, LLC.
* licensing@extjs.com
*
* http://extjs.com/license
*/
/*
* John Wilson (Daemach) daemach@gmail.com
* http://ideamill.synaptrixgroup.com
*
* MODIFIED:
* [05.05.2008 Steve 'Cutter' Blades]
* Added the ability to read the returned JSON, even if
* you don't use the ColdFusion QueryConvertForGrid() function
* within your ColdFusion Component
*/
Ext.data.CFJsonReader = function(meta, recordType){
	this.meta = meta || {};
	this.meta.fields = recordType
	this.recordType = Ext.data.Record.create(this.meta.fields);
};

Ext.extend(Ext.data.CFJsonReader, Ext.data.DataReader, {
	read: function(response){
		var json = response.responseText;
		if(json.indexOf('"QUERY":') < 0){
			json = '{"QUERY":' + json + '}';
		}
		var o = eval("(" + json + ")");
		if (!o) {
			throw {
				message: "CFJsonReader.read: Json object not found"
			};
		}
      	if (o.metaData) {
			delete this.ef;
			this.meta = o.metaData;
			this.recordType = Ext.data.Record.create(o.metaData.fields);
			this.onMetaChange(this.meta, this.recordType, o);
		}
		return this.readRecords(o);
	},

	// private function a store will implement 
	onMetaChange: function(meta, recordType, o){},
	
	readRecords: function(json){
		var aRecords = [];
		var cList = json.QUERY.COLUMNS;
		var cData = json.QUERY.DATA;
		var idField = this.meta.id;
		
		for (var i = 0; i < cData.length; i++) {
			var oRecord = {};
		
			for (var j = 0; j < cList.length; j++) {
				oRecord[cList[j]] = cData[i][j];
			}
			if (idField) {
				var id = cData[i][cList.indexOf(idField)];
				aRecords.push(new Ext.data.Record(oRecord, id));
			} else {
				aRecords.push(new Ext.data.Record(oRecord));
			}
		}
		if(!json.TOTALROWCOUNT){
			json.TOTALROWCOUNT = aRecords.length;
		}
		//console.log(aRecords)
		return {
			success: true,
			records: aRecords,
			totalRecords: json.TOTALROWCOUNT
		};
	}
});