document.addEventListener('DOMContentLoaded', () => {
    const tableRows = document.querySelectorAll('tbody tr');
    const tableHeadings = document.querySelectorAll('thead th');

    tableHeadings.forEach((head, i) => {
        let sortAsc = true;
        head.onclick = () => {
            tableHeadings.forEach(head => head.classList.remove('active'));
            head.classList.add('active');

            document.querySelectorAll('td').forEach(td => td.classList.remove('active'));
            tableRows.forEach(row => {
                row.querySelectorAll('td')[i].classList.add('active');
            });

            head.classList.toggle('asc', sortAsc);
            sortAsc = head.classList.contains('asc') ? false : true;

            sortTable(i, sortAsc);
        };
    });

    function sortTable(column, sortAsc) {
        [...tableRows].sort((a, b) => {
            const firstRow = a.querySelectorAll('td')[column].textContent.toLowerCase();
            const secondRow = b.querySelectorAll('td')[column].textContent.toLowerCase();
            return sortAsc ? (firstRow < secondRow ? 1 : -1) : (firstRow < secondRow ? -1 : 1);
        }).forEach(sortedRow => document.querySelector('tbody').appendChild(sortedRow));
    }

    const pdfBtn = document.querySelector('#toPDF');
    const watchlistTable = document.querySelector('#watchlist_table');

    const toPDF = function (table) {
        const htmlCode = `
        <!DOCTYPE html>
        <link rel="stylesheet" type="text/css" href="watchlist.css">
        <main class="table" id="watchlist_table">${table.innerHTML}</main>`;

        const newWindow = window.open();
        newWindow.document.write(htmlCode);

        setTimeout(() => {
            newWindow.print();
            newWindow.close();
        }, 400);
    };

    pdfBtn.onclick = () => {
        toPDF(watchlistTable);
    };

    const jsonBtn = document.querySelector('#toJSON');

    const toJSON = function (table) {
        const tableData = [];
        const tHead = [];
        const tHeadings = table.querySelectorAll('th');
        const tRows = table.querySelectorAll('tbody tr');

        for (let tHeading of tHeadings) {
            const actualHead = tHeading.textContent.trim().split(' ');
            tHead.push(actualHead.splice(0, actualHead.length - 1).join(' ').toLowerCase());
        }

        tRows.forEach(row => {
            const rowObject = {};
            const tCells = row.querySelectorAll('td');

            tCells.forEach((tCell, cellIndex) => {
                const img = tCell.querySelector('img');
                if (img) {
                    rowObject['company image'] = decodeURIComponent(img.src);
                }
                rowObject[tHead[cellIndex]] = tCell.textContent.trim();
            });
            tableData.push(rowObject);
        });

        return JSON.stringify(tableData, null, 4);
    };

    jsonBtn.onclick = () => {
        const json = toJSON(watchlistTable);
        downloadFile(json, 'json');
    };

    const downloadFile = function (data, fileType, fileName = '') {
        const a = document.createElement('a');
        a.download = fileName;
        const mimeTypes = {
            'json': 'application/json',
        };
        a.href = `data:${mimeTypes[fileType]};charset=utf-8,${encodeURIComponent(data)}`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    };
});


