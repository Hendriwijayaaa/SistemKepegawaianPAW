 function searchTable() {
            // Mendapatkan input pengguna
            var input = document.getElementById("search-table").value.toLowerCase();

            // Mendapatkan baris dalam tabel
            var rows = document.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

            var found = false;
            var visibleRowCount = 0; // menambahkan variabel untuk menghitung jumlah baris yang terlihat

            // Looping melalui setiap baris tabel
            for (var i = 0; i < rows.length; i++) {
                // Mendapatkan sel di setiap baris tabel
                var cells = rows[i].getElementsByTagName("td");
                // Looping melalui setiap sel dalam baris
                for (var j = 0; j < cells.length; j++) {
                    var cellText = cells[j].textContent.toLowerCase();
                    // Memeriksa apakah sel ini cocok dengan input pengguna
                    if (cellText.includes(input)) {
                        found = true;
                        break;
                    }
                }

                // Mengubah tampilan baris sesuai hasil pencarian
                if (found) {
                    rows[i].style.display = "";
                    visibleRowCount++; // menambahkan 1 pada variabel jika baris ditampilkan
                    found = false;
                } else {
                    rows[i].style.display = "none";
                }
            }

            // Menampilkan pesan jika tidak ada data yang cocok dengan input pengguna
            var noDataMessage = document.getElementById("no-data");

            if (visibleRowCount === 0) {
                noDataMessage.style.display = "block"; // menampilkan pesan jika tidak ada baris yang terlihat
            } else {
                noDataMessage.style.display = "none"; // menyembunyikan pesan jika ada baris yang terlihat
            }
}

        // Memanggil fungsi searchTable setiap kali input pengguna berubah
document.getElementById("search-table").addEventListener("input", searchTable);

        var table = document.querySelector('.table');
        var tbody = table.querySelector('tbody');
        var tr = tbody.querySelectorAll('tr');
        var currentPage = 1;
        var rowsPerPage = 10;
        var totalPages = Math.ceil(tr.length / rowsPerPage);

        // function to hide all rows except those to be displayed in the current page
        function showRows(page) {
            var start = (page - 1) * rowsPerPage;
            var end = start + rowsPerPage;
            for (var i = 0; i < tr.length; i++) {
                if (i >= start && i < end) {
                    tr[i].style.display = '';
                } else {
                    tr[i].style.display = 'none';
                }
            }
        }

   // function to generate pagination buttons
function generatePagination() {
    var pagination = document.querySelector('.pagination');
    pagination.innerHTML = '';

    // add Previous button
    var prevButton = document.createElement('li');
    prevButton.classList.add('page-item', 'disabled');
    prevButton.innerHTML = '<span class="page-link">Previous</span>';
    if (currentPage > 1) {
        prevButton.classList.remove('disabled');
        prevButton.addEventListener('click', function () {
            currentPage--;
            showRows(currentPage);
            generatePagination();
        });
    }
    pagination.appendChild(prevButton);

    // add Number buttons
    var numButtonsToShow = 5; // Number of pagination buttons to show

    var startPage = Math.max(1, currentPage - Math.floor(numButtonsToShow / 2));
    var endPage = Math.min(startPage + numButtonsToShow - 1, totalPages);

    for (var i = startPage; i <= endPage; i++) {
        var numberButton = document.createElement('li');
        numberButton.classList.add('page-item');
        if (i === currentPage) {
            numberButton.classList.add('active');
            numberButton.setAttribute('aria-current', 'page');
            numberButton.innerHTML = '<span class="page-link">' + i + '</span>';
        } else {
            numberButton.innerHTML = '<a href="#" class="page-link">' + i + '</a>';
            numberButton.addEventListener('click', function (i) {
                return function () {
                    currentPage = i;
                    showRows(currentPage);
                    generatePagination();
                };
            }(i));
        }
        pagination.appendChild(numberButton);
    }

    // add Next button
    var nextButton = document.createElement('li');
    nextButton.classList.add('page-item', 'disabled');
    nextButton.innerHTML = '<span class="page-link">Next</span>';
    if (currentPage < totalPages) {
        nextButton.classList.remove('disabled');
        nextButton.addEventListener('click', function () {
            currentPage++;
            showRows(currentPage);
            generatePagination();
        });
    }
    pagination.appendChild(nextButton);
}

// initial setup
showRows(currentPage);
generatePagination();

    