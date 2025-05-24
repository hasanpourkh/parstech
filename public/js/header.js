// استیکی هدر با مخفی شدن هنگام اسکرول به پایین و ظاهر شدن هنگام اسکرول بالا
document.addEventListener('DOMContentLoaded', function() {
    var prevScrollPos = window.scrollY;
    var header = document.getElementById('main-header');

    // نوتیف محصولات با موجودی کم
    var notifBadge = document.getElementById('notif-badge');
    var notifMarkSeenBtn = document.getElementById('notif-mark-seen-btn');
    var notifProductItems = document.querySelectorAll('.notif-product-item');
    var notifDropdown = document.getElementById('notif-dropdown');

    // جمع آیدی محصولات کم موجودی فعلی
    var productIds = [];
    notifProductItems.forEach(function(el){
        var pid = el.getAttribute('data-product-id');
        if(pid) productIds.push(pid);
    });

    // آی‌دی‌هایی که کاربر قبلا دیده (در localStorage)
    var seenIds = [];
    try {
        seenIds = JSON.parse(localStorage.getItem('lowStockSeen')) || [];
    } catch(e) {
        seenIds = [];
    }

    // فقط محصولات جدید (که کاربر ندیده) را نوتیف کن
    var unseenIds = productIds.filter(id => !seenIds.includes(id));

    // اگر تعداد محصولات ندیده > 0 نوتیف نشون بده
    if(notifBadge) {
        if(unseenIds.length > 0) {
            notifBadge.innerText = unseenIds.length;
            notifBadge.style.display = '';
            if(notifMarkSeenBtn) notifMarkSeenBtn.style.display = '';
            // فقط آیتم‌های ندیده را نمایش بده
            notifProductItems.forEach(function(el){
                if(!unseenIds.includes(el.getAttribute('data-product-id'))) el.style.display = 'none';
            });
        } else {
            notifBadge.style.display = 'none';
            if(notifMarkSeenBtn) notifMarkSeenBtn.style.display = 'none';
            notifProductItems.forEach(function(el){
                el.style.display = 'none';
            });
        }
    }

    // روی دکمه "متوجه شدم" کلیک شود: آی‌دی محصولات فعلی به seen اضافه شود
    if(notifMarkSeenBtn) {
        notifMarkSeenBtn.addEventListener('click', function(){
            var allSeen = Array.from(new Set(seenIds.concat(unseenIds)));
            localStorage.setItem('lowStockSeen', JSON.stringify(allSeen));
            if(notifBadge) notifBadge.style.display = 'none';
            notifProductItems.forEach(function(el){
                el.style.display = 'none';
            });
            notifMarkSeenBtn.style.display = 'none';
        });
    }

    // همیشه استیکی باشه
    if(header) {
        window.addEventListener('scroll', function() {
            var currentScrollPos = window.scrollY;
            if (currentScrollPos > prevScrollPos && currentScrollPos > 80) {
                header.style.top = '-80px';
            } else {
                header.style.top = '0';
            }
            prevScrollPos = currentScrollPos;
        });
        header.style.position = 'sticky';
        header.style.top = '0';
        header.style.width = '100%';
        header.style.zIndex = '1030';
    }

    // ApexCharts فروش ساعتی (نمایش با hover)
    var salesSummary = document.querySelector('.sales-summary');
    var salesChartPopup = document.getElementById('sales-chart-popup');
    var chartRendered = false;

    if(salesSummary && salesChartPopup){
        salesSummary.addEventListener('mouseenter', function(){
            salesChartPopup.style.display = 'block';
            if(!chartRendered){
                renderSalesHourlyChart();
                chartRendered = true;
            }
        });
        salesSummary.addEventListener('mouseleave', function(){
            salesChartPopup.style.display = 'none';
        });
    }

    function renderSalesHourlyChart(){
        if(typeof ApexCharts === 'undefined' || !window.hourlySales) return;
        // ساخت لیبل‌های ساعت
        var hours = [];
        for(var i=0; i<24; i++){
            hours.push((i < 10 ? '0' : '') + i + ':00');
        }
        var chartOptions = {
            chart: {
                type: 'bar',
                height: 210,
                toolbar: { show: false },
                fontFamily: 'Vazirmatn, Tahoma, Arial',
            },
            series: [{
                name: "فروش (تومان)",
                data: window.hourlySales
            }],
            xaxis: {
                categories: hours,
                labels: { style: { colors: '#5E5873', fontSize: '12px' } }
            },
            yaxis: {
                labels: { style: { colors: "#5E5873" } }
            },
            colors: ["#00c6ff"],
            fill: { type: 'gradient', gradient: { shade: 'light', type: "vertical", shadeIntensity: 0.4, gradientToColors: ["#0072ff"], inverseColors: false, opacityFrom: 0.8, opacityTo: 1, stops: [0, 100] } },
            plotOptions: {
                bar: { borderRadius: 6, columnWidth: '55%', distributed: true }
            },
            tooltip: {
                y: { formatter: function(val) { return val.toLocaleString() + ' تومان'; } }
            },
            grid: { show: true, borderColor: '#f1f1f1' }
        };
        var chart = new ApexCharts(document.querySelector("#sales-hourly-chart"), chartOptions);
        chart.render();
    }
});
