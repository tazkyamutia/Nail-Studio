const transactionTable = document.getElementById('transactionTable');
const loadMoreButton = document.getElementById('loadMore');

const moreTransactions = [
    {
        id: '#003',
        buyer: 'Tazkya',
        items: ['Black Cute Nails'],
        prices: ['Rp 125.000'],
        total: 'Rp 125.000',
        rating: '★★★★☆',
    },
    {
        id: '#004',
        buyer: 'Aqila',
        items: ['Cows', 'Flowers'],
        prices: ['Rp 135.000', 'Rp 170.000'],
        total: 'Rp 305.000',
        rating: '★★★★★',
    },
    {
        id: '#005',
        buyer: 'Irene',
        items: ['Tropical Bloom Nails', 'Black Cute Nails'],
        prices: ['Rp 140.000', 'Rp 125.000'],
        total: 'Rp 265.000',
        rating: '★★★★★',
    },
    {
        id: '#006',
        buyer: 'Yeri',
        items: ['Tropical Bloom Nails', 'Black Cute Nails'],
        prices: ['Rp 140.000', 'Rp 125.000'],
        total: 'Rp 265.000',
        rating: '★★★★★',
    },
    {
        id: '#007',
        buyer: 'Seulgi',
        items: ['Fake Pinks', 'Green Elegance'],
        prices: ['Rp 200.000', 'Rp 130.000'],
        total: 'Rp 330.000',
        rating: '★★★★★',
    },
];

loadMoreButton.addEventListener('click', () => {
    moreTransactions.forEach(transaction => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${transaction.id}</td>
            <td>${transaction.buyer}</td>
            <td>
                <ul>
                    ${transaction.items.map(item => `<li>${item}</li>`).join('')}
                </ul>
            </td>
            <td>
                <ul>
                    ${transaction.prices.map(price => `<li>${price}</li>`).join('')}
                </ul>
            </td>
            <td>${transaction.total}</td>
            <td class="rating">${transaction.rating}</td>
        `;
        transactionTable.appendChild(row);
    });

    loadMoreButton.disabled = true;
    loadMoreButton.innerText = 'All Data Has Been Loaded';
});

const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
	sidebar.classList.toggle('hide');
})

const searchButton = document.querySelector('#content nav form .form-input button');
const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
const searchForm = document.querySelector('#content nav form');

searchButton.addEventListener('click', function (e) {
	if(window.innerWidth < 576) {
		e.preventDefault();
		searchForm.classList.toggle('show');
		if(searchForm.classList.contains('show')) {
			searchButtonIcon.classList.replace('bx-search', 'bx-x');
		} else {
			searchButtonIcon.classList.replace('bx-x', 'bx-search');
		}
	}
})



if(window.innerWidth < 768) {
	sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
	searchButtonIcon.classList.replace('bx-x', 'bx-search');
	searchForm.classList.remove('show');
}


window.addEventListener('resize', function () {
	if(this.innerWidth > 576) {
		searchButtonIcon.classList.replace('bx-x', 'bx-search');
		searchForm.classList.remove('show');
	}
})


const switchMode = document.getElementById('switch-mode');

switchMode.addEventListener('change', function () {
	if(this.checked) {
		document.body.classList.add('dark');
	} else {
		document.body.classList.remove('dark');
	}
})  