/*
 *******************************************************************************
 * CLASS
 * AddressDetail
 * 
 * PURPOSE
 * To format address specific information of a data object
 *******************************************************************************
 */

// Apply custom namespace
Ext.namespace('CRM.panels');

// Extend the Ext.Panel component
CRM.panels.AddressDetail = Ext.extend(Ext.Panel,{
	// constructor properties
	width:350,
	
	height:125,
	
	data: {
		ID: 0,
		FIRSTNAME: '',
		LASTNAME: '',
		EMAIL: '',
		ADDRESSTYPE: 'Home (mailing)',
		STREET1: '',
		STREET2: '',
		STREET3: '',
		CITY: '',
		STATE: '',
		ZIP: '',
		PHONETYPE: 'Home',
		PHONE: ''
	},
	
	split: false,
	
 	tpl: new Ext.XTemplate([
 		'<b>{ADDRESSTYPE} Address</b><br />',
 		'<img src="/resources/images/icons/silk/database_edit.gif" width="16" height="16" id="addrEdit_{ID}" class="iconLnk" align="Edit Address" border="0" />{STREET1}<br />',
 		'<tpl if="STREET2.length &gt; 0">',
 			'<img src="/resources/images/s.gif" width="21" height="16" />{STREET2}<br />',
 		'</tpl>',
 		'<tpl if="STREET3.length &gt; 0">',
 			'<img src="/resources/images/s.gif" width="21" height="16" />{STREET3}<br />',
 		'</tpl>',
 		'<img src="/resources/images/s.gif" width="21" height="16" />{CITY}, {STATE} {ZIP}'
 	]),
 	
 	// Overriden parent object method, with additional functionality
 	initComponent: function(){
		CRM.panels.AddressDetail.superclass.initComponent.call(this);
		if (typeof this.tpl === 'string') {
			this.tpl = new Ext.XTemplate(this.tpl);
		}
 	},
 	
 	// Overriden parent object method, with additional functionality
 	onRender: function(ct, position) {
		CRM.panels.UserDetail.superclass.onRender.call(this, ct, position);
		if (this.data) {
			this.update(this.data);
		}
	},
	
	// Custom method of component
	update: function(data) {
		this.data = data;
		this.tpl.overwrite(this.body, this.data);
	}
});

// Register the component as an xtype for lazy instantiation
Ext.reg('addrdetail',CRM.panels.AddressDetail);