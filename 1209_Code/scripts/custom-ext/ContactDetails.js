/*
 *******************************************************************************
 * CLASS
 * ContactDetails
 * 
 * PURPOSE
 * To format contact details of a data object
 *******************************************************************************
 */

// Apply custom namespace
Ext.namespace('CRM.panels');

// Extend the Ext.Panel component
CRM.panels.ContactDetails = Ext.extend(Ext.Panel,{
	// constructor properties
	width: 350,
	
	height: 250,
	
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
	
	tpl: new Ext.XTemplate([
 		'<img src="/resources/images/s.gif" width="21" height="16" /><b>{FIRSTNAME} {LASTNAME}</b><br />',
 		'<img src="/resources/images/icons/silk/database_edit.gif" width="16" height="16" id="emailEdit_{ID}" class="iconLnk" align="Edit Email Address" border="0" />{EMAIL}<br />',
 		'<img src="/resources/images/icons/silk/database_edit.gif" width="16" height="16" id="phoneEdit_{ID}" class="iconLnk" align="Edit Phone" border="0" />{PHONE} ({PHONETYPE})<br />',
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
		CRM.panels.ContactDetails.superclass.initComponent.call(this);
		if (typeof this.tpl === 'string') {
			this.tpl = new Ext.XTemplate(this.tpl);
		}
		// The code below, and the onUpdate method at the bottom
		// are provided as examples of how to create custom events
		/*this.addEvents('update');
		this.addListener({
			update:{
				fn: this.onUpdate,
				scope: this
			}
		});*/
	},
 	
	// Overriden parent object method, with additional functionality
	onRender: function(ct, position) {
		CRM.panels.ContactDetails.superclass.onRender.call(this, ct, position);
		if (this.data) {
			this.update(this.data);
		}
	},
	
	// Custom method of component
	update: function(data) {
		this.data = data;
		this.tpl.overwrite(this.body, this.data);
		// Example code for custom events
		//this.fireEvent('update', this.data);
	}
	
	// The onUpdate method, combined with other code commented out in
	// this template, is used to demonstrate how to create custom events
	/*,
	onUpdate: function(data){
		console.log('in onUpdate');
	}*/
});