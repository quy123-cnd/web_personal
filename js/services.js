const nav_link = document.querySelectorAll('.nav-link');

nav_link.forEach(the =>{
    the.addEventListener('click', ()=>{
        nav_link.forEach(item => item.classList.remove('active'));

        the.classList.add('active')
    })
})




// 1. Lấy tất cả các nút bấm, khung gói giá và thẻ tiêu đề
const tab_btn = document.querySelectorAll('.tab-btn');
const tabContents = document.querySelectorAll('.pricing-grid');
const categoryNameSpan = document.getElementById('category-name');

// 2. Lặp qua từng nút để gán sự kiện Click
tab_btn.forEach(button => {
    button.addEventListener('click', () => {
        tab_btn.forEach(btn => btn.classList.remove('active'));
        
        tabContents.forEach(content => content.style.display = 'none');

        button.classList.add('active');

    
        const targetId = button.getAttribute('data');
        const targetContent = document.getElementById(targetId);
        if (targetContent) {
            targetContent.style.display = 'grid'; 
        }

        // E. Đổi chữ tiêu đề "Gói Dịch Vụ [Ảnh Cưới]" tự động
        const name = button.getAttribute('data-name');
        if (categoryNameSpan && name) {
            categoryNameSpan.innerText = name;
        }
    });
});


const pricing_cards = document.querySelectorAll('.pricing-card');
    pricing_cards.forEach(card =>{
        const card_quote = card.querySelector('.card-quote');

        if (!card_quote) {
            return;
        }

        card.addEventListener('mouseenter', () => {
        card_quote.style.display = 'block';   
        });

        card.addEventListener('mouseleave', () => {
        card_quote.style.display = 'none';
        });

    });



