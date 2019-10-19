$('body').on('click', '.btn.dropdown-toggle', function(e){
    console.log($(this).next().toggle()) ;
})