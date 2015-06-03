// JavaScript Document

$(function(){
	//
	if($.fn.editable){
		$.fn.editable.defaults.mode = 'inline' 
		$('.txtEdit').editable({
			type: 'text', url: '/home/assets/common/ajax.inc.php'
		});
		$('.txtAreaEdit').editable({
			type: 'textarea', url: '/home/assets/common/ajax.inc.php'
		});
		$('.selectEdit').editable({
			type: 'select', url: '/home/assets/common/ajax.inc.php'
		});
	}
	$('#navigation').find('li:has(ul)').click( function(event) {
		if (this == event.target) {
			$(this).toggleClass('expanded');
			$(this).children('ul').toggle('medium');
		}
		return false;
	}).addClass('collapsed').children('ul').hide();
	/*
	* Password indicator screens
	*/
	$(".new_pass").keyup(function(){
		if($(this).val() != '')
		passwordStrength($(this).val());   //Call the password strength function
	});
	$(".confirm_new_pass").keyup(function(){
		if($(this).val() != $(".new_pass").val()){
			$(".passDesc").html('Mismatch');
			document.getElementById("passStrength").className = "strength1";
		}else{
			passwordStrength($(this).val());	
		}
	});
	$("a.delete").live("click",function(event){
		return confirm($(this).attr('data'));
	});
	($('#sidebar_left').height() < $('#main_content').height()) ? $('#sidebar_left').height($('#main_content').height()) : $('#main_content').height($('#sidebar_left').height());
	//Close any notification area showing
	setTimeout(closeNotification, 5000);
	$("div#notification .close").click(function(){
		closeNotification();
	});
	$('select.filter').change(function(){
		var goto = location.href.substring(0, location.href.indexOf('filter')) ? location.href.substring(0, location.href.indexOf('filter')-1) : location.href;
		location.href=goto + '&filter=' + $('select.filter :selected').val();
	});
	$('input.date').datepicker();
	
	$('input.fromDate').datepicker({
		changeMonth:true
		, onClose : function( selectedDate ){
			dDay = new Date(selectedDate); 
			dTimeStamp = new Date(dDay.setDate(dDay.getDate() +  parseInt($('input.fromDate').attr('data-days')) ));
			var maxDt = padLeadZero(dTimeStamp.getMonth()+1) +'/'+ padLeadZero(dTimeStamp.getDate()) +'/'+ dTimeStamp.getFullYear();
			$('input.toDate').datepicker("option",{minDate: selectedDate, maxDate : maxDt});
		}
	});
	$('input.toDate').datepicker({
		changeMonth:true
	});
});
var padLeadZero = function(n){
	return (n < 10) ? ('0' + n) : n;
}
var closeNotification = function(){
	if ( $('div#notification').css('display') == 'block' )
		$('div#notification').slideUp('slow');
}
var passwordStrength = function(password){
	/**************  Function to help get password strength  **********/
	var desc = new Array();	
	desc[0] = "Very Weak";
	desc[1] = "Weak";
	desc[2] = "Better";
	desc[3] = "Medium";
	desc[4] = "Strong";
	desc[5] = "Strongest";
	
	var score   = 0;
	//if password bigger than 6 give 1 point
	if (password.length > 6) score++;
	//if password has both lower and uppercase characters give 1 point      
	if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
	//if password has at least one number give 1 point
	if (password.match(/\d+/)) score++;
	//if password has at least one special caracther give 1 point
	if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) ) score++;
	//if password bigger than 12 give another 1 point
	if (password.length > 12) score++;
	$("#passDesc").html(desc[score]);
	document.getElementById("passStrength").className = "strength" + score;
}
