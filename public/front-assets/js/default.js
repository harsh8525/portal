(document).ready(function(){				
			('.toggle-view li .acc-header').click(function (){
				var text = (this).siblings('#account-from');
				if (text.is(':hidden')) {
					text.slideDown(800);
					(this).children('h1').addClass('active-h1');		
				} else {
					text.slideUp(800);
					(this).children('h1').removeClass('active-h1');		
				} 
			});
			
			//.parent().filter(":first-child").children(".acc-header").click();
		});