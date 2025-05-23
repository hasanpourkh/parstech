document.addEventListener('DOMContentLoaded', function() {
    // تنظیم منوی کاربر
    const userMenuToggle = document.getElementById('userMenuToggle');
    const userMenuDropdown = document.getElementById('userMenuDropdown');

    if (userMenuToggle && userMenuDropdown) {
        userMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            userMenuDropdown.style.display =
                userMenuDropdown.style.display === 'none' ||
                userMenuDropdown.style.display === '' ? 'block' : 'none';
        });

        // بستن منو با کلیک خارج از آن
        document.addEventListener('click', function(e) {
            if (!userMenuToggle.contains(e.target) && !userMenuDropdown.contains(e.target)) {
                userMenuDropdown.style.display = 'none';
            }
        });
    }

    // مدیریت منوهای سایدبار
    const sidebarMenu = document.getElementById('sidebar-menu');
    if (sidebarMenu) {
        const menuLinks = sidebarMenu.querySelectorAll('.has-treeview > a');

        menuLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const parentLi = this.parentElement;
                const wasOpen = parentLi.classList.contains('menu-open');

                // بستن سایر منوها
                sidebarMenu.querySelectorAll('.has-treeview.menu-open').forEach(function(item) {
                    if (item !== parentLi) {
                        item.classList.remove('menu-open');
                    }
                });

                // تغییر وضعیت منوی فعلی
                parentLi.classList.toggle('menu-open');

                // انیمیشن نرم برای باز/بسته شدن
                const submenu = parentLi.querySelector('.nav-treeview');
                if (submenu) {
                    if (!wasOpen) {
                        submenu.style.display = 'block';
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    } else {
                        submenu.style.maxHeight = '0';
                        setTimeout(() => {
                            submenu.style.display = 'none';
                        }, 200);
                    }
                }
            });
        });
    }

    // مدیریت نمایش سایدبار در موبایل
    const toggleButtons = document.querySelectorAll('[data-widget="pushmenu"]');

    toggleButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('sidebar-open');
        });
    });

    // بستن سایدبار در موبایل با کلیک خارج از آن
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 991.98) {
            const sidebar = document.querySelector('.main-sidebar');
            const toggleButton = document.querySelector('[data-widget="pushmenu"]');

            if (!sidebar.contains(e.target) && !toggleButton.contains(e.target)) {
                document.body.classList.remove('sidebar-open');
            }
        }
    });

    // تنظیم منوی فعال بر اساس URL
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll('.nav-sidebar .nav-link');

    navLinks.forEach(function(link) {
        if (link.getAttribute('href') && currentPath.includes(link.getAttribute('href'))) {
            link.classList.add('active');

            const treeview = link.closest('.nav-treeview');
            if (treeview) {
                treeview.parentElement.classList.add('menu-open');
            }
        }
    });
});
