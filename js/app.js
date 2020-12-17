
//add active class to the current page
const url = window.location;

$('ul.nav a').filter(function(){
  return this.href == url;
}).parent().addClass('active');
