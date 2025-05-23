<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>دفتر روزنامه</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <style>
        body { direction: rtl; background:#f9f9f9; }
        .journal-table th, .journal-table td { text-align: center; vertical-align: middle; }
        .container { max-width: 900px; margin-top: 40px; background: #fff; border-radius: 8px; box-shadow: 0 0 10px #ddd; padding: 30px; }
        h1 { margin-bottom: 30px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>دفتر روزنامه</h1>
        <table class="table table-bordered journal-table">
            <thead class="table-light">
                <tr>
                    <th>ردیف</th>
                    <th>تاریخ</th>
                    <th>شرح</th>
                    <th>بدهکار</th>
                    <th>بستانکار</th>
                    <th>کد حساب</th>
                    <th>نام حساب</th>
                </tr>
            </thead>
            <tbody>
                <!-- داده‌های نمونه برای تست -->
                <tr>
                    <td>1</td>
                    <td>1404/03/01</td>
                    <td>ثبت فروش کالا</td>
                    <td>5,000,000</td>
                    <td>0</td>
                    <td>101</td>
                    <td>وجوه نقد</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>1404/03/01</td>
                    <td>ثبت فروش کالا</td>
                    <td>0</td>
                    <td>5,000,000</td>
                    <td>401</td>
                    <td>درآمد فروش</td>
                </tr>
                <!-- پایان داده نمونه -->
            </tbody>
        </table>

        <div class="alert alert-info mt-3">
            این یک نمونه اولیه از دفتر روزنامه است.<br>
            برای نمایش داده‌های واقعی باید اطلاعات حسابداری (سندها و آیتم‌ها) در دیتابیس ثبت و کدنویسی شود.
        </div>
    </div>
</body>
</html>
