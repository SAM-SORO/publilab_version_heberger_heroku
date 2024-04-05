(function($) {

	"use strict";

	var fullHeight = function() {

		$('.js-fullheight').css('height', $(window).height() + 300);
		$(window).resize(function(){
			$('.js-fullheight').css('height', $(window).height());
		});

	};
	fullHeight();

	$('#sidebarCollapse').on('click', function () {
      $('#sidebar').toggleClass('active');
  });


})(jQuery);

/*
// window.onload = function() {};
// window.addEventListener("load", function() {});
c'est deux methodes permettes  de faire une action une fois que la page est chargée.
document.addEventListener('DOMContentLoaded', function () {}

La propriété classList est une propriété de l'interface Element en JavaScript qui représente la liste des classes d'un élément HTML. Elle fournit des méthodes pour ajouter, supprimer et basculer des classes sur l'élément.

La méthode toggle de classList permet de basculer la présence d'une classe spécifiée. Si la classe est présente, elle sera supprimée, et si elle est absente, elle sera ajoutée.


/*/

window.addEventListener("load", function() {
    const togglePassword1 = document.querySelector('#toggle-password1');
    const password = document.querySelector('#password');

    if (togglePassword1 && password) {
        togglePassword1.addEventListener('click', function () {

            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Changer l'icône en conséquence
            togglePassword1.querySelector('i').classList.toggle('fa-eye-slash');
            togglePassword1.querySelector('i').classList.toggle('fa-eye');
        });
    }


    const togglePassword2 = document.getElementById('toggle-password2');
    const confirmPassword = document.getElementById('confirm-password');

    if (togglePassword2 && confirmPassword) {
        togglePassword2.addEventListener('click', function () {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);

            // Changer l'icône en conséquence
            togglePassword2.querySelector('i').classList.toggle('fa-eye-slash');
            togglePassword2.querySelector('i').classList.toggle('fa-eye');
        });
    }

});



//enregistrer un chercheur

$('#submit-enregistrer-chercheur').click(function(event) {
    event.preventDefault();

    var nom = $('#nom').val();
    var prenom = $('#prenom').val();
    var email = $('#email').val();
    var contact = $('#contact').val();
    var errors = false;

    // Validation du nom
    if (nom !== "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(nom)) {
        $('#nom').removeClass('is-invalid').addClass('is-valid');
        $('#error-nom').text('');
    } else {
        $('#nom').addClass('is-invalid').removeClass('is-valid');
        $('#error-nom').text('Le nom n\'est pas valide');
        errors = true;

    }

    // Validation du prénom
    if (prenom !== "" && /^[a-zA-Z ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/.test(prenom)) {
        $('#prenom').removeClass('is-invalid').addClass('is-valid');
        $('#error-prenom').text('');
    } else {
        $('#prenom').addClass('is-invalid').removeClass('is-valid');
        $('#error-prenom').text('Le prénom n\'est pas valide');
        errors = true;

    }

    // Validation de l'email
    if (email !== "" && /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)+$/.test(email)) {
        $('#email').removeClass('is-invalid').addClass('is-valid');
        $('#error-email').text('');
    } else {
        $('#email').addClass('is-invalid').removeClass('is-valid');
        $('#error-email').text('L\'adresse email n\'est pas valide');
        errors = true;

    }

    // Validation du contact
    if (contact !== "" && /^(?:\+225|00225)?\d{10}$/.test(contact.replace(/\s/g, ''))) {
        $('#contact').removeClass('is-invalid').addClass('is-valid');
        $('#error-contact').text('');
        errors = true;
    } else {
        $('#contact').addClass('is-invalid').removeClass('is-valid');
        $('#error-contact').text('Le numéro de téléphone n\'est pas valide');
        errors = true;
    }

    if(!$errors){
        $('#submit-enregistrer-chercheur').submit();
    }
});


/*
    Il existe des bibliothèques JavaScript telles que libphonenumber (Google's Phone Number Library) qui peuvent vous aider à valider et à formater les numéros de téléphone en fonction du pays. Vous pouvez l'utiliser pour valider les numéros de téléphone de manière précise et robuste dans votre application.
*/

//inscription visiteur

$('#submit-inscription').click(function(event) {
    event.preventDefault();

    var username = $('#username').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var passwordConfirmation = $('#confirm-password').val();
    var passwordLength = password.length;
    var errors = false;

    // Vérification du nom d'utilisateur
    if (username !== "" && /^[0-9A-Za-z]{6,16}$/.test(username)) {
        $('#username').removeClass('is-invalid').addClass('is-valid');
        $('#error-username').text('');
    } else {
        $('#username').addClass('is-invalid').removeClass('is-valid');
        $('#error-username').text('Le nom utilisateur doit contenir entre 6 et 16 caractères alphanumériques');
        errors = true;
    }

    // Vérification de l'email
    if (email !== "" && /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)+$/.test(email)) {
        $('#email').removeClass('is-invalid').addClass('is-valid');
        $('#error-email').text('');
    } else {
        $('#email').addClass('is-invalid').removeClass('is-valid');
        $('#error-email').text('L\'adresse email n\'est pas valide');
        errors = true;
    }

    // Vérification du mot de passe
    if (password) {
        if (passwordLength >= 8) {
            $('#password').removeClass('is-invalid').addClass('is-valid');
            $('#error-password').text('');
            // Vérification de la confirmation du mot de passe
            if (passwordConfirmation) {
                if (password === passwordConfirmation) {
                    $('#confirm-password').removeClass('is-invalid').addClass('is-valid');
                    $('#error-confirm-password').text('');
                } else {
                    $('#confirm-password').removeClass('is-valid').addClass('is-invalid');
                    $('#error-confirm-password').text('Les mots de passe ne correspondent pas');
                    errors = true;
                }
            } else {
                $('#confirm-password').removeClass('is-valid').addClass('is-invalid');
                $('#error-confirm-password').text('Veuillez confirmer le mot de passe');
                errors = true;
            }
        } else {
            $('#password').addClass('is-invalid').removeClass('is-valid');
            $('#error-password').text('Le mot de passe doit contenir au minimum 8 caractères');
            errors = true;
        }
    } else {
        $('#password').addClass('is-invalid').removeClass('is-valid');
        $('#error-password').text('Veuillez renseigner votre mot de passe');
        errors = true;
    }

    // Si aucune erreur n'est présente, soumettre le formulaire
    if (!errors) {
        $('#form-inscription').submit();
    }

    // Empêcher la soumission du formulaire si des erreurs sont présentes
    event.preventDefault();
});



//login visiteur
$('#submit-connexion').click(function(event) {
    event.preventDefault();
    var email = $('#email').val();
    var password = $('#password').val();
    var passwordLength = password.length;
    var $errors = false;

    if (!email) {
        $('#email').addClass('is-invalid').removeClass('is-valid');
        $('#error-email').text('Veuillez renseigner ce champ');
        $errors = true;
    } else if (!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)+$/.test(email)) {
        $('#email').addClass('is-invalid').removeClass('is-valid');
        $('#error-email').text('L\'adresse email n\'est pas valide');
        $errors = true;
    } else {
        $('#email').addClass('is-valid').removeClass('is-invalid');
        $('#error-email').text('');
    }

    if (!password) {
        $('#password').addClass('is-invalid').removeClass('is-valid');
        $('#error-password').text('Veuillez renseigner votre mot de passe');
        $errors = true;
    } else if (passwordLength <= 7) {
        $('#password').addClass('is-invalid').removeClass('is-valid');
        $('#error-password').text('Votre mot de passe doit contenir au moins 8 caractères');
        $errors = true;
    } else {
        $('#password').addClass('is-valid').removeClass('is-invalid');
        $('#error-password').text('');
    }

    if(!$errors){
        $('#form-login').submit();
    }

});


function emailExist(){
    var urlEmail = $('email').attr('ElevatedButton')
}

