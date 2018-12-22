/*
 * Chapter 14 Example 4
 * Simple Date formatting
 */

// Save all processing until the
// DOM is completely loaded
Ext.onReady(function(){
	var cls = "Band";
	var member = {
		firstName: 'Eric',
		lastName: 'Clapton',
		position: 'Lead Guitarist',
		birthDate: new Date('03/30/1945')
	};
	
	var pnl = new Ext.Panel({
		applyTo: 'chap14_ex05',
		width: 200,
		height: 100,
		bodyStyle: 'padding:5px',
		title: 'Band Member',
		html: String.format('<div class=\'{0}\'>{2}, {1}: {3}<br />DOB: {4}</div>', cls, member.firstName, member.lastName, member.position, member.birthDate.format('F j, Y'))
	});
});