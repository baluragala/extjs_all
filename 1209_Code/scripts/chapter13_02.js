/*
 * Chapter 13 Example 2
 * Custom Panel component, ContactDetails
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
 	
 	var userDetail = new CRM.panels.ContactDetails({
 		applyTo: 'chap13_ex02',
 		title: 'Chapter 13 Example 2',
 		data: userData[0]
 	});
 	
 	updateContact = function(event,el,data){
		userDetail.update(data.data);
	}
 	
 	Ext.get('actionLink').on('click',updateContact,this,{data:userData[1]});
});