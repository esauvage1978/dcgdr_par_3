function onClickToggleSubscription(event) {
    event.preventDefault();

    const url = this.href;
    const spanToggle = this.querySelector('span.js-toggle');
    const spanMessage = spanToggle.querySelector('span');
    const icone = spanToggle.querySelector('i');
    const title_icone = this.querySelector('.js-title-icone');
    const subscription = document.querySelector('.js-subscription-nbr h3');
    const subscription_title = document.querySelector('.js-subscription-nbr p');

    axios.get(url).then(function (response) {
        spanMessage.textContent = response.data.message;
        if (!response.data.value) {
            title_icone.classList = "js-title-icone text-warning";
            icone.classList = "fa fa-times text-warning";
            spanMessage.classList = "text-warning";
        } else {
            title_icone.classList = "js-title-icone text-success";
            icone.classList = "fa fa-check text-success";
            spanMessage.classList = "text-success";
        }
    }).catch(function (error) {
        if (error.response.status === 403) {
            windows.alert('Vous n\'êtes pas connecté');
        } else {
            windows.alert('Une erreur s\'est produite, merci de contacter l\'administrateur : '.error.response.status);
        }
    });

}


document.querySelectorAll("a.js-subscription").forEach(function (link) {
    link.addEventListener('click', onClickToggleSubscription);
});