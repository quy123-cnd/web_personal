const tab_btn = document.querySelectorAll('.tab-btn');
const album_cards = document.querySelectorAll('.album-card');
const collection_title = document.querySelector('.big-title');
const image_lists = document.querySelectorAll('.image-list');
const category_name = document.getElementById('category-name');

tab_btn.forEach(btn => {
    btn.addEventListener('click', () => {
        const selected_category = btn.dataset.category;

        tab_btn.forEach(button => button.classList.remove('active'));
        btn.classList.add('active');

            album_cards.forEach(card => {
                card.hidden = card.dataset.category !== selected_category;
            });

        if (category_name) {
            category_name.textContent = btn.dataset.name;
        }

        image_lists.forEach(images => {
            images.hidden = true;

        });
    });
});





const album_modal = document.getElementById('albumModal');
const modal_close = document.querySelector('.modal-close');
const modal_title = document.getElementById('modalTitle');


album_cards.forEach(card => {

    card.addEventListener('click', () => {

        const selected_card = card.dataset.card;

        image_lists.forEach(images => {
            images.hidden = images.dataset.card !== selected_card;
        });

        modal_title.textContent = card.querySelector('h3').textContent.toUpperCase();

        album_modal.classList.add('show');

    });

});

modal_close.addEventListener('click', () => {

    album_modal.classList.remove('show');

});


const image_modal = document.getElementById('image-modal');
const image_modal_close = document.querySelector('.image-modal-close');
const fullImage = document.getElementById("fullImage");
const images = document.querySelectorAll(".image-list img");

    images.forEach(image =>{
        image.addEventListener("click", function() {

        fullImage.src = image.src;

        fullImage.alt = image.alt;

        image_modal.classList.add("show");
        });    
    })

    image_modal_close.addEventListener("click", function() {

    image_modal.classList.remove("show");

    fullImage.src = "";
    });