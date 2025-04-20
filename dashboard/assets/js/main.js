
function loadCategories() {
  fetch('http://api.cc.localhost/index.php/categories')
    .then(res => res.json())
    .then(categories => {
      const tbody = document.querySelector('.categories tbody');
      tbody.innerHTML = '';

      categories.forEach(category => {
        const row = document.createElement('tr');

		row.innerHTML =
		'<td>' + category.name + '</td>' +
		'<td>' + category.name + '</td>' +
		'<td>' + category.created_at + '</td>' +
		'<td>' + category.updated_at + '</td>' +
		'<td>' + (category.parent_id ?? '—') + '</td>';
  
  
  
        tbody.appendChild(row);
      });
    });
}

loadCategories();