//Carousel - Talentos recientes 
var owl = $('.owl-one');
owl.owlCarousel({
 // animateOut: 'slideOutDown',
 // animateIn: 'flipInX',
 navText: ["<div class='nav-btn prev-slide'><p class='arrow'>‹</p></div>", "<div class='nav-btn next-slide'><p class='arrow'>›</p></div>"],
 autoplay:true,
 autoplayTimeout:3000,
 autoplayHoverPause:true,
 loop: true,
 nav: true,
 margin: 10,
 smartSpeed: 250,
 responsive: {
  0: {
   items: 1
  },
  600: {
   items: 3
  },
  960: {
   items: 3
  },
  1200: {
   items: 6
  }
 }
});

//Carousel - Mundo influencer
$(document).ready(function() {
  var owl2 = $('.owl-two');
    owl2.owlCarousel({
      //items: 2,
      loop: true,
      margin: 10,
      autoplay: true,
      autoplayTimeout: 8000,
      autoplayHoverPause: true,
      responsive: {
        0: {
         items: 1
        },
        600: {
         items: 1
        },
        960: {
         items: 2
        },
        1200: {
         items: 2
        }
       }
    }); 
  });

// Carousel - Categoria 
var owl3 = $('.owl-three');
owl3.owlCarousel({
 // animateOut: 'slideOutDown',
 // animateIn: 'flipInX',
  navText: ["<div class='nav-btn prev-slide'><p class='arrow'>‹</p></div>", "<div class='nav-btn next-slide'><p class='arrow'>›</p></div>"],
 autoplay:true,
 autoplayTimeout:10000,
 autoplayHoverPause:true,
 dots: false,
 loop: true,
 nav: true,
 margin: 10,
 smartSpeed: 250,
 responsive: {
  0: {
   items: 1
  },
  600: {
   items: 3
  },
  960: {
   items: 3
  },
  1200: {
   items: 5
  }
 }
});

// Carousel - El/la + Seguid@ 
var owl4 = $('.owl-four');
owl4.owlCarousel({
 // animateOut: 'slideOutDown',
 // animateIn: 'flipInX',
  navText: ["<div class='nav-btn prev-slide'><p class='arrow'>‹</p></div>", "<div class='nav-btn next-slide'><p class='arrow'>›</p></div>"],
 autoplay:true,
 autoplayTimeout:10000,
 autoplayHoverPause:true,
 dots: false,
 loop: true,
 nav: true,
 margin: 10,
 smartSpeed: 250,
 responsive: {
  0: {
   items: 1
  },
  600: {
   items: 3
  },
  960: {
   items: 3
  },
  1200: {
   items: 5
  }
 }
});



// Carousel - Comments (profile page)
var owl5 = $('.owl-five');
owl5.owlCarousel({
 // animateOut: 'slideOutDown',
 // animateIn: 'flipInX',
  navText: ["<div class='nav-btn-comments prev-slide-comments'><p class='arrow-comments'>‹</p></div>", "<div class='nav-btn-comments next-slide-comments'><p class='arrow-comments'>›</p></div>"],
 autoplay:true,
 autoplayTimeout:7000,
 autoplayHoverPause:true,
 dots: false,
 loop: true,
 nav: true,
 margin: 10,
 smartSpeed: 250,
 responsive: {
  0: {
   items: 1
  },
  600: {
   items: 1
  },
  960: {
   items: 1
  },
  1200: {
   items: 3
  }
 }
});


$(document).ready(function() {
  var owl = $('.owl-carousel-x');
  owl.owlCarousel({
    items: 3,
    loop: true,
    margin: 10,
    nav: true,
    autoplay: true,
    autoplayTimeout: 20000,
    autoplayHoverPause: true
  });
  $('.play').on('click', function() {
    owl.trigger('play.owl.autoplay', [1000])
  })
  $('.stop').on('click', function() {
    owl.trigger('stop.owl.autoplay')
  })
})