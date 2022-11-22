const form = document.getElementById('form');
		const username = document.getElementById('username');
		const email = document.getElementById('email');
		const password = document.getElementById('password');
		const errorElement = document.getElementById('error');

		form.addEventListener('submit',(e) =>{
			let messages = [];
			if (name.value === '' || name.value == null) {
				messages.push('Name is required');
			}

			if (email.value === '' || email.value == null) {
				messages.push('Email is required');
			}

			if (password.value === '' || password.value == null) {
				messages.push('Password is required');
			}

			if (messages.length > 0) {
				e.preventDefault();
				errorElement.innerText = messages.join(', ');
			}
		});

		function ValidateEmail(input) {

		  var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

		  if (input.value.match(validRegex)) {

		    alert("Valid email address!");

		    document.form.text1.focus();

		    return true;

		  } else {

		    alert("Invalid email address!");

		    document.form.text1.focus();

		    return false;

		  }
		}