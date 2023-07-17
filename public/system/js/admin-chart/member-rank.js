$(function () {
    const optionsCharge = $('.member-charge-options')
    const optionsClassified = $('.member-classified-options')

    $.ajax({
        url: optionsCharge.last().attr('href'),
        success: function (result) {
            if(result.data.length > 0)
                updateChargeRank(result.data)
        }
    })

    $.ajax({
        url: optionsClassified.last().attr('href'),
        success: function (result) {
            if(result.data.length > 0)
                updateClassifiedRank(result.data)
        }
    })

    optionsCharge.click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $('#list-charge-users').empty()

       $.ajax({
           url,
           success: function (result) {
               $('.member-charge-choose').html(result.title)
               if (result.data.length > 0)
                   updateChargeRank(result.data)
           }
       })
    })

    optionsClassified.click(function (evt) {
        evt.preventDefault();
        let url = $(this).attr('href')
        $('#list-classified-users').empty()

       $.ajax({
           url,
           success: function (result) {
               $('.member-classified-choose').html(result.title)
               if (result.data.length > 0)
                   updateClassifiedRank(result.data)
           }
       })
    })


})

function updateChargeRank([top_1, ...users]){
    let html_top1 = generateChargeRankMember(top_1, 1, 1);
    let result_html = html_top1 + users.map((item, index) => {
       return generateChargeRankMember(item, index + 2);
   }).join('');

    $('#list-charge-users').append(result_html)
}

function updateClassifiedRank([top_1, ...users]){
    let html_top1 = generateClassifiedRankMember(top_1, 1, 1);
    let result_html = html_top1 + users.map((item, index) => {
       return generateClassifiedRankMember(item, index + 2);
   }).join('');

    $('#list-classified-users').append(result_html)
}

function generateChargeRankMember(user, index = 1, top1 = false){
    const base_href = (document.querySelector('base') || {}).href;

   return `
    <div class="member-item">
        <div class="index">${index}</div>
        <div class="avatar">
            <div class="image">
            ${ top1 ?
            `<div class="top1">
                <img src="${base_href}images/icons/top1.png" alt="">
            </div>` : '' }
                <img src="${user.image_url ? user.image_url : '/frontend/images/login/people.png'}" alt="">
            </div>
        </div>

        <div class="member-info">
            <div class="name">${user.fullname}</div>
            <div class="info">
                <span>Số Coin đã nạp:</span>
                <span>${formatCurrency(parseInt(user.total_charge))}</span>
            </div>
        </div>
        <div class="view">
            <a target="_blank" href="${ $('#list-charge-users').data('coin-href') + '?keyword=' + user.phone_number }" class="text-blue-light">Xem</a>
        </div>
    </div>`
}

function generateClassifiedRankMember(user, index = 1, top1 = false){
    const base_href = (document.querySelector('base') || {}).href;

    return `
    <div class="member-item">
        <div class="index">${index}</div>
        <div class="avatar">
            <div class="image">
            ${ top1 ?
        `<div class="top1">
                <img src="${base_href}images/icons/top1.png" alt="">
            </div>` : '' }
                <img src="${user.image_url ? user.image_url : '/frontend/images/login/people.png'}" alt="">
            </div>
        </div>

        <div class="member-info">
            <div class="name">${user.fullname}</div>
            <div class="info">
                <span>Số Tin đã đăng:</span>
                <span>${formatCurrency(parseInt(user.total_post))}</span>
            </div>
        </div>
        <div class="view">
            <a target="_blank" href="${ $('#list-classified-users').data('classified-href') + '?keyword=' + user.phone_number }" class="text-blue-light">Xem</a>
        </div>
    </div>`
}

function formatCurrency(number){
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
