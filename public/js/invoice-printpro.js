// Highlight unpaid if any
document.addEventListener('DOMContentLoaded', function(){
    // Highlight unpaid row if exists
    let summaryRows = document.querySelectorAll('.summary-box tr');
    summaryRows.forEach(function(row){
        if(row.children[0] && row.children[0].innerText.includes('مانده قابل پرداخت')) {
            let cell = row.children[1];
            if(parseInt(cell.innerText.replace(/,/g, '')) > 0) {
                cell.style.color = '#d32f2f';
                cell.style.fontWeight = 'bold';
                row.style.background = '#fff4e3';
            }
        }
    });

    // Print shortcut
    document.body.addEventListener('keydown', function(e){
        if (e.ctrlKey && e.key === 'p') {
            window.print();
        }
    });
    // Optional: Auto scroll to top on print
    window.onbeforeprint = function(){
        window.scrollTo(0,0);
    };
});
