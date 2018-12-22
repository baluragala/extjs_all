/*
 * Chapter 13 Example 4
 * Custom Panel components, UserDetail and AddressDetail,
 * applie to a BorderLayout, using their custom xtypes
 */

// Save all processing until the
// DOM is completely loaded
Ext.onReady(function(){
	var userData = [
		{ID:1,FIRSTNAME:'John',LASTNAME:'Lennon',EMAIL:'john@beatles.com',PASSWORD:'apple1',ADDRESSTYPE:'Home (Mailing)',STREET1:'117 Abbey Road',STREET2:'',STREET3:'',CITY:'New York',STATE:'NY',ZIP:'12345',PHONETYPE:'Cell',PHONE:'123-456-7890'},
		{ID:2,FIRSTNAME:'Paul',LASTNAME:'McCartney',EMAIL:'paul@beatles.com',PASSWORD:'linda',ADDRESSTYPE:'Work (Mailing)',STREET1:'108 Penny Lane',STREET2:'',STREET3:'',CITY:'Los Angeles',STATE:'CA',ZIP:'67890',PHONETYPE:'Home',PHONE:'456-789-0123'},
		{ID:3,FIRSTNAME:'George',LASTNAME:'Harrison',EMAIL:'george@beatles.com',PASSWORD:'timebandit',ADDRESSTYPE:'Home (Shipping)',STREET1:'302 Space Way',STREET2:'',STREET3:'',CITY:'Billings',STATE:'MT',ZIP:'98765',PHONETYPE:'Office',PHONE:'890-123-4567'},
		{ID:4,FIRSTNAME:'Ringo',LASTNAME:'Starr',EMAIL:'bignose@beatles.com',PASSWORD:'barbie',ADDRESSTYPE:'Home (Mailing)',STREET1:'789 Zildizhan Pl',STREET2:'',STREET3:'',CITY:'Malibu',STATE:'CA',ZIP:'43210',PHONETYPE:'Home',PHONE:'567-890-1234'}
	];
 	
 	var ContactDetail = new Ext.Panel({
 		title: 'Contact Details',
 		applyTo: 'chap13_ex04',
 		width: 400,
 		height: 125,
 		layout: 'border',
 		frame: true,
 		items:[{
 			region: 'west',
 			xtype: 'userdetail',
 			width: 200,
 			data: userData[0]
 		},{
 			region: 'center',
 			xtype: 'addrdetail',
 			width: 200,
 			data: userData[0]
 		}]
 	});
});