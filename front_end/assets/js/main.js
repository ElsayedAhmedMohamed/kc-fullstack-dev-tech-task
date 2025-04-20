
function loadCategories() {
  fetch('http://api.cc.localhost/index.php/categories')
    .then(res => res.json())
    .then(categories => {
      const ul = document.querySelector('.categories-list');
      ul.innerHTML = '';

      categories.forEach(category => {
		 
		 
		if(category.parent_id === null) {
			
        const li = document.createElement('li');
		const coursesCount  = category.count_of_courses > 0 ? ' ('+category.count_of_courses+') ' : '' ;
		li.innerHTML = '<li><a onclick="filterByCategory(event)" attr-id="'+btoa(category.id)+'" attr-name="'+category.name+'" href="#">' + category.name + coursesCount + '</a></li>';
		
		if(category.subcategories){
		categories.forEach(subcategory => {
		if(subcategory.parent_id === category.id) {
		const subli = document.createElement("li");
		const coursesCount  = subcategory.count_of_courses > 0 ? ' ('+subcategory.count_of_courses+') ' : '' ;
		subli.innerHTML = '<li class="sub-category"><a onclick="filterByCategory(event)" attr-id="'+btoa(subcategory.id)+'" attr-name="'+subcategory.name+'" href="#">' + subcategory.name + coursesCount + '</a></li>';
		li.appendChild(subli);
		}
		});
	  }
	  ul.appendChild(li);
		}
  
  
        
      });
    });
}


function loadCourses() {
  fetch('http://api.cc.localhost/index.php/courses')
    .then(res => res.json())
    .then(courses => {
      const coursesGrid = document.querySelector('.courses');
      coursesGrid.innerHTML = '';

      courses.forEach(course => {
        const courseGrid = document.createElement('div');
		courseGrid.classList.add('course');
		courseGrid.classList.add(btoa(course.category_id));
		courseGrid.innerHTML = '<div class="category-label">' + course.main_category_name + '</div><div class="course-img"><img src="' + course.preview + '"/></div><div class="course-info"><h2>' + course.name + '</h2><p>' + course.description + '</p></div>';
        coursesGrid.appendChild(courseGrid);
      });
    });
}



function filterByCategory(id) {
	const contentElements = document.querySelectorAll(".course");
	contentElements.forEach(element => {
		element.classList.add("hidden");
	});
	const courseID = event.target.getAttribute('attr-id');
	const courseName = event.target.getAttribute('attr-name');
	document.querySelector('.page-title').innerHTML = courseName;
	document.querySelector('.'+courseID).classList.remove('hidden');
}
		
		
loadCategories();
loadCourses();